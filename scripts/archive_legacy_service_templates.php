<?php

$project_root = dirname(__DIR__);
$template_root = $project_root . '/themes/custom/drupify/templates';
$archive_root = $project_root . '/_archive/theme-templates/legacy-service-' . date('Ymd-His');

$files = [
  'paragraph/service/paragraph--service_list.html.twig',
  'paragraph/service/paragraph--service_lists.html.twig',
  'paragraph/service/paragraph--services_page_2.html.twig',
];

$archived = [];
foreach ($files as $relative) {
  $source = $template_root . '/' . $relative;
  if (!is_file($source)) {
    continue;
  }

  $destination = $archive_root . '/' . $relative;
  if (!is_dir(dirname($destination))) {
    mkdir(dirname($destination), 0775, TRUE);
  }

  if (rename($source, $destination)) {
    $archived[] = $relative;
    print "Archived {$relative}\n";
  }
}

if (!is_dir($archive_root)) {
  mkdir($archive_root, 0775, TRUE);
}

file_put_contents($archive_root . '/manifest.json', json_encode([
  'reason' => 'Service detail pages now render service list paragraph data directly in page--node--service.html.twig.',
  'archived' => $archived,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

print "Archived " . count($archived) . " legacy service templates to {$archive_root}\n";
