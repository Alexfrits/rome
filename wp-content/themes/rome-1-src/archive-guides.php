<?php get_header(); ?>

<?php now_in(__FILE__) ?>

<?php echo home_url(); ?>

<main>
<?php
  if(have_posts()): ?>
  <div class="guides-list__wrapper">
    <ul class="guides-list">
    <?php while(have_posts()): the_post();
      $formation = get_field_object('formation');
      $ville = get_field_object('ville');
      $photoUrl = get_field_object('photo')['value']['sizes']['carre']; ?>
      <li class="guides-list__item">
      <div class="guides-list__item__col--left">
        <h2 class="guides-list__item__nom"><?php the_title(); ?></h2>
        <img class="guides-list__item__img" src="<?php echo $photoUrl; ?>" alt="">
      </div>
      <div class="guides-list__item__col--right">
        <p class="guides-list__item__formation"><?php echo $formation['value']; ?></p>
        <p class="guides-list__item__ville"><?php echo $ville['value']; ?></p>
      </div>
      </li>
    <?php endwhile; ?>
    </ul>
  </div>
  <?php endif;
?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>