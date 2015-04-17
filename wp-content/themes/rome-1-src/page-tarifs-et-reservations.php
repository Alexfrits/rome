<?php get_header(); ?>
<?php now_in(__FILE__) ?>

<?php

/*  Vérifier si the_post est pas vide.
*/

if(have_posts())
    while(have_posts()) {
        the_post();
        $p_flag = true; // permet de vérifier plus loin si the_post est pas vide.
    }
else
    $p_flag = false;

// récupération des catégories (taxonomies) de visite

$args = [
    'taxonomy'      => 'visites',
    'hide_empty'    => 1
];
$visites = get_categories($args); ?>

<main>

<?php if($p_flag === true): ?>
    <h2><?php the_title(); ?></h2>
<?php endif; ?>

<p>Note : penser au mode de paiement (ou osef).</p>

<form action="" method="post">

    <fieldset class="fset--visite">
        <legend>La visite</legend>
        <label for="">Choix de la visite
            <select id="visite" name="visite" id="visite">

                <?php foreach ($visites as $i => $v): ?>
                    <option value=<?php echo '"'.home_url().'/visites/'.$v->slug.'">'.$v->name;; ?></option>
                <?php endforeach; ?>

            </select>
        </label>
        <label for="date">Date<input id="date" name="date" type="date"></label>
        <label for="heure">Heure souhaitée<input id="heure" name="heure" type="time"></label>
    </fieldset>

    <fieldset class="fset--participants">
        <legend>Nombre de participants</legend>
        (Bon, faut se fixer.)
        <label for="nb_gens">Combien de personnes participeront à la visite&nbsp;?<input id="nb_gens" name="nb_gens" type="range" min="1" max="40" step="1" value="5"></label>
        <label for="nb_gens">Combien de personnes participeront à la visite&nbsp;?<input id="nb_gens" name="nb_gens" type="number" min="1" max="40" step="1" value="5"></label>
    </fieldset>

    <fieldset class="fset--contact">
        <legend>Vos coordonnées</legend>
        <label for="nom">Votre nom et prénom<input id="nom" name="nom" type="text"></label>
        <label for="mail">Mail<input id="mail" name="mail" type="email"></label>
        <label for="tel">Tél.<input id="tel" name="tel" type="tel"></label>
    </fieldset>

    <fieldset class="fset--checkbox">
        <legend>Lettre d’information</legend>
        <label for="newsletter">S’inscrire à la lettre d’information.<input id="newsletter" name="newsletter" type="checkbox"></label>
    </fieldset>

    <button>Envoyer la réservation</button>
</form>

<?php if($p_flag === true): ?>
    <h2><?php the_content(); ?></h2>
<?php endif; ?>

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>