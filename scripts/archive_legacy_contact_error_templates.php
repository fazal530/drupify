<?php

$project_root = dirname(__DIR__);
$template_root = $project_root . '/themes/custom/drupify/templates';
$archive_root = $project_root . '/_archive/theme-templates/legacy-contact-error-' . date('Ymd-His');

$files = [
  'paragraph/contact/paragraph--contact_page_style_1.html.twig',
  'paragraph/home-3/paragraph--contact_style_3.html.twig',
  'paragraph/pages_of_pages/paragraph--error_page.html.twig',
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
  'reason' => 'Contact and error pages are rendered by modern page templates, so these legacy paragraph Twig templates are no longer needed for public rendering.',
  'archived' => $archived,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

print "Archived " . count($archived) . " legacy contact/error templates to {$archive_root}\n";
