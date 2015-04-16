<?php get_header(); ?>
<?php now_in(__FILE__) ?>

<?php echo home_url(); ?>


<?php if(have_posts()) : ?>
    <?php
        // initialisation de l'array qui contient les coordonnées de tous les lieux affichés
        $locations = [];
        while(have_posts()) : $location=[];?>
        <?php the_post(); ?>
        <h3><?php the_title(); ?></h3>
        <p><?php the_content(); ?></p>
        <p>Durée de la visite&nbsp;: <?php the_field('duree_de_la_visite'); ?></p>
        <?php
            // stockage titre du lieu
            $location[] = get_the_title();
            // stockage contenu de l'article
            $location[] = get_the_content();
            // stockage infos gmaps
            $location[] = get_field('google_map');
            // vérifie si le lieu a les infos de position
            if( !empty($location) ):
                // pousse les infos dans l'array à chaque lieu
                $locations[] = $location;
            endif;
        ?>
    <?php endwhile; ?>
<?php endif; ?>
    

<!--  AFFICHAGE GOOGLE MAPS
==================================================================-->
<?php
    // affiche l'array
    a($locations);
?>

<?php
    if( !empty($locations) ):
?>
    <div class="acf-map">
    <!-- Pour chaque élément, crée un marker -->
    <?php foreach ($locations as $location): ?>
        <?php
            $locationTitle = $location[0];
            $locationContent = $location[1];
            $locationAddress = $location[2];
        ?>
        <div class="marker" data-lat="<?php echo $locationAddress['lat']; ?>" data-lng="<?php echo $locationAddress['lng']; ?>">
            <h3><?php echo $locationTitle; ?></h3>
            <div><?php echo $locationContent; ?></div>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>




<?php get_sidebar(); ?>
<?php get_footer(); ?>