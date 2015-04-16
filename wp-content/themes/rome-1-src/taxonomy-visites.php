<?php get_header(); ?>
<?php now_in(__FILE__) ?>

<?php echo home_url(); ?>

<?php if(have_posts()) : ?>
    <?php while(have_posts()) : ?>
        <?php the_post(); ?>
        <h3><?php the_title(); ?></h3>
        <p><?php the_content(); ?></p>
        <p>Durée de la visite&nbsp;: <?php the_field('duree_de_la_visite'); ?></p>


        <!--  AFFICHAGE GOOGLE MAPS
        ==================================================================-->
        
        <?php
        // récup le champ custom gmaps
        $location = get_field('google_map');
        a($location);
        // fonction d'affichage de la carte par ACF
        if( !empty($location) ): ?>
            <div class="acf-map">
                <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>


<?php get_sidebar(); ?>
<?php get_footer(); ?>