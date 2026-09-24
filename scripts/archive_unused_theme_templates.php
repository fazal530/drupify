<?php

use Drupal\Core\Entity\EntityInterface;

$project_root = dirname(__DIR__);
$template_root = $project_root . '/themes/custom/drupify/templates';
$archive_root = $project_root . '/_archive/theme-templates/unused-' . date('Ymd-His');
$etm = \Drupal::entityTypeManager();

$protected_paragraph_bundles = [];
$protected_view_displays = [
  'blog:page',
  'client_:page',
  'team_member_:page',
];
$seen_paragraphs = [];

$walk = static function (EntityInterface $entity) use (&$walk, &$protected_paragraph_bundles, &$protected_view_displays, &$seen_paragraphs): void {
  foreach ($entity->getFields() as $field) {
    $type = $field->getFieldDefinition()->getType();

    if ($type === 'entity_reference_revisions') {
      foreach ($field->referencedEntities() as $referenced) {
        if ($referenced->getEntityTypeId() !== 'paragraph') {
          continue;
        }

        if (isset($seen_paragraphs[$referenced->id()])) {
          continue;
        }

        $seen_paragraphs[$referenced->id()] = TRUE;
        $protected_paragraph_bundles[$referenced->bundle()] = TRUE;
        $walk($referenced);
      }
    }

    if ($type === 'viewsreference') {
      foreach ($field as $item) {
        $view = $item->target_id ?? '';
        $display = $item->display_id ?? '';
        if ($view !== '' && $display !== '') {
          $protected_view_displays[$view . ':' . $display] = TRUE;
        }
      }
    }
  }
};

$published_node_ids = \Drupal::entityQuery('node')
  ->accessCheck(FALSE)
  ->condition('status', 1)
  ->execute();
foreach ($etm->getStorage('node')->loadMultiple($published_node_ids) as $node) {
  $walk($node);
}

foreach ($etm->getStorage('block')->loadMultiple() as $block) {
  if (!$block->status() || !str_starts_with($block->getPluginId(), 'block_content:')) {
    continue;
  }

  $uuid = substr($block->getPluginId(), strlen('block_content:'));
  $blocks = $etm->getStorage('block_content')->loadByProperties(['uuid' => $uuid]);
  $content_block = reset($blocks);
  if ($content_block instanceof EntityInterface) {
    $walk($content_block);
  }
}

$protected_paragraph_bundles['drupify_faq_item'] = TRUE;
$protected_paragraph_bundles['drupify_process_item'] = TRUE;
$protected_paragraph_bundles['drupify_pricing_item'] = TRUE;
$protected_paragraph_bundles['drupify_testimonial_item'] = TRUE;
$protected_paragraph_bundles['drupify_why_item'] = TRUE;

$move_file = static function (string $path) use ($template_root, $archive_root): bool {
  $relative = substr($path, strlen($template_root) + 1);
  $destination = $archive_root . '/' . $relative;
  if (!is_dir(dirname($destination))) {
    mkdir(dirname($destination), 0775, TRUE);
  }
  return rename($path, $destination);
};

$archived = [];

$paragraph_iterator = new RecursiveIteratorIterator(
  new RecursiveDirectoryIterator($template_root . '/paragraph', FilesystemIterator::SKIP_DOTS)
);
foreach ($paragraph_iterator as $file) {
  if (!$file->isFile() || $file->getExtension() !== 'twig') {
    continue;
  }

  $basename = $file->getBasename('.html.twig');
  if (!str_starts_with($basename, 'paragraph--')) {
    continue;
  }

  $bundle = substr($basename, strlen('paragraph--'));
  $bundle = str_replace('-', '_', $bundle);

  if (isset($protected_paragraph_bundles[$bundle])) {
    continue;
  }

  if ($move_file($file->getPathname())) {
    $archived[] = substr($file->getPathname(), strlen($template_root) + 1);
  }
}

$view_iterator = new RecursiveIteratorIterator(
  new RecursiveDirectoryIterator($template_root . '/view', FilesystemIterator::SKIP_DOTS)
);
foreach ($view_iterator as $file) {
  if (!$file->isFile() || $file->getExtension() !== 'twig') {
    continue;
  }

  $basename = $file->getBasename('.html.twig');
  if (!preg_match('/^views-view(?:-fields|-unformatted)?--(.+)--(.+)$/', $basename, $matches)) {
    continue;
  }

  $view = str_replace('-', '_', $matches[1]);
  $display = str_replace('-', '_', $matches[2]);
  if (isset($protected_view_displays[$view . ':' . $display])) {
    continue;
  }

  if ($move_file($file->getPathname())) {
    $archived[] = substr($file->getPathname(), strlen($template_root) + 1);
  }
}

$page_candidates = [
  $template_root . '/page/page--node--short_codes.html.twig',
];
foreach ($page_candidates as $path) {
  if (is_file($path) && $move_file($path)) {
    $archived[] = substr($path, strlen($template_root) + 1);
  }
}

if (!is_dir($archive_root)) {
  mkdir($archive_root, 0775, TRUE);
}

file_put_contents($archive_root . '/manifest.json', json_encode([
  'protected_paragraph_bundles' => array_keys($protected_paragraph_bundles),
  'protected_view_displays' => array_keys($protected_view_displays),
  'archived' => $archived,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

print "Protected paragraph bundles: " . count($protected_paragraph_bundles) . "\n";
print "Protected view displays: " . count($protected_view_displays) . "\n";
print "Archived templates: " . count($archived) . "\n";
print "Archive: {$archive_root}\n";
