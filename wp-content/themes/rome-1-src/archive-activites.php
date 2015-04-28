<?php get_header(); ?>

<!-- /////////////////////////////////////////////////

    Ce fichier n'est pas utilisé en tant que tel par WP,
    il est inclu dans page-infospratiques.php, qui l'affiche

//////////////////////////////////////////////////////-->

<?php now_in(__FILE__) ?>

<?php echo home_url(); ?>
<?php echo get_query_var( ('pagename') ); ?>
<?php 
    $infocat = explode('?infocat=', $_SERVER['REQUEST_URI']);
    $infocat = (is_array($infocat) ? $infocat[count($infocat) - 1] : '');
    if(strstr($infocat, '/'))
        $infocat = '';
?>

<svg>
    <defs>
        <!-- dormir -->
        <symbol class="gmcc__marker" id="icon-dormir" viewBox="286.1 405.9 23 30">
            <title>Icône dormir</title>
            <desc>Une icône représentant un lit</desc>
            <path class="gmcc__marker__bg" fill="#ccc" d="M309.1 418.5c0 6.9-9.4 15.4-11.5 17.4-2.1-2.1-11.5-10.5-11.5-17.4 0-6.9 5.1-12.6 11.5-12.6C304 405.9 309.1 411.6 309.1 418.5z"/>
            <g>
                <circle class="gmcc__marker__picto" fill="#ddd" cx="291.7" cy="413.8" r="1.8"/>
                <path class="gmcc__marker__picto" fill="#ddd" d="M300.9 413.8c0 0-6.4 0-6.4 0 0 1.5-1.2 2.7-2.7 2.7h-1.4c-0.2 0-0.5 0-0.5 0.5 0 0.1 0 0.6 0 0.9 0 0.5 0.4 0.5 0.5 0.5h15v-1.8C305.4 415.6 305.4 413.8 300.9 413.8zM304.5 419.3h-13.7c-0.5 0-0.9 0.5-0.9 0.9 0 0.9 0 2 0 2.7 0 0.5 0.5 0.9 0.9 0.9s0.9-0.5 0.9-0.9v-1.8h11.8v1.8c0 0.5 0.5 0.9 0.9 0.9 0.5 0 0.9-0.5 0.9-0.9v-2.7C305.4 419.7 304.9 419.3 304.5 419.3z"/>
            </g>
        </symbol>
        <!-- manger -->
        <symbol class="gmcc__marker" id="icon-manger" viewBox="286.1 405.9 23 30">
            <title>Icône manger</title>
            <desc>Une icône représentant un couteau et une fourchette</desc>
            <path class="gmcc__marker__bg" fill="#ccc" d="M309.1 418.5c0 6.9-9.4 15.4-11.5 17.4-2.1-2.1-11.5-10.5-11.5-17.4 0-6.9 5.1-12.6 11.5-12.6C304 405.9 309.1 411.6 309.1 418.5z"/>
            <path class="gmcc__marker__picto" fill="#ddd" d="M291.2 410.7c0 0-1-0.6-1 0.5 -0.1 1.1 1.7 3.3 3.5 5.2 1.9 2 1.9 2 3.6 3.5l0.9 0c0 0 3.1 3.5 4.3 4.6 0.8 0.8 2.1-0.5 1.4-1.1L291.2 410.7zM290.4 423.1c-0.9 0.9 0.4 2.4 1.4 1.4s3.9-3.9 3.9-3.9l-1.4-1.4C294.3 419.2 291.3 422.2 290.4 423.1zM304.7 412.9c-0.1-0.3-0.4-0.5-0.7-0.5 -0.2 0-0.4 0.1-0.6 0.3l-2.5 2.5c-0.3 0.3-0.8 0.4-1 0.1l-0.2-0.2c-0.4-0.4-0.1-0.9 0.1-1.1l2.4-2.4c0.3-0.3 0.3-0.6 0.2-0.9 -0.1-0.3-0.4-0.5-0.7-0.5 -0.2 0-0.4 0.1-0.6 0.3l-2.6 2.6c-0.7 0.7-1 1.9-0.1 2.8 0.2 0.2 0.2 0.2 0.2 0.2 0 0 0.1 0.1 0.5 0.5 0.4 0.4 0.8 0.6 1.3 0.6 0.5 0 1.1-0.3 1.5-0.7l2.6-2.6C304.7 413.5 304.8 413.2 304.7 412.9z"/>
        </symbol>
        <!-- musees -->
        <symbol class="gmcc__marker" id="icon-musees" viewBox="286.1 405.9 23 30">
            <title>Icône musees</title>
            <desc>Une icône représentant un musée</desc>
            <path class="gmcc__marker__bg" fill="#ccc" d="M309.1 418.5c0 6.9-9.4 15.4-11.5 17.4-2.1-2.1-11.5-10.5-11.5-17.4 0-6.9 5.1-12.6 11.5-12.6C304 405.9 309.1 411.6 309.1 418.5z"/>
            <path class="gmcc__marker__picto" fill="#ddd" d="M305.4 414.1l-7.7-5.2 -7.7 5.2h7.7H305.4zM296.1 411.8c0-0.8 0.7-1.5 1.5-1.5s1.5 0.7 1.5 1.5 -0.7 1.5-1.5 1.5S296.1 412.6 296.1 411.8zM304.4 414.8h-13.5c-0.2 0-0.3 0.2-0.3 0.3s0.2 0.3 0.3 0.3h13.5c0.2 0 0.3-0.2 0.3-0.3S304.6 414.8 304.4 414.8zM304.4 425.1h-13.5c-0.2 0-0.3 0.2-0.3 0.3s0.2 0.3 0.3 0.3h13.5c0.2 0 0.3-0.2 0.3-0.3S304.6 425.1 304.4 425.1zM293.4 416.7c0.2 0 0.3-0.2 0.3-0.3s-0.2-0.3-0.3-0.3h-2.6c-0.2 0-0.3 0.2-0.3 0.3s0.2 0.3 0.3 0.3h0.3v7.1h-0.3c-0.2 0-0.3 0.2-0.3 0.3 0 0.2 0.2 0.3 0.3 0.3h2.6c0.2 0 0.3-0.2 0.3-0.3s-0.2-0.3-0.3-0.3h-0.3v-7.1H293.4zM296 423.8c-0.2 0-0.3 0.2-0.3 0.3 0 0.2 0.2 0.3 0.3 0.3h2.6c0.2 0 0.3-0.2 0.3-0.3s-0.2-0.3-0.3-0.3h-0.3v-7.1h0.3c0.2 0 0.3-0.2 0.3-0.3s-0.2-0.3-0.3-0.3H296c-0.2 0-0.3 0.2-0.3 0.3s0.2 0.3 0.3 0.3h0.3v7.1H296zM301.2 423.8c-0.2 0-0.3 0.2-0.3 0.3 0 0.2 0.2 0.3 0.3 0.3h2.6c0.2 0 0.3-0.2 0.3-0.3s-0.2-0.3-0.3-0.3h-0.3v-7.1h0.3c0.2 0 0.3-0.2 0.3-0.3s-0.2-0.3-0.3-0.3h-2.6c-0.2 0-0.3 0.2-0.3 0.3s0.2 0.3 0.3 0.3h0.3v7.1H301.2z"/>
        </symbol>
    </defs>
