<?php

use Drupal\path_alias\Entity\PathAlias;

/**
 * Moves an existing node alias out of the way so a View page can own the path.
 */
function drupify_move_node_alias($node_path, $old_alias) {
  $storage = \Drupal::entityTypeManager()->getStorage('path_alias');
  $existing = $storage->loadByProperties([
    'path' => $node_path,
    'alias' => $old_alias,
    'langcode' => 'en',
  ]);

  if (!$existing) {
    return;
  }

  $new_alias = $old_alias . '-old-page';
  foreach ($storage->loadByProperties(['alias' => $new_alias, 'langcode' => 'en']) as $alias) {
    $alias->delete();
  }

  foreach ($existing as $alias) {
    $alias->set('alias', $new_alias);
    $alias->save();
  }

  echo "Moved {$old_alias} node alias to {$new_alias}.\n";
}

/**
 * Adds or updates a simple View page display.
 */
function drupify_ensure_view_page($view_id, $path, $title, $items_per_page = 24) {
  $view = \Drupal::entityTypeManager()->getStorage('view')->load($view_id);
  if (!$view) {
    throw new \RuntimeException("View {$view_id} was not found.");
  }

  $displays = $view->get('display');
  if (!isset($displays['page'])) {
    $displays['page'] = $displays['default'];
    $displays['page']['id'] = 'page';
    $displays['page']['display_title'] = $title . ' Page';
    $displays['page']['display_plugin'] = 'page';
    $displays['page']['position'] = count($displays);
  }

  $displays['page']['display_options']['path'] = ltrim($path, '/');
  $displays['page']['display_options']['defaults']['title'] = FALSE;
  $displays['page']['display_options']['defaults']['pager'] = FALSE;
  $displays['page']['display_options']['defaults']['sorts'] = FALSE;
  $displays['page']['display_options']['title'] = $title;
  $displays['page']['display_options']['menu'] = [
    'type' => 'none',
    'title' => '',
    'description' => '',
    'weight' => 0,
    'expanded' => FALSE,
    'menu_name' => 'main',
    'parent' => '',
    'context' => '0',
  ];
  $displays['page']['display_options']['pager'] = [
    'type' => 'full',
    'options' => [
      'items_per_page' => $items_per_page,
      'offset' => 0,
      'id' => 0,
      'total_pages' => NULL,
      'tags' => [
        'next' => 'Next',
        'previous' => 'Previous',
        'first' => 'First',
        'last' => 'Last',
      ],
      'expose' => [
        'items_per_page' => FALSE,
        'items_per_page_label' => 'Items per page',
        'items_per_page_options' => '5, 10, 25, 50',
        'items_per_page_options_all' => FALSE,
        'items_per_page_options_all_label' => '- All -',
        'offset' => FALSE,
        'offset_label' => 'Offset',
      ],
      'quantity' => 9,
    ],
  ];

  if ($view_id === 'team_member_') {
    $displays['page']['display_options']['sorts'] = [
      'field_ordering_value' => [
        'id' => 'field_ordering_value',
        'table' => 'node__field_ordering',
        'field' => 'field_ordering_value',
        'relationship' => 'none',
        'group_type' => 'group',
        'admin_label' => '',
        'plugin_id' => 'standard',
        'order' => 'ASC',
        'exposed' => FALSE,
        'expose' => ['label' => ''],
      ],
      'created' => [
        'id' => 'created',
        'table' => 'node_field_data',
        'field' => 'created',
        'relationship' => 'none',
        'group_type' => 'group',
        'admin_label' => '',
        'plugin_id' => 'date',
        'order' => 'DESC',
        'exposed' => FALSE,
        'expose' => ['label' => ''],
        'granularity' => 'second',
      ],
    ];
  }

  if ($view_id === 'client_') {
    $displays['page']['display_options']['sorts'] = [
      'title' => [
        'id' => 'title',
        'table' => 'node_field_data',
        'field' => 'title',
        'relationship' => 'none',
        'group_type' => 'group',
        'admin_label' => '',
        'plugin_id' => 'standard',
        'order' => 'ASC',
        'exposed' => FALSE,
        'expose' => ['label' => ''],
      ],
    ];
  }

  $view->set('display', $displays);
  $view->save();

  echo "View {$view_id}.page is available at {$path}.\n";
}

drupify_move_node_alias('/node/65', '/team');
drupify_move_node_alias('/node/66', '/clients');

$node_storage = \Drupal::entityTypeManager()->getStorage('node');
foreach ([65, 66] as $nid) {
  $node = $node_storage->load($nid);
  if ($node && $node->isPublished()) {
    $node->setUnpublished();
    $node->save();
    echo "Unpublished old page holder node {$nid}: {$node->label()}.\n";
  }
}

drupify_ensure_view_page('team_member_', '/team', 'Team', 24);
drupify_ensure_view_page('client_', '/clients', 'Clients', 24);
