<?php get_header(); ?>
<?php now_in(__FILE__) ?>

<main>

<h1>La ville à voir… et à revoir !</h1>

<!-- HOPHOPHOP début potentiel problème -->


<!-- article home principal -->
<?php
    $args = array(
        'post_type' => 'home',
        'tax_query' => array(
            array(
                'taxonomy' => 'articleshome',
                'field' => 'slug',
                'terms' => 'principal'
            ),
        ),
    );
    $main_home_article = new WP_Query($args);

    if($main_home_article->have_posts()) :
        while($main_home_article->have_posts()) : $main_home_article->the_post(); ?>
            <h2><?php the_title(); ?></h2>
            <?php the_content(); ?>
        <? endwhile;
    endif; wp_reset_postdata(); ?>



<!-- page Qui Sommes Nous (avec shortcode photos guides) -->
<?php
    $args = array(
        'pagename' => 'qui-sommes-nous'
    );
    $page_qsn_query = new WP_Query($args);

    if($page_qsn_query->have_posts()) :
        while($page_qsn_query->have_posts()) : $page_qsn_query->the_post(); ?>
            <h2><?php the_title(); ?></h2>
            <?php the_content(); ?>
        <? endwhile;
    endif; wp_reset_postdata(); ?>


<!-- article home secondaire -->
<?php
    $args = array(
        'post_type' => 'home',
        'tax_query' => array(
            array(
                'taxonomy' => 'articleshome',
                'field' => 'slug',
                'terms' => 'secondaire'
            ),
        ),
    );
    $main_home_article = new WP_Query( $args );

    if($main_home_article->have_posts()) :
        while($main_home_article->have_posts()) : $main_home_article->the_post(); ?>
            <h2><?php the_title(); ?></h2>
                <?php the_content(); ?>
        <? endwhile;
    endif; wp_reset_postdata(); ?>

<!-- HOPHOPHOP fin potentiel problème -->

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>