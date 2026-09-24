<?php

use Drupal\captcha\Entity\CaptchaPoint;
use Drupal\webform\Entity\Webform;

$form_id = 'webform_submission_contact_node_97_add_form';
$captcha_type = 'recaptcha_v3/drupify';
$storage = \Drupal::entityTypeManager()->getStorage('captcha_point');
$existing = $storage->load($form_id);

if ($existing instanceof CaptchaPoint) {
  $existing->set('captchaType', $captcha_type);
  $existing->enable();
  $existing->save();
  print "Updated CAPTCHA point {$form_id} to {$captcha_type}.\n";
}
else {
  CaptchaPoint::create([
    'id' => $form_id,
    'label' => $form_id,
    'formId' => $form_id,
    'captchaType' => $captcha_type,
    'status' => TRUE,
  ])->save();
  print "Created CAPTCHA point {$form_id} with {$captcha_type}.\n";
}

$action = \Drupal::entityTypeManager()->getStorage('recaptcha_v3_action')->load('drupify');
if ($action) {
  $action->set('challenge', 'captcha/Math');
  $action->save();
  print "Kept reCAPTCHA v3 fallback challenge as captcha/Math for failed score only.\n";
}

$webform = Webform::load('contact');
if ($webform) {
  $elements = $webform->getElementsDecoded();

  if (isset($elements['captcha']) && (($elements['captcha']['#type'] ?? NULL) === 'captcha')) {
    $previous = $elements['captcha']['#captcha_type'] ?? 'default';
    unset($elements['captcha']);
    $webform->setElements($elements);
    $webform->save();
    print "Removed contact webform CAPTCHA element that was using {$previous}; CAPTCHA point now controls the form.\n";
  }
  else {
    print "Contact webform does not contain a top-level CAPTCHA element named captcha.\n";
  }
}
else {
  print "Contact webform was not found.\n";
}
