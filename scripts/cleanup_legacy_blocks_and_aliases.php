<?php

use Drupal\node\NodeInterface;
use Drupal\path_alias\Entity\PathAlias;

$legacy_block_ids = [
  'advertidement',
  'blogad',
  'bloggridpagesidebar',
  'blogmostcommentedsidebar',
  'blogscategorysidebar',
  'blogssidebardetailspage',
  'drupify_aboutishfaq',
  'drupify_aboutriaz',
  'drupify_ishfaqinfo',
  'drupify_ishfaqskills',
  'drupify_riazinfo',
  'drupify_riazsskills',
  'servicesidebar',
  'sidebar',
  'sidebarblog',
  'views_block__banner_block_1',
  'views_block__blog_block_12',
];

$block_storage = \Drupal::entityTypeManager()->getStorage('block');
foreach ($legacy_block_ids as $block_id) {
  $block = $block_storage->load($block_id);
  if (!$block) {
    print "Missing block: {$block_id}\n";
    continue;
  }

  if ($block->status()) {
    $block->disable();
    $block->save();
    print "Disabled legacy block: {$block_id}\n";
  }
  else {
    print "Already disabled: {$block_id}\n";
  }
}

$team_aliases = [
  28 => '/team/abdur-rehman',
  30 => '/team/jahangir',
  31 => '/team/fazal-haq',
  32 => '/team/riaz',
  33 => '/team/ishfaq-ahmad',
  127 => '/team/zeeshan',
  133 => '/team/manzoor-ahmad',
];

$portfolio_aliases = [
  13 => '/portfolio/extermatrim',
  15 => '/portfolio/cruschalva',
  16 => '/portfolio/mrkcontracting',
  17 => '/portfolio/ubm',
  18 => '/portfolio/wizardoorer',
  19 => '/portfolio/qytera',
  20 => '/portfolio/foxbrospools',
  21 => '/portfolio/simplyrenting',
];

$alias_storage = \Drupal::entityTypeManager()->getStorage('path_alias');
$set_alias = static function (NodeInterface $node, string $alias) use ($alias_storage): void {
  $source = '/node/' . $node->id();
  $existing = $alias_storage->loadByProperties([
    'path' => $source,
    'langcode' => $node->language()->getId(),
  ]);

  $canonical = NULL;
  foreach ($existing as $path_alias) {
    if ($path_alias instanceof PathAlias && $path_alias->getAlias() === $alias) {
      $canonical = $path_alias;
      break;
    }
  }

  if (!$canonical instanceof PathAlias) {
    $canonical = reset($existing);
    if ($canonical instanceof PathAlias) {
      $canonical->setAlias($alias);
      $canonical->save();
      print "Updated alias: {$source} -> {$alias}\n";
    }
    else {
      PathAlias::create([
        'path' => $source,
        'alias' => $alias,
        'langcode' => $node->language()->getId(),
      ])->save();
      print "Created alias: {$source} -> {$alias}\n";
      return;
    }
  }
  else {
    print "Alias already clean: {$source} -> {$alias}\n";
  }

  foreach ($existing as $path_alias) {
    if ($path_alias instanceof PathAlias && $path_alias->id() !== $canonical->id()) {
      print "Deleted duplicate alias: {$source} -> " . $path_alias->getAlias() . "\n";
      $path_alias->delete();
    }
  }
};

$node_storage = \Drupal::entityTypeManager()->getStorage('node');
foreach ($team_aliases + $portfolio_aliases as $nid => $alias) {
  $node = $node_storage->load($nid);
  if (!$node instanceof NodeInterface) {
    print "Missing node for alias cleanup: {$nid}\n";
    continue;
  }

  $set_alias($node, $alias);
}
