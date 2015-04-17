<?php get_header(); ?>

<!-- /////////////////////////////////////////////////

    Ce fichier n'est pas utilisé en tant que tel par WP,
    il est inclu dans page-infospratiques.php, qui l'affiche

//////////////////////////////////////////////////////-->

<?php now_in(__FILE__) ?>

<?php echo home_url(); ?>


<!--  MODIFICATION de la boucle (car on est dans un autre fichier)
==================================================================-->
<!--
    cette boucle n'affiche rien,
    elle récup les infos et crée l'array
    pour l'affichage des markers de la map
-->

<?php

    $args = array('post_type' => 'activites' );

    // The Query
    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ): ?>
        <ul>
            <?php
            $locations = [];
            while($the_query->have_posts()): 
                $location = [];
                $the_query->the_post();
                $location[] = get_the_title();
                $location[] = get_field('google_map');
                if( !empty($location) ):
                    $locations[] = $location;
                endif;
            endwhile; ?>
        </ul>
        <?php a($locations); ?>
    <?php else: ?>
        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
    <?php endif;
    wp_reset_postdata();
?>


<!--  Contrôles customs et filtres pour la map
==================================================================-->
<!--
    GMCC = Google Maps Custom Controls
    cette classe est utilisée par le JS dans infospratiques.js
-->

<ul class="gmcc">
    <?php
    $args = [
        'taxonomy'      => 'infospratiques',
        'hide_empty'    => 1
    ];
    $visites = get_categories($args); ?>

    <?php foreach ($visites as $i => $v): ?>
        <li><a class="gmcc__filter" href=<?php echo '"'.home_url().'/infospratiques/'.$v->slug.'">'.$v->name; ?></a></li>
    <?php endforeach; ?>
</ul>

<!--  AFFICHAGE GOOGLE MAPS
==================================================================-->

<?php
    if( !empty($locations) ):
?>
    <div class="acf-map">
    <!-- Pour chaque élément, crée un marker -->
    <?php foreach ($locations as $location): ?>
        <?php
            $locationTitle = $location[0];
            $locationContent = $location[1];
            // vérifie si le lieux a des infos de coordonnées
            if(count($location[1]) === 3):
                $locationAddress = $location[1]; ?>
                <!-- si oui, crée un marker -->
                <div class="marker" data-lat="<?php echo $locationAddress['lat']; ?>" data-lng="<?php echo $locationAddress['lng']; ?>">
                    <h3><?php echo $locationTitle; ?></h3>
                    <div><?php echo $locationContent; ?></div>
                </div>
            <?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>




<?php get_sidebar(); ?>
<?php get_footer(); ?>