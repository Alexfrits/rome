<?php get_header(); ?>

<!-- /////////////////////////////////////////////////

    Ce fichier n'est pas utilisé en tant que tel par WP,
    il est inclus dans page-visites.php, qui l'affiche.

//////////////////////////////////////////////////////-->

<?php now_in(__FILE__) ?>

<?php echo home_url(); ?>

<main>
<h1>Nos visites</h1>
    <ul>
        <?php
        $args = array(
            'taxonomy'      => 'visites',
            'hide_empty'    => 1
        );
        $visites = get_categories($args); ?>

        <?php foreach ($visites as $i => $v): ?>
            <li><a href="<?php echo home_url() . '/visites/' . $v->slug; ?>"><?php echo $v->name; ?></a></li>
        <?php endforeach; ?>
    </ul>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>