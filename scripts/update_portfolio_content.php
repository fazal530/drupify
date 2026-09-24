<?php

use Drupal\node\NodeInterface;

$updates = [
  142 => [
    'summary' => 'Claybrick is a public-facing organization website with structured information, project imagery, and a direct path to the live site.',
    'body' => 'Claybrick is presented as a published Drupify portfolio project with verified project imagery and a live website URL. The case study can be expanded later with Drupal version, scope, launch process, and measurable results.',
  ],
  141 => [
    'summary' => 'Saferbuildings is a responsive web project focused on presenting membership, resources, and organization information clearly across devices.',
    'body' => 'Saferbuildings is a responsive web project with existing screenshot evidence and a live website link. The portfolio record highlights the project identity, category, and public URL while leaving room for deeper technical outcomes.',
  ],
  140 => [
    'summary' => 'Comunepalazzoloso is a municipal-style web project built around clear information access, responsive layouts, and public-facing content.',
    'body' => 'Comunepalazzoloso is a published portfolio record with verified imagery and live website context. The project can be developed into a fuller case study with content architecture, frontend implementation, and support details.',
  ],
  139 => [
    'summary' => 'Digitalproductivity is a business website project with a polished responsive presentation and clear service-oriented content structure.',
    'body' => 'Digitalproductivity is listed as a live portfolio project with project imagery, categories, and website URL. The record is prepared for future case-study expansion with scope, implementation notes, and outcomes.',
  ],
  138 => [
    'summary' => 'Hilltromper is a content-driven web project focused on editorial presentation, responsive browsing, and accessible project navigation.',
    'body' => 'Hilltromper is a content-focused portfolio entry with existing screenshot evidence and a live website link. Further case-study details can document content model, design implementation, and editorial workflow.',
  ],
  137 => [
    'summary' => 'Casacourses is an education-focused web project with structured course information, responsive presentation, and clear user pathways.',
    'body' => 'Casacourses is presented as a live education-oriented portfolio project. The record includes project imagery and URL context and can later be expanded with platform, content, and conversion details.',
  ],
  135 => [
    'summary' => 'Ackerleylab is a research-oriented website project that presents lab information, people, and project content in a clean web structure.',
    'body' => 'Ackerleylab is a published portfolio project with verified imagery and a live URL. The project record can be extended with content architecture, frontend, and maintainability notes.',
  ],
  134 => [
    'summary' => 'Cykelcentralen is a commerce-style web project focused on product/service presentation, responsive browsing, and clear customer actions.',
    'body' => 'Cykelcentralen is a live portfolio record with project imagery and website link. The project can be expanded later with commerce flow, theme implementation, and support details.',
  ],
  21 => [
    'summary' => 'Simplyrenting is a property/rental web project with a clear public-facing interface and responsive project presentation.',
    'body' => 'Simplyrenting is a published Drupify portfolio project with verified screenshot imagery and live website context. Future case-study details can cover content structure, frontend implementation, and project outcomes.',
  ],
  20 => [
    'summary' => 'Foxbrospools is a service business website project designed to present offerings, trust signals, and contact pathways clearly.',
    'body' => 'Foxbrospools is a live service-business portfolio record with project imagery and URL context. The record can be expanded with scope, technical implementation, and results when verified.',
  ],
  19 => [
    'summary' => 'QYTERA is a professional services web project with structured service content, responsive layouts, and clear navigation.',
    'body' => 'QYTERA is a published portfolio project with a live URL and project image. Additional case-study notes can document platform details, content modeling, and frontend delivery.',
  ],
  18 => [
    'summary' => 'Wizardoorer is a product/service website project with brand-focused presentation, responsive pages, and direct live-site access.',
    'body' => 'Wizardoorer is represented with project imagery and a verified live website URL. The portfolio record is ready for deeper notes about design, build scope, and support.',
  ],
  17 => [
    'summary' => 'UBM is an organization website project with structured information, responsive presentation, and clear access to key content.',
    'body' => 'UBM is a published portfolio entry with screenshot evidence and live website context. The case study can later include Drupal structure, content workflow, and launch notes.',
  ],
  16 => [
    'summary' => 'MRK Contracting is a construction/service website project focused on credibility, service presentation, and conversion paths.',
    'body' => 'MRK Contracting is listed as a live portfolio project with project imagery and website URL. The record can be expanded with service structure, visual implementation, and support details.',
  ],
  15 => [
    'summary' => 'Cruschalva is a web project with responsive pages, clean content presentation, and a direct route to the live site.',
    'body' => 'Cruschalva is a published portfolio project with verified screenshot imagery and live website context. Future details can cover implementation scope, content model, and outcomes.',
  ],
  13 => [
    'summary' => 'Extermatrim is a service-business web project built around clear presentation, responsive layouts, and direct customer action.',
    'body' => 'Extermatrim is a live portfolio record with project imagery and website URL. The project can be expanded into a full case study with scope, technical details, and measurable results.',
  ],
];

$storage = \Drupal::entityTypeManager()->getStorage('node');
foreach ($updates as $nid => $data) {
  $node = $storage->load($nid);
  if (!$node instanceof NodeInterface || !$node->hasField('body')) {
    print "Skipped missing portfolio node {$nid}\n";
    continue;
  }

  $node->set('body', [
    'summary' => $data['summary'],
    'value' => '<p>' . htmlspecialchars($data['body'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>',
    'format' => 'basic_html',
  ]);
  $node->save();
  print "Updated portfolio content: {$nid} | " . $node->label() . "\n";
}
