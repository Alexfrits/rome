<?php

/*
*   TDM
* ------------------------------
*   1. Langues
*   2. Scripts
*   3. Styles
*/


/*  LANGUES
===================================================================*/

function rome_setup() {
  load_theme_textdomain('rome-1', get_template_directory().'/languages');
}
add_action('after_setup_theme', 'rome_setup');


/*  2. SCRIPTS
===================================================================*/

function rome_scripts() {
  wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
  wp_enqueue_script('main-script', get_template_directory_uri().'/js/main.min.js', array('google-maps'));
  // wp_enqueue_script('google-jsapi','https://www.google.com/jsapi');
}
add_action('wp_enqueue_scripts', 'rome_scripts');
// add_action('admin_enqueue_scripts', 'rome_scripts');


/*  3. STYLES
===================================================================*/

function rome_styles() {
  wp_enqueue_style('main', get_template_directory_uri().'/style.css');
}

add_action( 'wp_enqueue_scripts', 'rome_styles');

?>