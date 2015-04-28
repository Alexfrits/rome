<?php

/*
*   TDM
* ------------------------------
*   1. Langues
*   2. Scripts
*   3. Styles
*   4. Shortcode guides pictures list
*   5. add query var
*/


/*  LANGUES
===================================================================*/

function rome_setup() {
  load_theme_textdomain('rome-1', get_template_directory().'/languages');
  add_theme_support('post-thumbnails');
  // add_image_size($name, $width, $height, $crop);
  add_image_size('carre', 200, 200, true);
}
add_action('after_setup_theme', 'rome_setup');


/*  2. SCRIPTS
===================================================================*/

function rome_scripts() {
  wp_deregister_script('jquery');
  wp_register_script('main-script', get_template_directory_uri().'/js/main.js', array('google-maps'));
  wp_enqueue_script('main-script');
  wp_enqueue_script('google-maps', '//maps.googleapis.com/maps/api/js', array('jquery'), '3.19');
  wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', array(), '2.1.3', true);
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


// HTML5 search form (from WP engine). To made your own one, create searchform.php.
add_theme_support( 'html5', array( 'search-form' ) );


/*  4. SHORTCODE guides pictures list
===================================================================*/

function rome_picture_list() {
  $return_string = '';
  $pictures_guides = new WP_Query('page=&post_type=guides');

  // récupère les photos des guides et en fait une liste
  if($pictures_guides->have_posts()):
    $return_string = '<div class="picture-list__wrapper"><ul class="picture-list">';
    while($pictures_guides->have_posts()): $pictures_guides->the_post();
        $return_string .= '<li class="picture-list__item">';
        $return_string .= '<img  class="picture-list__item__img" src="' . get_field_object('photo')['value']['sizes']['carre'] . '" alt="la photo de '. get_the_title() . '">';
        $return_string .= '</li>';
    endwhile;
    $return_string .= '</ul></div>';
  endif;
  wp_reset_postdata();
  return $return_string;
}

function rome_register_shortcode() {
  add_shortcode('picturelist', 'rome_picture_list');
}

add_action('init', 'rome_register_shortcode');

/*  5. Add query var (passing parameter to the URL)
===================================================================*/

function add_query_vars($aVars) {
$aVars[] = "infocat"; // represents the name of the product category as shown in the URL
return $aVars;
}

// hook add_query_vars function into query_vars
add_filter('query_vars', 'add_query_vars');

