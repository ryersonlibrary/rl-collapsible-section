<?php
/**
 * Add a custom options page.
 */
function rl_collapsible_section_add_options_page() {
  add_options_page(
    __( 'Collapsible Section Shortcode Settings', 'rl_collapsible_section' ),
    __( 'Collapsible Section Shortcode Settings', 'rl_collapsible_section' ),
    'manage_options',
    'rl_collapsible_section',
    'rl_collapsible_section_render_options_page_callback'
  );
}
add_action( 'admin_menu', 'rl_collapsible_section_add_options_page' );

/**
 * Callback function to render custom options page.
 */
function rl_collapsible_section_render_options_page_callback() {
  ?>
    <form method="POST" action="options.php">
      <?php 
      settings_fields( 'rl_collapsible_section' );
      do_settings_sections( 'rl_collapsible_section' );
      submit_button(); 
      ?>
    </form>
  <?php
}

/**
 * Register and initialize settings for the plugin.
 */
function rl_collapsible_section_settings_init() {
  $settings_section = 'rl_collapsible_section-main';
  $settings_page = 'rl_collapsible_section';

  add_settings_section(
    $settings_section,
    'Settings for Collapsible Section Shortcode plugin',
    'rl_collapsible_section_settings_section_main_callback',
    $settings_page
  );

  add_settings_field(
    'rl_collapsible_section-default_title_tag',
    'Default Title Tag',
    'rl_collapsible_section_default_title_tag_callback',
    $settings_page,
    $settings_section,
    array( 'label_for' => 'rl_collapsible_section-default_title_tag' )
  );

  $default_title_tag_args = array(
    'type' => 'string',
    'default' => 'h2',
    'description' => 'Default tag used for the title of a collapsible section.'
  );
  register_setting($settings_page, 'rl_collapsible_section-default_title_tag', $default_title_tag_args);

  add_settings_field(
    'rl_collapsible_section-expand_first_collapsible',
    'Expand First Collapsible',
    'rl_collapsible_section_expand_first_collapsible_callback',
    $settings_page,
    $settings_section,
    array( 'label_for' => 'rl_collapsible_section-expand_first_collapsible' )
  );

  $expand_first_collapsible_args = array(
    'type' => 'boolean',
    'default' => true,
    'description' => 'Whether or not to expand the first collapsible section.'
  );
  register_setting($settings_page, 'rl_collapsible_section-expand_first_collapsible', $expand_first_collapsible_args);
}
add_action( 'admin_init', 'rl_collapsible_section_settings_init' );

/**
 * Callback function to render settings section html
 */
function rl_collapsible_section_settings_section_main_callback() {
  echo '';
}

/**
 * Callback functions to render settings
 */
function rl_collapsible_section_default_title_tag_callback( $args ) {
  $setting_id = 'rl_collapsible_section-default_title_tag';
  $setting_value = esc_attr( get_option( 'rl_collapsible_section-default_title_tag' ) );

  echo <<<setting_html
  <input type="text" id="{$setting_id}" name="{$setting_id}" value="{$setting_value}" />
  <p class="description">{$args[0]}</p>
setting_html;
}

function rl_collapsible_section_expand_first_collapsible_callback( $args ) {
  $setting_id = 'rl_collapsible_section-expand_first_collapsible';
  $setting_value = esc_attr( get_option( 'rl_collapsible_section-expand_first_collapsible' ) );

  if ($setting_value) {
    $checked = 'checked';
  } else {
    $checked = 'unchecked';
  }

  echo <<<setting_html
  <input type="checkbox" id="{$setting_id}" name="{$setting_id}" checked="{$checked}" />
  <p class="description">{$args[0]}</p>
setting_html;
}
