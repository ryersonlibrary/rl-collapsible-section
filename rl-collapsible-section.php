<?php
defined( 'ABSPATH' ) OR exit;
/*
 * Plugin Name: Collapsible Section Shortcode
 * Plugin URI: https://github.com/ryersonlibrary/rl-collapsible-section
 * Author: Ryerson University Library
 * Author URI: https://github.com/ryersonlibrary
 * Description: Adds the [rl_collapsible_section] shortcode to WordPress.
 * Version: 0.0.1
 */

function rl_collapsible_section_shortcode($attrs = [], $content = null, $tag = '')
{
  // normalize attribute keys, lowercase
  $attrs = array_change_key_case((array)$attrs, CASE_LOWER);
 
  // override default attributes with user attributes
  $shortcode_attrs = shortcode_atts([
    'title' => 'Collapsible section',
    'title-tag' => 'h1'
  ], $attrs, $tag);

  $title = $shortcode_attrs['title'];
  $title_tag = $shortcode_attrs['title-tag'];

  $output = "<div class=\"rl-collapsible-section\">";
  $output .= "<{$title_tag} class=\"rl-collapsible-section-title\">$title</{$title_tag}>";
  $output .= "<div class=\"rl-collapsible-section-content\">{$content}</div>";
  $output .= "</div>";

  return do_shortcode($output);
}
add_shortcode('rl_collapsible_section', 'rl_collapsible_section_shortcode');