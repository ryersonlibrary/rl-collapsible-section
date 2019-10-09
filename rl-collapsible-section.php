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

function rl_collapsible_section_register_scripts() {
  wp_register_style( 'rl-collapsible-section-style', plugin_dir_url( __FILE__ ).'/css/rl-collapsible-section.css', array(), '0.0.7' );
  wp_register_script( 'rl-collapsible-section-js', plugin_dir_url( __FILE__ ).'/js/rl-collapsible-section.js', array('jquery'), '0.0.7', true );
}
add_action( 'wp_enqueue_scripts', 'rl_collapsible_section_register_scripts' );

function rl_collapsible_section_shortcode($attrs = [], $content = null, $tag = '') {
  wp_enqueue_script( 'rl-collapsible-section-js' );
  wp_enqueue_style( 'rl-collapsible-section-style' );

  // normalize attribute keys, lowercase
  $attrs = array_change_key_case((array)$attrs, CASE_LOWER);
 
  // override default attributes with user attributes
  $shortcode_attrs = shortcode_atts([
    'title' => 'Collapsible section',
    'title-tag' => 'h1',
    'collapsed' => 'yes'
  ], $attrs, $tag);

  $title = $shortcode_attrs['title'];
  $title_tag = $shortcode_attrs['title-tag'];
  $collapsed = $shortcode_attrs['collapsed'] == 'yes';
  
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

function rl_collapsible_section_toggle_button_shortcode() {
  $output = '<div style="text-align: right"><button class="rl-collapsible-section-toggle-button">Expand / Collapse All</button></div>';
  return $output;
}
add_shortcode('rl_collapsible_section_toggle_button', 'rl_collapsible_section_toggle_button_shortcode');

function rl_collapsible_section_prepend_toggle_button($content) {
  if ( has_shortcode($content, 'rl_collapsible_section') && !has_shortcode($content, 'rl_collapsible_section_toggle_button') ) {
    $before = stristr($content, '[rl_collapsible_section', true);    
    $after = stristr($content, '[rl_collapsible_section');   
    return $before . '[rl_collapsible_section_toggle_button]' . $after;
  }
  return $content;
}
add_filter( 'the_content', 'rl_collapsible_section_prepend_toggle_button' );
