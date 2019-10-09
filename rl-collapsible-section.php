<?php
defined( 'ABSPATH' ) OR exit;
/*
 * Plugin Name: Collapsible Section Shortcode
 * Plugin URI: https://github.com/ryersonlibrary/rl-collapsible-section
 * Author: Ryerson University Library
 * Author URI: https://github.com/ryersonlibrary
 * Description: Adds the [rl_collapsible_section] shortcode to WordPress.
 * GitHub Plugin URI: https://github.com/ryersonlibrary/rl-collapsible-section
 * Version: 0.1.3
 */

// Register styles and scripts to be used later when needed
function rl_collapsible_section_register_scripts() {
  wp_register_style( 'rl-collapsible-section-style', plugin_dir_url( __FILE__ ).'/css/rl-collapsible-section.css', array(), '0.0.7' );
  wp_register_script( 'rl-collapsible-section-js', plugin_dir_url( __FILE__ ).'/js/rl-collapsible-section.js', array('jquery'), '0.0.7', true );
}
add_action( 'wp_enqueue_scripts', 'rl_collapsible_section_register_scripts' );

// [rl_collapsible_section] shortcode
function rl_collapsible_section_shortcode($atts = [], $content = null) {
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);
 
  // override default attributes with user attributes
  $shortcode_atts = shortcode_atts([
    'title' => 'Collapsible section',
    'title-tag' => 'h1',
    'collapsed' => 'yes'
  ], $atts, 'rl_collapsible_section');

  $title = $shortcode_atts['title'];
  $title_tag = $shortcode_atts['title-tag'];
  $collapsed = $shortcode_atts['collapsed'] == 'yes';
  
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
    if ( !has_shortcode($content, 'rl_collapsible_section_toggle_button') ) {
      $before = stristr($content, '[rl_collapsible_section', true);    
      $after = stristr($content, '[rl_collapsible_section');   
      $content = $before . '[rl_collapsible_section_toggle_button]' . $after;
    }

  }
  return $content;
}
add_filter( 'the_content', 'rl_collapsible_section_the_content_filter' );
