<?php
defined( 'ABSPATH' ) OR exit;
/*
 * Plugin Name: Collapsible Section Shortcode
 * Plugin URI: https://github.com/ryersonlibrary/rl-collapsible-section
 * Author: Ryerson University Library
 * Author URI: https://github.com/ryersonlibrary
 * Description: Adds the [rl_collapsible_section] shortcode to WordPress.
 * GitHub Plugin URI: https://github.com/ryersonlibrary/rl-collapsible-section
 * Version: 0.1.8
 */

// Include our custom settings page for the plugin
require_once plugin_dir_path( __FILE__ ).'/inc/rl-collapsible-section-settings.php';

// Register styles and scripts to be used later when needed
function rl_collapsible_section_register_scripts() {
  wp_register_style( 'rl-collapsible-section-style', plugin_dir_url( __FILE__ ).'/css/rl-collapsible-section.css', array(), '0.0.7' );
  wp_register_script( 'rl-collapsible-section-js', plugin_dir_url( __FILE__ ).'/js/rl-collapsible-section.js', array('jquery'), '0.0.7', true );
}
add_action( 'wp_enqueue_scripts', 'rl_collapsible_section_register_scripts' );

// [rl_collapsible_section] shortcode
function rl_collapsible_section_shortcode($atts = [], $content = null) {

  // Flag for determining if this is the first instance of [rl_collapsible_section]
  static $rl_collapsible_section_first_instance = true;

  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);
 
  // override default attributes with user attributes
  $shortcode_atts = shortcode_atts([
    'title' => 'Collapsible section',
    'title-tag' => esc_attr( get_option( 'rl_collapsible_section-default_title_tag', 'h2' ) ),
    'collapsed' => 'yes'
  ], $atts, 'rl_collapsible_section');

  $title = $shortcode_atts['title'];
  $title_tag = $shortcode_atts['title-tag'];
  $collapsed = $shortcode_atts['collapsed'] == 'yes';

  // Forces the first collapsible section to be expanded
  $expand_first_collapsible = esc_attr( get_option( 'rl_collapsible_section-expand_first_collapsible', true ) );
  if ($rl_collapsible_section_first_instance && $expand_first_collapsible) {
    $collapsed = false;
  }
  
  $collapsible_section_classes = '';  
  $aria_expanded = 'true';
  if ($collapsed) {
    $aria_expanded = 'false';
    $collapsible_section_classes = 'rl-collapsed';
  }
  

  $output = "<div class=\"rl-collapsible-section {$collapsible_section_classes}\">";
  $output .= "<{$title_tag} class=\"rl-collapsible-section-title\"><button aria-expanded=\"{$aria_expanded}\">{$title}<span class=\"rl-collapsible-section-button-indicator\"></span></button></{$title_tag}>";
  $output .= "<div class=\"rl-collapsible-section-content\">{$content}</div>";
  $output .= "</div>";

  // Flag for determining if this is the first instance of [rl_collapsible_section]
  $rl_collapsible_section_first_instance = false;

  if ( defined( 'RL_IS_PB_EXPORT' ) ) {
    $output = "<{$title_tag}>{$title}</{$title_tag}>";
    $output .= "<p>{$content}</p>";
  }

  return do_shortcode($output);
}
add_shortcode('rl_collapsible_section', 'rl_collapsible_section_shortcode');

// [rl_collapsible_section_toggle_button] shortcode
function rl_collapsible_section_toggle_button_shortcode() {
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // override default attributes with user attributes
  $shortcode_atts = shortcode_atts([
    'align' => 'right',
    'button-text' => 'Expand / Collapse All'
  ], $atts, 'rl_collapsible_section_toggle_button');

  $align = $shortcode_atts['align'];
  $button_text = $shortcode_atts['button-text'];

  $output = "<div style=\"text-align: {$align}\">";
  $output .= "<button class=\"rl-collapsible-section-toggle-button\">{$button_text}</button>";
  $output .= '</div>';
  return $output;
}
add_shortcode('rl_collapsible_section_toggle_button', 'rl_collapsible_section_toggle_button_shortcode');

// Magic.
function rl_collapsible_section_the_content_filter($content) {
  if ( has_shortcode( $content, 'rl_collapsible_section') ) {
    // Enqueue styles and scripts only if the shortcode exists
    wp_enqueue_script( 'rl-collapsible-section-js' );
    wp_enqueue_style( 'rl-collapsible-section-style' );
    
    // Check if the toggle button is manually placed in the content,
    // if not prepend it to the first instance of [rl_collapsible_section]
    if ( !has_shortcode($content, 'rl_collapsible_section_toggle_button') && !defined( 'RL_IS_PB_EXPORT' ) ) {
      $before = stristr($content, '[rl_collapsible_section', true);    
      $after = stristr($content, '[rl_collapsible_section');   
      $content = $before . '[rl_collapsible_section_toggle_button]' . $after;
    }

  }
  return $content;
}
add_filter( 'the_content', 'rl_collapsible_section_the_content_filter' );

// Pressbooks export compatability
add_action( 'pb_pre_export', function() {
  define( 'RL_IS_PB_EXPORT', true );
});