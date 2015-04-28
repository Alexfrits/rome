<?php

/*
*   TDM
* ------------------------------
*   1. Langues
*   2. Scripts
*   3. Styles
*   4. Shortcode guides pictures list
*   5. HTML Header
*   6. Users
*   7. add query var
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


/*  5. HTML Header
===================================================================*/

// nice <title>
function rome_site_title($title) {

  if(is_home())
    return get_bloginfo('name');

  elseif(is_archive() == true) {
    $categories = get_the_category();
    return $title. ' - '.get_bloginfo('name');
  }

  else
    return get_the_title(get_the_id()).' - '.get_bloginfo('name');
}
add_filter('wp_title','rome_site_title');


/*  6. Users
===================================================================*/

// le chef \o/

function rome_admin_create() {
    remove_role('manager');
    add_role ('manager','Gestionnaire');
}
add_action( 'admin_init', 'rome_admin_create');

function rome_admin_caps() {

    // base
    $role = get_role('manager');

    $role->add_cap('read');
    $role->add_cap('edit_posts');
    // $role->add_cap('delete_posts');
    $role->add_cap( 'edit_others_pages' );
    $role->add_cap( 'edit_others_posts' );
    $role->add_cap( 'edit_pages' );
    $role->add_cap( 'edit_private_pages' );
    // $role->add_cap( 'edit_private_posts' );
    $role->add_cap( 'edit_published_pages' );
    // $role->add_cap( 'edit_published_posts' );
    $role->add_cap( 'upload_files' );

    // users
    $role->add_cap('list_users');
    $role->add_cap('create_users');
    $role->add_cap('add_users');
    $role->add_cap('edit_users');
    $role->add_cap('delete_users');
    $role->add_cap('remove_users');

    // categories/taxonomies
    $role->add_cap('manage_categories');


    // home
/*    $role->add_cap('edit_home',true);
    $role->add_cap('edit_homes',true);
    $role->add_cap('delete_home',true);
    $role->add_cap('delete_homes',true);
    $role->add_cap('edit_other_home',true);
    $role->add_cap('edit_other_homes',true);
    $role->add_cap('edit_published_home',true);
    $role->add_cap('edit_published_homes',true);

    // guides
    $role->add_cap('publish_guides',true);
    $role->add_cap('publish_guidess',true);
    $role->add_cap('edit_guides',true);
    $role->add_cap('edit_guidess',true);
    $role->add_cap('delete_guides',true);
    $role->add_cap('delete_guidess',true);
    $role->add_cap('edit_other_guides',true);
    $role->add_cap('edit_other_guidess',true);
    $role->add_cap('edit_published_guides',true);
    $role->add_cap('edit_published_guidess',true);

    // activités
    $role->add_cap('publish_activite',true);
    $role->add_cap('publish_activites',true);
    $role->add_cap('edit_activite',true);
    $role->add_cap('edit_activites',true);
    $role->add_cap('delete_activite',true);
    $role->add_cap('delete_activites',true);
    $role->add_cap('edit_other_activite',true);
    $role->add_cap('edit_other_activites',true);
    $role->add_cap('edit_published_activite',true);
    $role->add_cap('edit_published_activites',true);

    // lieux
    $role->add_cap('publish_lieu',true);
    $role->add_cap('publish_lieus',true);
    $role->add_cap('edit_lieu',true);
    $role->add_cap('edit_lieus',true);
    $role->add_cap('delete_lieu',true);
    $role->add_cap('delete_lieus',true);
    $role->add_cap('edit_other_lieu',true);
    $role->add_cap('edit_other_lieus',true);
    $role->add_cap('edit_published_lieu',true);
    $role->add_cap('edit_published_lieus',true);*/
}
add_action('admin_init','rome_admin_caps');


// les employés \o/

function rome_writers_create() {
    remove_role('writers');
    add_role ('writers','Writers');
}
add_action( 'admin_init', 'rome_writers_create');

function rome_writers_caps() {

    // base
    $role = get_role('writers');
    $role->add_cap('read');
    $role->add_cap('edit_posts');
    $role->add_cap( 'edit_others_pages' );
    $role->add_cap( 'edit_others_posts' );
    $role->add_cap( 'edit_pages' );
    $role->add_cap( 'edit_private_pages' );
    $role->add_cap( 'edit_published_pages' );
    $role->add_cap( 'upload_files' );

    // below: doesn't work :(

    // home
/*    $role->add_cap('edit_home',true);
    $role->add_cap('edit_homes',true);
    $role->add_cap('delete_home',true);
    $role->add_cap('delete_homes',true);
    $role->add_cap('edit_other_home',true);
    $role->add_cap('edit_other_homes',true);
    $role->add_cap('edit_published_home',true);
    $role->add_cap('edit_published_homes',true);

    // guides
    $role->add_cap('publish_guides',true);
    $role->add_cap('publish_guidess',true);
    $role->add_cap('edit_guides',true);
    $role->add_cap('edit_guidess',true);
    $role->add_cap('delete_guides',true);
    $role->add_cap('delete_guidess',true);
    $role->add_cap('edit_other_guides',true);
    $role->add_cap('edit_other_guidess',true);
    $role->add_cap('edit_published_guides',true);
    $role->add_cap('edit_published_guidess',true);

    // activités
    $role->add_cap('publish_activite',true);
    $role->add_cap('publish_activites',true);
    $role->add_cap('edit_activite',true);
    $role->add_cap('edit_activites',true);
    $role->add_cap('delete_activite',true);
    $role->add_cap('delete_activites',true);
    $role->add_cap('edit_other_activite',true);
    $role->add_cap('edit_other_activites',true);
    $role->add_cap('edit_published_activite',true);
    $role->add_cap('edit_published_activites',true);

    // lieux
    $role->add_cap('publish_lieu',true);
    $role->add_cap('publish_lieus',true);
    $role->add_cap('edit_lieu',true);
    $role->add_cap('edit_lieus',true);
    $role->add_cap('delete_lieu',true);
    $role->add_cap('delete_lieus',true);
    $role->add_cap('edit_other_lieu',true);
    $role->add_cap('edit_other_lieus',true);
    $role->add_cap('edit_published_lieu',true);
    $role->add_cap('edit_published_lieus',true);*/
}
add_action('admin_init','rome_writers_caps');

/*  7. Add query var (passing parameter to the URL)
===================================================================*/

// function add_query_vars($aVars) {
// $aVars[] = "infocat"; // represents the name of the product category as shown in the URL
// return $aVars;
// }

// // hook add_query_vars function into query_vars
// add_filter('query_vars', 'add_query_vars');

?>