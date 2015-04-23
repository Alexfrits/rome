<?php get_header(); ?>
<?php now_in(__FILE__) ?>

<main>

    <h1><strong><?php echo $_GET['s'] ?></strong>: résultats de recherche</h1>

<?php
    global $wp_query;
    $args = array(
        'order'               => 'ASC',
        'orderby'             => 'parent',
        's'                   => $_GET['s'],
        'posts_per_page'      => -1
    );

    $wp_query = new WP_Query($args);
 ?>


<?php if($wp_query->have_posts()): ?>
    <ul>
    <?php while($wp_query->have_posts()): $wp_query->the_post(); ?>
        <li>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </li>
    <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>Pas de résultats. :(</p>
<?php endif; ?>

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>