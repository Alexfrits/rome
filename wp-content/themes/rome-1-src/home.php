<?php get_header(); ?>

<?php now_in(__FILE__) ?>

<main>


  <h1>La ville à voir ... et à revoir !</h1>
<?php
  $page_qsn_query = new WP_Query( 'page=&pagename=qui-sommes-nous' );

  if($page_qsn_query->have_posts()):
    while($page_qsn_query->have_posts()): $page_qsn_query->the_post();?>
      <h2><?php the_title(); ?></h2>
      <?php the_content(); ?>

    <? endwhile;
  endif; wp_reset_postdata(); ?>
</main>


<!--
<main>
 <h1>La ville à voir ... et à revoir !</h1>
  <p><strong>Rome, la ville éternelle ...Qui n'a pas rêvé de la visiter ?</strong><br>
    Mais, comment faire pour tout visiter, tout savoir ? il y a tant de choses à découvrir, à connaître, tant de siècles à parcourir ... il est impossible de tout savoir des artistes, des grands personnages et des moments historiques qui ont forgés cette ville légendaire.</p>
  <p><strong>Faire appel à un guide est encore plus indispensable à Rome qu'ailleur !</strong></p>
  <h2>Qui sommes nous ?</h2>
  <p>Nous sommes 5 historiennes de l'art passionnées par notre ville. Nous vous proposons toute une série de formules adaptées aux envies et au «&nbsp;timing&nbsp;» de chacun.</p>
  <p class="guides-index">
  <img src="images/guides/juliaThumb.jpg" alt="">
  <img src="images/guides/silvioThumb.jpg" alt="">
  <img src="images/guides/pamThumb.jpg" alt="">
  <img src="images/guides/RobertoThumb.jpg" alt="" >
  <img src="images/guides/taniaThumb.jpg" alt="">
  </p>
  <p><strong>Nos visites guidées se font uniquement en français.</strong></p>
  <p>En recourant à nos services, vous pourrez bénéficier en plus, des avantages qu'un guide autorisé peut vous offrir :</p>
  <ul>
    <li> horaires &quot;ciblés&quot;</li>
    <li> accès facilités</li>
    <li>réservations de musées et monuments</li>
    <li> info sur les évènenent et la vie romaine ...</li>
  </ul>
  <p>Vous trouverez aussi sur ce site, une mine de renseignements utiles et de liens vers des sites intéressants .</p>
  <p>Bonne visite...</p>
</main>
-->



<?php get_sidebar(); ?>
<?php get_footer(); ?>