</svg>

<main <?php echo ($infocat != '' ? 'data-couille="'.$infocat.'"' : ''); ?>>

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

                // get custom fields baby!
                    $loc_full = get_field_objects();
                    foreach ($loc_full as $key => $loc) {

                        // champ google_map déjà récupéré dans gmap
                        if ($loc['name'] !== 'google_map') {
                            if ($key !== 'prefixe') {
                                $location['infos'][$key]['name'] = $loc['name'];
                                $location['infos'][$key]['label'] = $loc['label'];
                                $location['infos'][$key]['value'] = $loc['value'];

                                if(isset($loc['append']))
                                    if($loc['append'] != '')
                                        $location['infos'][$key]['append'] = $loc['append'];

                                if(isset($loc['prepend']))
                                    if($loc['prepend'] != '')
                                        $location['infos'][$key]['prepend'] = $loc['prepend'];
                            } else {
                                // récupère la valeur du champ 'préfixe' comme prepend de 'prix'
                                if(isset( $loc['value']))
                                    if( $loc['value'] != '')
                                        $location['infos']['prix']['prepend'] = $loc['value'];
                            }
                        }
                    }

                // si l'array a des infos, on les stocke dans $locations
                if( !empty($location) ):
                    $locations[] = $location;
                endif;

            endwhile; ?>
        </ul>
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
    <h2 class="gmcc__title">Filtres</h2>
    <ul class="gmcc">
        <?php
        $args = [
            'taxonomy'      => 'infospratiques',
            'hide_empty'    => 1
        ];
        $visites = get_categories($args); ?>

        <?php foreach ($visites as $i => $v): ?>
            <li class="gmcc__filter ?>">
                <a
                class="gmcc__label"
                data-cat="<?php echo $v->slug; ?>"
                href="<?php echo home_url() . '/infospratiques/' . $v->slug; ?>"
                >
                    <span class="gmcc__label__name">
                        <?php echo $v->name; ?>
                    </span>
                    <svg class="gmcc__marker__wrapper">
                        <use xlink:href="#<?php echo 'icon-' . $v->slug; ?>"/>
                    </svg>
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
                    <h3><?php echo $location['title']; ?></h3>
                </div>
            <?php endif; ?>
    <?php endforeach; ?>
    </div>
    <div class="act-inf__wrapper">
        <ul class="act-inf">
            <?php  foreach($locations as $l => $v) : ?>
                <?php  $loc_inf = $v['infos']; ?>
                
                <li class="act-inf__item cat-<?php echo $v['cat']; ?>">
                    <div class="act-inf__item__col--left">
                        <h3 class="act-inf__item__nom"><?php echo $v['title']; ?></h3>
                        <p><?php echo $v['gmap']['address']; ?></p>
                    </div>

                    <ul class="act-inf__item__col--right">
                        <?php if($loc_inf['phone']['value'] !== ''): ?>
                            <li><?php echo $loc_inf['phone']['prepend'] . ' ' . $loc_inf['phone']['value']; ?></li>
                        <?php endif; ?>

                        <?php if($loc_inf['email']['value'] !== ''): ?>
                            <li><?php echo $loc_inf['email']['value']; ?></li>
                        <?php endif; ?>

                        <?php if($loc_inf['email']['value'] !== ''): ?>
                            <li><?php echo $loc_inf['website']['value']; ?></li>
                        <?php endif; ?>

                        <?php if($loc_inf['prix']['value'] !== ''): ?>
                            <li>
                                <?php if(isset($loc_inf['prix']['prepend'])):
                                    echo $loc_inf['prix']['prepend'].' ';
                                endif;
                                echo $loc_inf['prix']['value'];
                                echo $loc_inf['prix']['append']?>
                            </li>
                        <?php endif; ?>

                        <?php if($loc_inf['horaires']['value'] !== ''): ?>
                            <li>
                                <?php echo $loc_inf['horaires']['label']. ': '; ?>
                                <?php echo $loc_inf['horaires']['value']; ?>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php  endforeach; ?>
        <?php endif; ?>
            
        </ul>
        
    </div>
    <a href="<?php add_query_arg( 'infocat', 'dormir', site_url( '/infospratiques/' ) )?>">test</a>
    <?php echo get_query_var( 'infocat' ); ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>