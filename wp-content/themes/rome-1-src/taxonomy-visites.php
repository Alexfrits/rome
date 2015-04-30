<?php get_header(); ?>
<?php // now_in(__FILE__) ?>

<?php echo home_url(); ?>
<main>


<?php if(have_posts()) : ?>
    <?php
        // initialisation de l'array qui contient les coordonnées de tous les lieux affichés
        $locations = []; ?>

    <?php while(have_posts()) :
        $location=[];
        the_post();
        if(get_field('duree_de_la_visite')) {
            $location['duree'] = get_field('duree_de_la_visite');
        };
        // stockage titre du lieu
        $location['title'] = get_the_title();
        // stockage contenu de l'article
        $location['content'] = get_the_content();
        // stockage infos gmaps
        $location['gmap'] = get_field('google_map');
        // vérifie si le lieu a les infos de position
        if( !empty($location) ){
            // pousse les infos dans l'array à chaque lieu
            $locations[] = $location;
        };
    endwhile; ?>

    <?php if(isset($location['duree'])): ?>
        <p>Durée de la visite: <?php echo $location['duree']; ?></p>

    <?php endif; ?>
        <a class="btn__reserver" href="<?php echo get_permalink(get_page_by_path('tarifs-et-reservations')) ?>">Réserver cette visite</a>
<?php endif; ?>


<!--  AFFICHAGE GOOGLE MAPS
==================================================================-->
<?php
    // affiche l'array
    // a($locations);
?>

<?php
    if( !empty($locations) ):
?>
    <div class="acf-map">
    <!-- Pour chaque élément, crée un marker -->
    <?php foreach ($locations as $location): ?>
        <?php
            // vérifie si le lieux a des infos de coordonnées
            if(count($location['gmap']) === 3): ?>
                <!-- si oui, crée un marker -->
                <div class="marker"
                data-lat="<?php echo $location['gmap']['lat']; ?>"
                data-lng="<?php echo $location['gmap']['lng']; ?>"
                >
                    <h3><?php echo $location['title']; ?></h3>
                    <div><?php echo $location['content']; ?></div>
                </div>
            <?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
</main>




<?php get_sidebar(); ?>
<?php get_footer(); ?>