<?php 
// include_once('dev-helpers.php');
    //     // récupération du slug de visite à partir de l'URL (utile dan sle cas où on vient d'une visite)
    // $visit_referer = explode('/', $_SERVER['REQUEST_URI']);
    // $visit_referer = (is_array($visit_referer) ? $visit_referer[count($visit_referer) - 2] : '');

    // global $wp_query;
    // // a($wp_query);
    // $cat = get_queried_object();
    header('location:'.home_url().'/infospratiques/?infocat=dormir');
?>