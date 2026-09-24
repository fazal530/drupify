<?php

use Drupal\path_alias\Entity\PathAlias;

$view = \Drupal::entityTypeManager()->getStorage('view')->load('blog');
if (!$view) {
  throw new \RuntimeException('The blog View was not found.');
}

$display = $view->get('display');
$source = $display['default'];
$page = $source;
$page['id'] = 'page';
$page['display_title'] = 'Blog Page';
$page['display_plugin'] = 'page';
$page['position'] = 20;
$page['display_options']['display_description'] = 'Modern Drupify blog listing page.';
$page['display_options']['path'] = 'blog';
$page['display_options']['menu'] = [
  'type' => 'none',
  'title' => '',
  'description' => '',
  'weight' => 0,
  'expanded' => FALSE,
  'menu_name' => 'main',
  'parent' => '',
];
$page['display_options']['pager'] = [
  'type' => 'full',
  'options' => [
    'offset' => 0,
    'pagination_heading_level' => 'h4',
    'items_per_page' => 9,
    'total_pages' => NULL,
    'id' => 0,
    'tags' => [
      'next' => 'Next ›',
      'previous' => '‹ Previous',
      'first' => '« First',
      'last' => 'Last »',
    ],
    'expose' => [
      'items_per_page' => FALSE,
      'items_per_page_label' => 'Items per page',
      'items_per_page_options' => '9, 18, 27',
      'items_per_page_options_all' => FALSE,
      'items_per_page_options_all_label' => '- All -',
      'offset' => FALSE,
      'offset_label' => 'Offset',
    ],
    'quantity' => 5,
  ],
];
$page['display_options']['defaults']['pager'] = FALSE;

$display['page'] = $page;
$view->set('display', $display);
$view->save();

$alias_storage = \Drupal::entityTypeManager()->getStorage('path_alias');
$aliases = $alias_storage->loadByProperties(['alias' => '/blog']);
foreach ($aliases as $alias) {
  if ($alias instanceof PathAlias && $alias->getPath() === '/node/87') {
    $alias->set('alias', '/blog-old-page');
    $alias->save();
    echo "Moved /blog node alias to /blog-old-page.\n";
  }
}

echo "Blog View page display is available at /blog.\n";
