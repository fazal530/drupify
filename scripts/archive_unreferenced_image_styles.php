<?php

$project_root = dirname(__DIR__);
$report_file = $project_root . '/docs/drupify-public-files-asset-audit.json';
$public_dir = \Drupal::service('file_system')->realpath('public://');
$archive_dir = $project_root . '/_archive/public-files-image-styles-' . date('Ymd-His');

if (!is_file($report_file)) {
  throw new RuntimeException('Run scripts/audit_public_files.php before archiving image styles.');
}

if (!$public_dir || !is_dir($public_dir)) {
  throw new RuntimeException('Could not locate public:// files directory.');
}

$report = json_decode(file_get_contents($report_file), TRUE, 512, JSON_THROW_ON_ERROR);
$candidates = $report['candidates'] ?? [];
$manifest = [];

foreach ($candidates as $item) {
  $relative = $item['relative'] ?? '';
  if ($relative === '' || !str_starts_with($relative, 'styles/')) {
    continue;
  }

  $source = $public_dir . '/' . $relative;
  if (!is_file($source)) {
    continue;
  }

  $destination = $archive_dir . '/' . $relative;
  $destination_dir = dirname($destination);
  if (!is_dir($destination_dir)) {
    mkdir($destination_dir, 0775, TRUE);
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
}

if (!is_dir($archive_dir)) {
  mkdir($archive_dir, 0775, TRUE);
}

file_put_contents(
  $archive_dir . '/manifest.json',
  json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
);

print "Archived " . count($manifest) . " unreferenced image style files to {$archive_dir}\n";
