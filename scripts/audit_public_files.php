<?php

use Drupal\Core\Database\Database;

$drupal_root = \Drupal::root();
$public_dir = \Drupal::service('file_system')->realpath('public://');
$project_root = dirname(__DIR__);
$report_dir = $project_root . '/docs';

if (!$public_dir || !is_dir($public_dir)) {
  throw new RuntimeException('Could not locate public:// files directory.');
}

$physical_files = [];
$iterator = new RecursiveIteratorIterator(
  new RecursiveDirectoryIterator($public_dir, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
  if (!$file->isFile()) {
    continue;
  }

  $path = $file->getPathname();
  $relative = str_replace($public_dir . '/', '', $path);
  $physical_files[$relative] = [
    'relative' => $relative,
    'path' => $path,
    'basename' => basename($relative),
    'size' => $file->getSize(),
    'extension' => strtolower(pathinfo($relative, PATHINFO_EXTENSION)),
    'references' => [],
  ];
}

$connection = Database::getConnection();

try {
  $rows = $connection->select('file_managed', 'fm')
    ->fields('fm', ['fid', 'uri', 'filename', 'status'])
    ->execute();

  foreach ($rows as $row) {
    if (!str_starts_with($row->uri, 'public://')) {
      continue;
    }

    $relative = substr($row->uri, strlen('public://'));
    if (isset($physical_files[$relative])) {
      $physical_files[$relative]['references'][] = 'file_managed fid ' . $row->fid . ' status ' . $row->status;
    }
  }
}
catch (Throwable $e) {
  print "Could not scan file_managed: {$e->getMessage()}\n";
}

$database_text = '';
$columns = $connection->query(
  "SELECT TABLE_NAME, COLUMN_NAME
   FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE()
   AND DATA_TYPE IN ('char', 'varchar', 'text', 'mediumtext', 'longtext')"
)->fetchAll();

foreach ($columns as $column) {
  $table = $column->TABLE_NAME;
  $field = $column->COLUMN_NAME;

  try {
    $values = $connection->query("SELECT `$field` FROM `$table` WHERE `$field` IS NOT NULL")->fetchCol();
  }
  catch (Throwable $e) {
    continue;
  }

  foreach ($values as $value) {
    if ($value !== '') {
      $database_text .= "\n" . $value;
    }
  }
}

$code_text = '';
$scan_dirs = [
  $project_root . '/themes/custom',
  $project_root . '/modules/custom',
];

foreach ($scan_dirs as $scan_dir) {
  if (!is_dir($scan_dir)) {
    continue;
  }

  $code_iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($scan_dir, FilesystemIterator::SKIP_DOTS)
  );
  foreach ($code_iterator as $file) {
    if (!$file->isFile()) {
      continue;
    }

    $extension = strtolower(pathinfo($file->getPathname(), PATHINFO_EXTENSION));
    if (!in_array($extension, ['css', 'js', 'twig', 'yml', 'theme', 'php', 'html'], TRUE)) {
      continue;
    }

    $contents = file_get_contents($file->getPathname());
    if ($contents !== FALSE) {
      $code_text .= "\n" . $contents;
    }
  }
}

foreach ($physical_files as $relative => &$info) {
  $encoded_relative = str_replace('%2F', '/', rawurlencode($relative));
  $basename = $info['basename'];
  $encoded_basename = rawurlencode($basename);
  $tokens = [
    'public://' . $relative,
    '/sites/default/files/' . $relative,
    'sites/default/files/' . $relative,
    $relative,
    $encoded_relative,
  ];

  foreach ($tokens as $token) {
    if ($token !== '' && str_contains($database_text, $token)) {
      $info['references'][] = 'database text/html';
      break;
    }
  }

  foreach ([$relative, $encoded_relative, $basename, $encoded_basename] as $token) {
    if ($token !== '' && str_contains($code_text, $token)) {
      $info['references'][] = 'theme/custom code';
      break;
    }
  }

  $info['references'] = array_values(array_unique($info['references']));
}
unset($info);

$used = [];
$candidates = [];
$generated = [];
$junk = [];

