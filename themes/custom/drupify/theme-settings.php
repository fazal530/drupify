<?php


use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Implements hook_form_system_theme_settings_alter() for settings form.
 *
 * Replace Barrio setting options with subtheme ones.
 *
 * Example on how to alter theme settings form
 */

function drupify_form_system_theme_settings_alter(&$form, FormStateInterface $form_state) {
  $form['components']['navbar']['bootstrap_barrio_navbar_top_background']['#options'] = [
    'bg-primary' => t('Primary'),
    'bg-secondary' => t('Secondary'),
    'bg-light' => t('Light'),
    'bg-dark' => t('Dark'),
    'bg-white' => t('White'),
    'bg-transparent' => t('Transparent'),
  ];
  $form['components']['navbar']['bootstrap_barrio_navbar_background']['#options'] = [
    'bg-primary' => t('Primary'),
    'bg-secondary' => t('Secondary'),
    'bg-light' => t('Light'),
    'bg-dark' => t('Dark'),
    'bg-white' => t('White'),
    'bg-transparent' => t('Transparent'),
  ];

  /*Core theme  settings*/
    // Color Pannel
  $form['color_options'] = array(
    '#type' => 'details',
    '#title' => t('Color Options'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#group' => 'visibility',
    '#weight' => -992,
  );
  $form['color_options']['default_color'] = [
    '#type' => 'checkbox',
    '#title' => t('Use the Default Color'),
    '#default_value' => theme_get_setting('default_color'),
    '#tree' => FALSE,
  ];
  $form['color_options']['color_settings'] = [
    '#type' => 'container',
    '#states' => [
      // Hide the color settings when using the default color.
      'invisible' => [
        'input[name="default_color"]' => ['checked' => TRUE],
      ],
    ],
  ];
  $form['color_options']['color_settings']['primary_color'] = [
    '#type' => 'color',
    '#title' => t('Select Primary Color'),
    '#default_value' => theme_get_setting('primary_color'),
  ];
  $form['color_options']['color_settings']['secondary_color'] = [
    '#type' => 'color',
    '#title' => t('Select Secondary Color'),
    '#default_value' => theme_get_setting('secondary_color'),
  ];
  /*Core theme  settings*/
  $form['logo']['#group'] = 'visibility';
  $form['logo']['#title'] = t('Logo Image');
  $form['logo']['#weight'] = -995;
  $form['favicon']['#group'] = 'visibility';
  $form['favicon']['#weight'] = -994;

  $form['logo']['#open'] = TRUE;
  $form['favicon']['#open'] = TRUE;
  unset($form['theme_settings']); 
  unset($form['bootstrap_barrio_source']); 

  $form['visibility'] = [
    '#type' => 'vertical_tabs',
    '#title' => t('Classic pro Settings'),
    '#weight' => -999,
  ];
  //general settings 
  $form['general'] = [
    '#type' => 'details',
    '#title' => t('General Options'),
    '#weight' => -999,
    '#group' => 'visibility',
    '#open' => FALSE,
  ];

   
  // Login page 
  $form['general']['login'] = array(
    '#type' => 'details',
    '#title' => t('Login Page Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['general']['login']['login_banner_title'] = array(  
    '#type'          => 'textfield',
    '#title'         => t('Banner Title'),
    '#default_value' => theme_get_setting('login_banner_title'),
    '#description'   => t("Please enter the banner title of Login Page."),
  );
  $form['general']['login']['login_title'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Page Title'),
    '#default_value' => theme_get_setting('login_title'),
    '#description'   => t("Please enter the title for Login page."),
  );
  $form['general']['login']['login_name_description'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Name field description'),
    '#default_value' => theme_get_setting('login_name_description'),
    '#description'   => t("Please enter the description for name field in Login page."),
  );
  $form['general']['login']['login_password_description'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Password field description'),
    '#default_value' => theme_get_setting('login_password_description'),
    '#description'   => t("Please enter the description for password field in Login page."),
  );
   // Register page 
   $form['general']['register'] = array(
    '#type' => 'details',
    '#title' => t('Register Page Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['general']['register']['register_banner_title'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Banner Title'),
    '#default_value' => theme_get_setting('register_banner_title'),
    '#description'   => t("Please enter the title of Register page banner."),
  );
  $form['general']['register']['register_title'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Title'),
    '#default_value' => theme_get_setting('register_title'),
    '#description'   => t("Please enter the title of the Register page."),
  );
  $form['general']['register']['register_mail_description'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Email field description'),
    '#default_value' => theme_get_setting('register_mail_description'),
    '#description'   => t("Please enter the description for mail field in Register page."),
  );
  $form['general']['register']['register_name_description'] = array(
    '#type'          => 'textfield',
    '#title'         => t('User Name field description'),
    '#default_value' => theme_get_setting('register_name_description'),
    '#description'   => t("Please enter the description for name field in Register page."),
  );
  // Reset password page 
  $form['general']['reset_pass'] = array(
    '#type' => 'details',
    '#title' => t('Reset Password Page Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['general']['reset_pass']['reset_banner_title'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Banner Title'),
    '#default_value' => theme_get_setting('reset_banner_title'),
    '#description'   => t("Please enter the Reset Password Page banner title."),
  );
  $form['general']['reset_pass']['reset_page_title'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Page Title'),
    '#default_value' => theme_get_setting('reset_page_title'),
    '#description'   => t("Please enter the title of the Reset Password page."),
  );
  $form['general']['reset_pass']['reset_pass_name_description'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Mail field description'),
    '#default_value' => theme_get_setting('reset_pass_name_description'),
    '#description'   => t("Please enter the description for mail field in Reset Password page."),
  );
  // Maintenance and coming soon Section Start
  $form['general']['maintenance_coming_soon']['maintenance_mode'] = array(
    '#type' => 'details',
    '#title' => t('Maintenance'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['general']['maintenance_coming_soon']['launch_date'] = [
    '#type' => 'date',
    '#title' => t('Set Date'),
    '#description' => t('Please enter the date of site coming to alive, This date will be displayed in Coming soon page.'),
    '#default_value' => theme_get_setting('launch_date'),
  ];
  $form['general']['maintenance_coming_soon']['mode_type'] = array(
    '#type'        => 'select',
    '#title'       => t('Mode Type'),
    '#options'     => ['1' => t('Maintenance Mode'),'2' => t('Coming Soon')],
    '#default_value' => theme_get_setting('mode_type'),
    '#description'   => t("Please select any one mode to change the content of Maintenance page. If Coming soon mode selected, while site under Maintenance, Coming Soon page content will be displayed"),
  );
  // Maintenace mode
  $form['general']['maintenance_coming_soon']['maintenance_mode']['maintenance_mode_title'] = array(
    '#type'          => 'text_format',
    '#title'         => t('Title'),
    '#default_value' => theme_get_setting('maintenance_mode_title')['value'],
    '#description'   => t("Please enter the title of Maintenance Page."),
  );
  $form['general']['maintenance_coming_soon']['maintenance_mode']['maintenance_mode_description'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Description'),
    '#default_value' => theme_get_setting('maintenance_mode_description'),
    '#description'   => t("Please enter the description of Maintenance Page."),
  );
  $form['general']['maintenance_coming_soon']['maintenance_mode']['bg_image_m'] = [
    '#type' => 'managed_file',
    '#title'    => t('Background Image'),
    '#default_value' => theme_get_setting('bg_image_m'),
    '#upload_location' => 'public://',
    '#description' => t('Choose background image for maintenance page.'),
  ];
  // Comming soon
  $form['general']['maintenance_coming_soon']['coming_soon'] = array(
    '#type' => 'details',
    '#title' => t('Coming Soon'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['general']['maintenance_coming_soon']['coming_soon']['coming_soon_title'] = array(
    '#type'          => 'text_format',
    '#title'         => t('Title'),
    '#default_value' => theme_get_setting('coming_soon_title')['value'],
    '#description'   => t("Please enter the title of Coming soon Page."),
  );
  $form['general']['maintenance_coming_soon']['coming_soon']['coming_soon_description'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Description'),
    '#default_value' => theme_get_setting('coming_soon_description'),
    '#description'   => t("Please enter the description of Coming soon Page."),
  );
  $form['general']['maintenance_coming_soon']['coming_soon']['bg_image_c'] = [
    '#type' => 'managed_file',
    '#title'    => t('Background Image'),
    '#default_value' => theme_get_setting('bg_image_c'),
    '#upload_location' => 'public://',
    '#description' => t('Choose background image for Coming Soon page.'),
  ];
  //Search
  $form['general']['search_result'] = array(
    '#type' => 'details',
    '#title' => t('Search Result Page'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );  
   $form['general']['search_result']['search_banner_title'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Banner Title'),
    '#default_value' => theme_get_setting('search_banner_title'),
    '#description'   => t("Please enter title for search result page banner."),
  ); 
  

  // Header Style
  $form['header'] = [
    '#type' => 'details',
    '#title' => t('Header Options'),
    '#weight' => -998,
    '#group' => 'visibility',
    '#open' => FALSE,
  ];
  $form['header']['header_variation'] = [
    '#title' => 'Header', 
    '#type' => 'select',
    '#options' => array(
      'header-1' => 'Header Style 1',
      'header-2' => 'Header Style 2',
      'header-3' => 'Header Style 3',
    ),
    '#default_value' => theme_get_setting('header_variation'),
    '#description' => t('Please choose your prefered header style'),
  ];
  $form['header']['sticky'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Sticky menu'),
    '#default_value' => theme_get_setting('sticky'),
  ); 
  
 //hamburger
 $form['general']['hamburger'] = [
  '#type'         => 'details',
  '#title'        => t('hamburger Details'),
  '#open'         => FALSE,
  ];
  $form['general']['hamburger']['about_title'] = [
    '#type'          => 'textfield',
    '#title'         => t('About Title'),
    '#default_value' => theme_get_setting('about_title'),
  ];
  $form['general']['hamburger']['about_site'] = [
    '#type'          => 'textarea',
    '#title'         => t('About You'),
    '#default_value' => theme_get_setting('about_site'),
    '#description'   => t("Short Description about the Site."),
  ];
  $form['general']['hamburger']['hamburger_icon'] = [
    '#title' => t('Hamburger Icon'),
    '#description' => t('Use: png|jpg|jpeg'),
    '#type' => 'managed_file',
    '#upload_location' => 'public://',
    '#upload_validators' => [
    'file_validate_extensions' => ['png jpg jpeg'],
    ],
    '#default_value' => theme_get_setting('hamburger_icon'),
  ];
  $form['general']['hamburger']['contact_title'] = [
    '#type'          => 'textfield',
    '#title'         => t('Contact Title'),
    '#default_value' => theme_get_setting('contact_title'),
  ];
  $form['general']['hamburger']['contact_detail'] = [
    '#type'          => 'text_format',
    '#title'         => t('Contact Detail'),
    '#default_value' => theme_get_setting('contact_detail')['value'],
    '#description'   => t("Eg: Mail Id"),
  ]; 
  //Footer
  $form['general']['footer'] = array(
    '#type' => 'details',
    '#title' => t('Footer Requirements'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  //Footer - Bg
  $form['general']['footer']['footer_bg_image'] = [
    '#title' => t('Footer Background Image'),
    '#description' => t('Use: png|jpg|jpeg'),
    '#type' => 'managed_file',
    '#upload_location' => 'public://',
    '#upload_validators' => [
    'file_validate_extensions' => ['png jpg jpeg'],
    ],
    '#default_value' => theme_get_setting('footer_bg_image'),
  ];
  //CopyRight
    $form['general']['footer']['copyright_detail'] = [
      '#type'          => 'text_format',
      '#title'         => t('Copyright'),
      '#default_value' => theme_get_setting('copyright_detail')['value'],
      '#description'   => t("Copyright text that appears in the footer"),
    ]; 
  //Common Inputs
  //Footer
  $form['general']['common'] = array(
    '#type' => 'details',
    '#title' => t('General Requirements'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  //Page Banner
  $form['general']['common']['banner_image'] = [
    '#title' => t('Page Banner'),
    '#description' => t('Use: png|jpg|jpeg'),
    '#type' => 'managed_file',
    '#upload_location' => 'public://',
    '#upload_validators' => [
    'file_validate_extensions' => ['png jpg jpeg'],
    ],
    '#default_value' => theme_get_setting('banner_image'),
  ];
  // Company Logo

  $form['general']['common']['company_logo'] = [
    '#title' => t('Company Logo'),
    '#description' => t('Use: png|jpg|jpeg'),
    '#type' => 'managed_file',
    '#upload_location' => 'public://',
    '#upload_validators' => [
    'file_validate_extensions' => ['png jpg jpeg'],
    ],
    '#default_value' => theme_get_setting('company_logo'),
    '#description' => t('This Logo will be displayed in Pages such as Login page, Reset Page, Register Page'),
  ];
  // custom css Section Start
  $form['custom_css'] = array(
    '#type' => 'details',
    '#title' => t('Custom CSS'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#group' => 'visibility',
    '#open' => FALSE,
    '#weight' => -993,
  );
  $form['custom_css']['styles'] = array(
    '#type'          => 'textarea',
    '#title'         => t('Custom Style'),
    '#default_value' => theme_get_setting('styles'),
    '#description'   => t("Place your custom style for your site."),
  );
  $form['#submit'][] = 'drupify_form_submit';
}
  function drupify_form_submit(&$form, $form_state) {
    if ($file_id = $form_state->getValue(['footer_bg_image', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }

    if ($file_id = $form_state->getValue(['site_logo', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }

    if ($file_id = $form_state->getValue(['hamburger_icon', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }

    if ($file_id = $form_state->getValue(['banner_image', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }

    if ($file_id = $form_state->getValue(['company_logo', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }

    if ($file_id = $form_state->getValue(['bg_image_m', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }

    if ($file_id = $form_state->getValue(['bg_image_c', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }
    
  }

  

 