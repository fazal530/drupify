<?php

$project_root = dirname(__DIR__);
$report_file = $project_root . '/docs/drupify-public-files-asset-audit.json';
$public_dir = \Drupal::service('file_system')->realpath('public://');
$archive_dir = $project_root . '/_archive/public-files-duplicate-branding-' . date('Ymd-His');

if (!is_file($report_file)) {
  throw new RuntimeException('Run scripts/audit_public_files.php before archiving duplicate branding assets.');
}

if (!$public_dir || !is_dir($public_dir)) {
  throw new RuntimeException('Could not locate public:// files directory.');
}

$report = json_decode(file_get_contents($report_file), TRUE, 512, JSON_THROW_ON_ERROR);
$candidates = $report['candidates'] ?? [];
$manifest = [];

$is_safe_branding_candidate = static function (string $relative): bool {
  $basename = basename($relative);

  if (str_contains($relative, '/')) {
    return FALSE;
  }

  return preg_match('/^(Inline Logo_\\d+|logo_(\\d+|\\d+_\\d+)|[Ff]avicon(_\\d+)?|Classic_Pro - 1(_\\d+)+)\\.png$/', $basename) === 1;
};

foreach ($candidates as $item) {
  $relative = $item['relative'] ?? '';
  if ($relative === '' || !$is_safe_branding_candidate($relative)) {
    continue;
  }

  $source = $public_dir . '/' . $relative;
  if (!is_file($source)) {
    continue;
  }

  $destination = $archive_dir . '/' . $relative;
  if (!is_dir(dirname($destination))) {
    mkdir(dirname($destination), 0775, TRUE);
  }

  if (!rename($source, $destination)) {
    print "Could not archive {$relative}\n";
    continue;
  }

  $manifest[] = [
    'relative' => $relative,
    'original' => $source,
    'archive' => $destination,
    'size' => $item['size'] ?? 0,
  ];
  print "Archived {$relative}\n";
}

if (!is_dir($archive_dir)) {
  mkdir($archive_dir, 0775, TRUE);
}

file_put_contents(
  $archive_dir . '/manifest.json',
  json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
);

print "Archived " . count($manifest) . " duplicate branding/demo files to {$archive_dir}\n";