foreach ($physical_files as $relative => $info) {
  $is_generated = str_starts_with($relative, 'css/') || str_starts_with($relative, 'js/') || str_starts_with($relative, 'php/');
  $is_junk = in_array($info['basename'], ['.DS_Store', 'Thumbs.db'], TRUE);

  if ($is_junk) {
    $junk[] = $info;
  }
  elseif ($is_generated) {
    $generated[] = $info;
  }
  elseif ($info['references']) {
    $used[] = $info;
  }
  else {
    $candidates[] = $info;
  }
}

$sort_by_size = static function (array &$items): void {
  usort($items, static fn(array $a, array $b): int => $b['size'] <=> $a['size']);
};
$sort_by_size($used);
$sort_by_size($candidates);
$sort_by_size($generated);
$sort_by_size($junk);

$format_bytes = static function (int $bytes): string {
  if ($bytes >= 1048576) {
    return round($bytes / 1048576, 2) . ' MB';
  }
  if ($bytes >= 1024) {
    return round($bytes / 1024, 2) . ' KB';
  }
  return $bytes . ' B';
};

$total_size = array_sum(array_column($physical_files, 'size'));
$candidate_size = array_sum(array_column($candidates, 'size'));
$generated_size = array_sum(array_column($generated, 'size'));
$junk_size = array_sum(array_column($junk, 'size'));

$lines = [];
$lines[] = '# Drupify Public Files Asset Audit';
$lines[] = '';
$lines[] = 'Generated by `scripts/audit_public_files.php`.';
$lines[] = '';
$lines[] = '## Summary';
$lines[] = '';
$lines[] = '- Total public files: ' . count($physical_files) . ' (' . $format_bytes($total_size) . ')';
$lines[] = '- Used/reference-detected files: ' . count($used);
$lines[] = '- Unreferenced candidate files: ' . count($candidates) . ' (' . $format_bytes($candidate_size) . ')';
$lines[] = '- Generated aggregate/cache files: ' . count($generated) . ' (' . $format_bytes($generated_size) . ')';
$lines[] = '- Junk files: ' . count($junk) . ' (' . $format_bytes($junk_size) . ')';
$lines[] = '';
$lines[] = '## Safe Cleanup Rule';
$lines[] = '';
$lines[] = 'Archive junk files automatically. Generated aggregate/cache files can be archived only when stale because Drupal can regenerate them; recent/current generated files should be left alone. Keep unreferenced candidates for human review because unpublished content or external references may still need them.';
$lines[] = '';
$lines[] = '## Largest Unreferenced Candidates';
$lines[] = '';
foreach (array_slice($candidates, 0, 80) as $item) {
  $lines[] = '- `' . $item['relative'] . '` - ' . $format_bytes($item['size']);
}
$lines[] = '';
$lines[] = '## Generated Aggregate/Cache Files';
$lines[] = '';
foreach (array_slice($generated, 0, 120) as $item) {
  $lines[] = '- `' . $item['relative'] . '` - ' . $format_bytes($item['size']);
}
$lines[] = '';
$lines[] = '## Junk Files';
$lines[] = '';
foreach ($junk as $item) {
  $lines[] = '- `' . $item['relative'] . '` - ' . $format_bytes($item['size']);
}

file_put_contents($report_dir . '/drupify-public-files-asset-audit.md', implode("\n", $lines) . "\n");
file_put_contents($report_dir . '/drupify-public-files-asset-audit.json', json_encode([
  'summary' => [
    'total_files' => count($physical_files),
    'total_size' => $total_size,
    'used_files' => count($used),
    'candidate_files' => count($candidates),
    'candidate_size' => $candidate_size,
    'generated_files' => count($generated),
    'generated_size' => $generated_size,
    'junk_files' => count($junk),
    'junk_size' => $junk_size,
  ],
  'used' => $used,
  'candidates' => $candidates,
  'generated' => $generated,
  'junk' => $junk,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

print "Wrote docs/drupify-public-files-asset-audit.md\n";
print "Wrote docs/drupify-public-files-asset-audit.json\n";
print "Candidates: " . count($candidates) . " (" . $format_bytes($candidate_size) . ")\n";
print "Generated: " . count($generated) . " (" . $format_bytes($generated_size) . ")\n";
print "Junk: " . count($junk) . " (" . $format_bytes($junk_size) . ")\n";
