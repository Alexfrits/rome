<?php get_header(); ?>

<!-- /////////////////////////////////////////////////

    Ce fichier n'est pas utilisé en tant que tel par WP,
    il est inclu dans page-infospratiques.php, qui l'affiche

//////////////////////////////////////////////////////-->

<?php now_in(__FILE__) ?>

<?php echo home_url(); ?>

<main>

<!--  MODIFICATION de la boucle (car on est dans un autre fichier)
==================================================================-->
<!--
    cette boucle n'affiche rien,
    elle récup les infos et crée l'array
    pour l'affichage des markers de la map
-->
<?php
    $args = array(
                'post_type' => 'activites',
            );

    // The Query
    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ): ?>
        <ul>
            <?php
            $locations = [];
            while($the_query->have_posts()):
                // init / vide l'array
                $location = [];
                $the_query->the_post();

                // stocke le titre du marker
                $location['title'] = get_the_title();

                // stocke l'addresse/lat/lng
                $location['gmap'] = get_field('google_map');

                // // stockage de la catégorie de 'infospratiques'
                $infosTerms = get_the_terms(get_the_id(), 'infospratiques');
                foreach ($infosTerms as $t => $v):
                    $location['cat'] = $v->slug;
                endforeach;

                // si l'array a des infos, on les stocke dans $locations
                if( !empty($location) ):
                    $locations[] = $location;
                endif;
            endwhile; ?>
        </ul>
        <?php a($locations); ?>
    <?php else: ?>
        <p><?php echo 'Aucun post correspondant à votre requête'; ?></p>
    <?php endif;
    wp_reset_postdata();
?>

<!--  Contrôles customs et filtres pour la map
=====================================================================

    GMCC = Google Maps Custom Controls
    cette classe est utilisée par le JS dans acf-maps.js
-->
<div class="gmcc__wrapper" id="gmcc_wrapper">
    <ul class="gmcc">
        <?php
        $args = [
            'taxonomy'      => 'infospratiques',
            'hide_empty'    => 1
        ];
        $visites = get_categories($args); ?>

        <?php foreach ($visites as $i => $v): ?>
            <li class="gmcc__filter">
                <a
                data-cat="<?php echo $v->slug; ?>"
                href="<?php echo home_url() . '/infospratiques/' . $v->slug; ?>"
                >
                    <?php echo $v->name; ?>
                <object width="23" height="30" type="image/svg+xml" data="<?php echo get_template_directory_uri() . '/img/gmaps-icons/icon-' . $v->slug . '.svg'; ?>">
                    <img
                    src="<?php echo get_template_directory_uri() . '/img/gmaps-icons/icon-' . $v->slug . '.png'; ?>"
                    alt="<?php echo 'picto ' . $v->slug; ?>"
                    >
                </object>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!--  AFFICHAGE GOOGLE MAPS
==================================================================-->
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
                <div
                class="marker"
                data-lat="<?php echo $location['gmap']['lat']; ?>"
                data-lng="<?php echo $location['gmap']['lng']; ?>"
                data-cat="<?php echo $location['cat']; ?>"
                data-img="<?php echo get_template_directory_uri() . '/img/gmaps-icons/icon-' . $location['cat'] . '.png'; ?>"
                >
                    <h3><?php echo $locationTitle; ?></h3>
                    <div><?php echo get_template_directory_uri() . '/img/gmaps-icons/icon-' . $location['cat'] . '.png'; ?></div>
                </div>
            <?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>


</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>