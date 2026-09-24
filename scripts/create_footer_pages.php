<?php

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\path_alias\Entity\PathAlias;

$entity_type_manager = \Drupal::entityTypeManager();

function drupify_ensure_industry_type() {
  if (!NodeType::load('industry')) {
    NodeType::create([
      'type' => 'industry',
      'name' => 'Industry',
      'description' => 'Industry landing pages for Drupify service positioning.',
    ])->save();
  }

  if (!FieldConfig::loadByName('node', 'industry', 'body')) {
    FieldConfig::create([
      'field_storage' => FieldStorageConfig::loadByName('node', 'body'),
      'bundle' => 'industry',
      'label' => 'Body',
      'settings' => [
        'display_summary' => TRUE,
        'required_summary' => FALSE,
      ],
    ])->save();
  }

  if (!FieldStorageConfig::loadByName('node', 'field_image')) {
    FieldStorageConfig::create([
      'field_name' => 'field_image',
      'entity_type' => 'node',
      'type' => 'image',
      'settings' => [
        'uri_scheme' => 'public',
        'default_image' => [],
      ],
    ])->save();
  }

  if (!FieldConfig::loadByName('node', 'industry', 'field_image')) {
    FieldConfig::create([
      'field_storage' => FieldStorageConfig::loadByName('node', 'field_image'),
      'bundle' => 'industry',
      'label' => 'Image',
      'settings' => [
        'file_directory' => '[date:custom:Y]-[date:custom:m]',
        'alt_field' => TRUE,
        'alt_field_required' => TRUE,
        'title_field' => FALSE,
      ],
    ])->save();
  }
}

function drupify_file_by_uri($uri) {
  $files = \Drupal::entityTypeManager()
    ->getStorage('file')
    ->loadByProperties(['uri' => $uri]);
  if ($files) {
    return reset($files);
  }
  if (!file_exists($uri)) {
    return NULL;
  }
  $file = File::create([
    'uri' => $uri,
    'status' => 1,
  ]);
  $file->save();
  return $file;
}

function drupify_set_alias($path, $alias) {
  $storage = \Drupal::entityTypeManager()->getStorage('path_alias');
  $existing = $storage->loadByProperties(['alias' => $alias, 'langcode' => 'en']);
  foreach ($existing as $item) {
    $item->delete();
  }
  PathAlias::create([
    'path' => $path,
    'alias' => $alias,
    'langcode' => 'en',
  ])->save();
}

function drupify_upsert_node($type, $title, $alias, array $body, $image_uri = '') {
  $storage = \Drupal::entityTypeManager()->getStorage('node');
  $existing = $storage->loadByProperties([
    'type' => $type,
    'title' => $title,
  ]);
  $node = $existing ? reset($existing) : Node::create([
    'type' => $type,
    'title' => $title,
    'status' => 1,
  ]);

  $node->set('body', [
    'summary' => $body['summary'],
    'value' => $body['value'],
    'format' => 'basic_html',
  ]);

  if ($image_uri && $node->hasField('field_image')) {
    $file = drupify_file_by_uri($image_uri);
    if ($file) {
      $node->set('field_image', [
        'target_id' => $file->id(),
        'alt' => $title,
      ]);
    }
  }

  $node->save();
  drupify_set_alias('/node/' . $node->id(), $alias);
  echo $type . "\t" . $node->id() . "\t" . $title . "\t" . $alias . "\n";
}

drupify_ensure_industry_type();

drupify_upsert_node('service', 'Drupal Support', '/service/drupal-support', [
  'summary' => 'Ongoing Drupal support, maintenance, updates, troubleshooting, and performance care for teams that need a dependable technical partner.',
  'value' => '<p>Drupal Support from Drupify keeps your website stable, secure, and easier to manage after launch. The service is built for teams that need reliable help with maintenance, updates, bug fixes, performance issues, and small improvements without starting a full rebuild.</p><p>We review the site structure, modules, theme layer, Composer setup, logs, forms, Views, content editing experience, and release process so support work is practical and controlled.</p><ul><li>Security updates and Composer maintenance</li><li>Bug fixing for Drupal themes, modules, Views, and forms</li><li>Performance checks, cache review, and frontend quality fixes</li><li>Monthly support planning for upgrades, content workflows, and launch stability</li></ul>',
], 'public://2024-08/drupal.jpg');

drupify_upsert_node('industry', 'Healthcare Drupal Websites', '/industry/healthcare', [
  'summary' => 'Drupal planning, development, and support for healthcare websites that need trust, accessibility, structured content, and dependable maintenance.',
  'value' => '<p>Healthcare websites need clarity, trust, accessibility, and careful content governance. Drupify helps healthcare teams use Drupal to organize services, locations, resources, provider information, forms, and patient-facing content in a maintainable way.</p><p>The goal is not only a better-looking website. The site should be easier for staff to update, safer to maintain, and clearer for visitors who need information quickly.</p><ul><li>Accessible service and resource pages</li><li>Structured content for departments, locations, and care information</li><li>Secure form and workflow planning</li><li>Ongoing Drupal updates, support, and frontend QA</li></ul>',
], 'public://2024-07/scalable.jpg');

drupify_upsert_node('industry', 'Education Drupal Websites', '/industry/education', [
  'summary' => 'Drupal websites for schools, training providers, and education teams that need structured content, easy publishing, and long-term support.',
  'value' => '<p>Education websites often carry many audiences at once: students, parents, staff, partners, and applicants. Drupify helps structure Drupal so programs, departments, news, resources, forms, and landing pages stay organized and easy to update.</p><p>We focus on practical content architecture, reusable page sections, responsive templates, accessibility, and support workflows that help the site grow without becoming messy.</p><ul><li>Program, course, department, and resource structures</li><li>Reusable landing page and content components</li><li>Accessible frontend templates for mobile and desktop</li><li>Drupal support for updates, publishing, and long-term maintenance</li></ul>',
], 'public://2024-07/innovative.jpg');

drupify_upsert_node('industry', 'Drupal Support for Agencies', '/industry/agencies', [
  'summary' => 'White-label and partner-friendly Drupal development, theming, migration, and support for agencies that need reliable delivery help.',
  'value' => '<p>Agencies often need dependable Drupal help without slowing down their own delivery pipeline. Drupify supports agencies with custom development, Twig theming, migrations, bug fixing, QA, and maintenance work that can fit into an existing client process.</p><p>The work is designed to be clear, collaborative, and easy to hand over, with attention to reusable Drupal structure and frontend quality.</p><ul><li>White-label Drupal development and frontend implementation</li><li>Migration and upgrade support for client projects</li><li>Views, paragraphs, theme, and content model cleanup</li><li>QA, support, and maintenance for ongoing retainers</li></ul>',
], 'public://2024-07/adv_web_development.jpg');
