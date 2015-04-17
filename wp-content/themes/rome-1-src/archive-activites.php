<?php get_header(); ?>

<!-- /////////////////////////////////////////////////

    Ce fichier n'est pas utilisé en tant que tel par WP,
    il est inclu dans page-infospratiques.php, qui l'affiche

//////////////////////////////////////////////////////-->

<?php now_in(__FILE__) ?>

<?php echo home_url(); ?>


<?php if(have_posts()) : ?>
    <?php
        // initialisation de l'array qui contient les coordonnées de tous les lieux affichés
        $locations = [];
        while(have_posts()) :$location=[];?>
        <?php the_post(); ?>
        <h3><?php the_title(); ?></h3>
        <p><?php the_content(); ?></p>
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
<?php

// Vol sans vergogne du code de la sidebar par Mehdi

a(get_post_types());

$args = [
    'taxonomy'      => 'infos-pratiques',
    'hide_empty'    => 1
];
$visites = get_categories($args); ?>

<?php foreach ($visites as $i => $v): ?>
    <li><a href=<?php echo '"'.home_url().'/infos-pratiques/'.$v->slug.'">'.$v->name; ?></a></li>
<?php endforeach; ?>

<!-- Quand on a le JS -->
<!--
<div>
    <form action="">
        <label for="">
            <span>Musées</span>
            <input name="" id="" type="checkbox">
        </label>
        <label for="">
            <span>Manger</span>
            <input name="" id="" type="checkbox">
        </label>
        <label for="">
            <span>Dormir</span>
            <input name="" id="" type="checkbox">
        </label>
    </form>
</div>

-->


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
            if(count($location[2]) === 3):
                $locationAddress = $location[2]; ?>
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