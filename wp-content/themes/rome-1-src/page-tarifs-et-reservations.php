<?php

/* FICHIER EN TRAVAUX GRAVE */

/* RESSOURCES

https://codex.wordpress.org/Data_Validation#Output_Sanitization
http://code.tutsplus.com/tutorials/data-sanitization-and-validation-with-wordpress--wp-25536
https://codex.wordpress.org/Function_Reference/ent2ncr
https://codex.wordpress.org/Function_Reference/sanitize_text_field
https://codex.wordpress.org/Class_Reference/wpdb
https://codex.wordpress.org/Function_Reference/wp_mail
*/


// traitement des données reçues en $_POST ; https://codex.wordpress.org/Data_Validation

// global $wpdb; /* pour utiliser l'objet DB de WP, si on améliore en stockant en DB les réservations */

include_once('dev-helpers.php');


/*  récupération des catégories (taxonomies) de visite
    on s'en sert à la vérif du formulaire et dans le HTMl du formulaire.
*/

$args = [
    'taxonomy'      => 'visites',
    'hide_empty'    => 1
];

$visites = get_categories($args);
$option_str = '';

foreach ($visites as $i => $v) {
    $option_str .= '<option value="'.$v->slug.'">'.$v->name.'</option>';
    if($_POST['visite'] == $v->slug)
        $valid['visite'] = true;
}

a($_POST);

if($_POST['visite'] != '') { // si le formulaire est envoyé, on commence sa vérification dans ce if()

    // format de date + date ultérieure au jour de réservation
    // WARNING: REMPLACER PAR UNE REGEXP
    if($_POST['date'] != '') {
        $date = explode('-',$_POST['date']);
        if(checkdate($date[1],$date[2],$date[0]) && $_POST['date'] > date('Y-m-d'));
    }

    // format de l'heure souhaitée
    // WARNING: REMPLACER PAR UNE REGEXP
    if($_POST['heure'] != '') {
        $heure = explode(':',$_POST['heure']);
        if($heure[0] >= 0 && $heure[0] < 25 && $heure[1] >= 0 && $heure[1] < 61);
    }

    // nombre de gens
    // WARNING: REMPLACER PAR UNE REGEXP
    if($_POST['nb_gens'] != '' && intval($_POST['nb_gens']) > 0) {
    }
}



// $_POST['visite']
// $_POST['heure']
// $_POST['nb_gens']
// $_POST['nom']
// $_POST['mail']
// $_POST['tel']
// $_POST['newsletter']



?>




<?php get_header(); ?>
<?php now_in(__FILE__) ?>

<?php

/*  Vérifie si the_post est pas vide.
*/

if(have_posts())
    while(have_posts()) {
        the_post();
        $p_flag = true; // permet de vérifier plus loin si the_post est pas vide.
    }
else
    $p_flag = false;

?>

<main>

<?php if($p_flag === true): ?>
    <h2><?php the_title(); ?></h2>
<?php endif; ?>

<h1 style="color: #770000;">Note : ajouter les placeholders &amp; required, quelques attributs dans les champs et penser au mode de paiement (ou osef).</h1>

<form action="" method="post">

    <fieldset class="fset--visite">
        <legend>La visite</legend>
        <label for="">Choix de la visite
            <select id="visite" name="visite" id="visite"><?php echo $option_str; ?></select>
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