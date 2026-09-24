<?php

use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

$node = Node::load(1);
if (!$node) {
  throw new \RuntimeException('Homepage node 1 was not found.');
}

$paragraph_refs = [];

$make_paragraph = static function (string $type, array $values = [], array $children = []): Paragraph {
  $paragraph = Paragraph::create(['type' => $type]);

  foreach ($values as $field => $value) {
    if (!$paragraph->hasField($field)) {
      continue;
    }

    if (is_array($value)) {
      $paragraph->set($field, $value);
    }
    else {
      $paragraph->set($field, ['value' => $value]);
    }
  }

  if ($children && $paragraph->hasField('field_reference_sections')) {
    $paragraph->set('field_reference_sections', array_map(static function (Paragraph $child): array {
      return [
        'target_id' => $child->id(),
        'target_revision_id' => $child->getRevisionId(),
      ];
    }, $children));
  }

  $paragraph->save();
  return $paragraph;
};

$ref = static function (Paragraph $paragraph): array {
  return [
    'target_id' => $paragraph->id(),
    'target_revision_id' => $paragraph->getRevisionId(),
  ];
};

$process_items = [
  $make_paragraph('drupify_process_item', [
    'field_item_title' => 'Audit the current Drupal state',
    'field_item_description' => 'We review core, contrib modules, custom code, theme compatibility, content model, performance, and launch risks before development starts.',
  ]),
  $make_paragraph('drupify_process_item', [
    'field_item_title' => 'Plan a composer-safe path',
    'field_item_description' => 'Upgrade and migration work is mapped around dependencies, rollback points, database updates, and release confidence.',
  ]),
  $make_paragraph('drupify_process_item', [
    'field_item_title' => 'Build, test, and launch',
    'field_item_description' => 'Frontend, backend, QA, cache, forms, navigation, and production readiness are checked before handoff.',
  ]),
];

$why_items = [
  $make_paragraph('drupify_why_item', [
    'field_item_title' => 'Drupal-first delivery',
    'field_item_description' => 'The site is structured around Drupal content types, paragraphs, Views, blocks, and Twig templates instead of static page fragments.',
  ]),
  $make_paragraph('drupify_why_item', [
    'field_item_title' => 'Maintainable frontend systems',
    'field_item_description' => 'Reusable CSS classes, predictable templates, responsive layouts, and accessible interaction patterns keep the theme easier to evolve.',
  ]),
  $make_paragraph('drupify_why_item', [
    'field_item_title' => 'Upgrade-aware engineering',
    'field_item_description' => 'Drupal 11 readiness, module compatibility, security, cache behavior, and future release paths are considered from the start.',
  ]),
];

$testimonial_items = [
  $make_paragraph('drupify_testimonial_item', [
    'field_item_quote' => 'Drupify helped us move faster with a cleaner Drupal implementation and a stronger frontend foundation.',
    'field_item_author' => 'Client feedback',
  ]),
  $make_paragraph('drupify_testimonial_item', [
    'field_item_quote' => 'The team understood the Drupal structure, theme layer, and business goal behind the website.',
    'field_item_author' => 'Project stakeholder',
  ]),
  $make_paragraph('drupify_testimonial_item', [
    'field_item_quote' => 'A practical partner for Drupal maintenance, upgrades, and ongoing improvements.',
    'field_item_author' => 'Ongoing support client',
  ]),
];

$faq_items = [
  $make_paragraph('drupify_faq_item', [
    'field_item_question' => 'Can Drupify upgrade existing Drupal websites?',
    'field_item_answer' => 'Yes. Drupify handles Drupal upgrades, module compatibility checks, theme updates, database updates, and launch verification.',
  ]),
  $make_paragraph('drupify_faq_item', [
    'field_item_question' => 'Can the homepage content be edited from Drupal admin?',
    'field_item_answer' => 'Yes. The homepage is designed to use Drupal paragraphs for page sections and Views for dynamic services, work, clients, and insights.',
  ]),
  $make_paragraph('drupify_faq_item', [
    'field_item_question' => 'Do you build custom Drupal themes?',
    'field_item_answer' => 'Yes. Drupify works with Twig, Drupal libraries, responsive CSS, JavaScript behaviors, and component-style section templates.',
  ]),
];

