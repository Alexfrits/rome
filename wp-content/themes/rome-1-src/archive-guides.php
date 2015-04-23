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
        <?php the_title(); ?>
        <img class="guides-list__item__img" src="<?php echo $photoUrl; ?>" alt="">
        <p class="guides-list__item__formation"><?php echo $formation['value']; ?></p>
        <p class="guides-list__item__ville"><?php echo $ville['value']; ?></p>
      </li>
    <?php endwhile; ?>
    </ul>
  </div>
  <?php endif;
?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>