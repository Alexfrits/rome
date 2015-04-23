<?php get_header(); ?>

<?php now_in(__FILE__) ?>

<?php echo home_url(); ?>

<main>
<?php
    if(have_posts()):
        while(have_posts()): the_post();
            the_title();
        endwhile;
    endif;
?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>