$sections = [
  $make_paragraph('drupify_hero', [
    'field_eyebrow' => 'Drupal development, migration, upgrade & support',
    'field_title' => 'Expert Drupal engineering for websites that need to scale, perform, and stay secure.',
    'field_description' => 'Drupify helps businesses and agencies build, modernize, and maintain Drupal platforms from custom modules and themes to Drupal upgrades, migrations, performance optimization, and long-term support.',
    'field_link_title' => 'Book Free Consultation',
    'field_link_url' => '/contact-us',
    'field_secondary_title' => 'Request Free Drupal Audit',
    'field_secondary_url' => '/contact-us',
    'field_items' => "Drupal 11\nMigration\nCustom Development\nPerformance\nLong-term Support",
  ]),
  $make_paragraph('drupify_platforms', [
    'field_eyebrow' => 'Immediate proof',
    'field_title' => 'Platforms & Technologies',
    'field_items' => "Drupal\nAcquia\nPantheon\nGitHub\nPHP\nTwig\nComposer\nDrush",
  ]),
  $make_paragraph('drupify_clients', [
    'field_eyebrow' => 'Client proof',
    'field_title' => 'Published client records from Drupify.',
    'field_description' => 'Client logos are pulled from the Client content type through the Drupify Home View.',
  ]),
  $make_paragraph('drupify_services', [
    'field_eyebrow' => 'Drupal services',
    'field_title' => 'Focused Drupal services for serious websites.',
    'field_description' => 'Services are pulled dynamically from the Service content type so the homepage stays connected to the site content model.',
  ]),
  $make_paragraph('drupify_work', [
    'field_eyebrow' => 'Selected work',
    'field_title' => 'Drupal and web projects shipped with care.',
    'field_description' => 'Portfolio cards are loaded from the Portfolio content type and can be managed from Drupal admin.',
  ]),
  $make_paragraph('drupify_process', [
    'field_eyebrow' => 'Delivery process',
    'field_title' => 'A practical path from audit to launch.',
    'field_description' => 'Every engagement starts by understanding the Drupal structure already in place, then shaping the safest implementation path.',
  ], $process_items),
  $make_paragraph('drupify_why', [
    'field_eyebrow' => 'Why Drupify',
    'field_title' => 'Built for Drupal sites that need to keep moving.',
  ], $why_items),
  $make_paragraph('drupify_testimonials', [
    'field_eyebrow' => 'Trust',
    'field_title' => 'What clients value about working with Drupify.',
  ], $testimonial_items),
  $make_paragraph('drupify_insights', [
    'field_eyebrow' => 'Insights',
    'field_title' => 'Latest Drupal thinking from Drupify.',
    'field_description' => 'Insights are loaded dynamically from the Blog content type.',
  ]),
  $make_paragraph('drupify_faq', [
    'field_eyebrow' => 'FAQ',
    'field_title' => 'Common questions before starting.',
  ], $faq_items),
  $make_paragraph('drupify_audit_cta', [
    'field_eyebrow' => 'Free Drupal audit',
    'field_title' => 'Know what to fix before you invest in a rebuild.',
    'field_description' => 'Send us your Drupal website and we will review the visible frontend, content structure, and upgrade risks.',
    'field_items' => "Drupal version and module risks\nTheme and frontend issues\nContent model and admin usability\nPerformance and launch readiness",
    'field_link_title' => 'Request Free Drupal Audit',
    'field_link_url' => '/contact-us',
  ]),
  $make_paragraph('drupify_final_cta', [
    'field_title' => 'Ready to make your Drupal website easier to trust, manage, and grow?',
    'field_description' => 'Book a free consultation and let Drupify review the next best step for your site.',
    'field_link_title' => 'Book Free Consultation',
    'field_link_url' => '/contact-us',
  ]),
];

foreach ($sections as $section) {
  $paragraph_refs[] = $ref($section);
}

$node->set('field_sections', $paragraph_refs);
$node->setTitle('Expert Drupal Solutions');
$node->setPublished(TRUE);
$node->setNewRevision(TRUE);
$node->revision_log = 'Rebuilt homepage with clean Drupify paragraph structure.';
$node->save();

echo "Rebuilt homepage node 1 with " . count($paragraph_refs) . " Drupify sections.\n";
