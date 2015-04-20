<?php

/* FICHIER EN TRAVAUX GRAVE */

/* RESSOURCES

https://codex.wordpress.org/Data_Validation#Output_Sanitization
http://code.tutsplus.com/tutorials/data-sanitization-and-validation-with-wordpress--wp-25536
https://codex.wordpress.org/Function_Reference/ent2ncr
https://codex.wordpress.org/Function_Reference/sanitize_text_field
https://codex.wordpress.org/Class_Reference/wpdb
https://codex.wordpress.org/Function_Reference/wp_mail
https://codex.wordpress.org/Data_Validation
*/

// global $wpdb; /* pour utiliser l'objet DB de WP, si on améliore en stockant en DB les réservations */

include_once('dev-helpers.php');


/*  récupération des catégories (taxonomies) de visite
    on s'en sert à la vérif du formulaire et dans le HTML du formulaire.
*/

    $visites_query = [
        'taxonomy'      => 'visites',
        'hide_empty'    => 1
    ];

    $visites = get_categories($visites_query);
    $option_str = ''; // compléter ici
    $visit_flag = 0;

    foreach ($visites as $i => $v) {
        $selected = '';

        // validation du slug de visite reçu en $_POST
        if($visit_flag == 0 && isset($_POST['visite']))
            if($_POST['visite'] == $v->slug) {
                $valid['visite'] = $v->name; // nom du circuit choisi envoyé par mail
                $visit_flag = 1;
                $selected = ' selected';
            }

        // définition de la liste de visites
        $option_str .= '<option value="'.$v->slug.'"'.$selected.'>'.$v->name.'</option>';
    }


/* vérification du formulaire */

    // $_POST['newsletter'] will be set only if checkbox is checked
    $newsletter_box = (isset($_POST['newsletter']) ? 'checked' : '');

    if(isset($valid['visite'])) { // slug de visite valide : on vérifie donc le reste du formulaire

        // format de date (regex) + date comprise entre demain et dans 90 jours
        if($_POST['date'] != '') {

            $visit_time = strtotime($_POST['date']);
            $now_time = time();
            $visit_max = 90; // on peut réserver jusqu'à $visit_max jours à l'avance

            $valid['date'] = (
                preg_match("/(2[0-9]{3})-(0[0-9]|1[0-2])-([0-2][0-9]|3[01])/", $_POST['date'])
                && $visit_time > $now_time
                && $visit_time < $now_time + 86400 * $visit_max
                ? 1 : 0
            );
        }
        else
            $valid['date'] = 0;

        // format de l'heure
        $valid['heure'] = (preg_match("/([01][0-9]|2[0-3]):[0-5][0-9]/", $_POST['heure']) ? 1 : 0);

        // nombre de gens
        $gens_max = 40; // à intégrer dans l'admin
        $gens = intval($_POST['nb_gens']);
        $valid['nb_gens'] = ($gens > 0 && $gens <= $gens_max ? 1 : 0);

        // nom (au moins 3 caractères)
        $valid['nom'] = (strlen($_POST['nom']) > 2 ? 1 : 0);

        // mail
        $valid['mail'] = (is_email($_POST['mail']) ? 1 : 0);

        // tél : à faire sérieusement
        $valid['tel'] = (strlen($_POST['tel']) > 5 ? 1 : 0);

        a($valid);

        // search for unvalid field
        $err_flag = 0;
        if(isset($valid))
            if(in_array('0', $valid))
                $err_flag = 1;


         /* send booking by mail */
        if($err_flag == 0) {

            $to = 'meduzen@gmail.com,frits.alex@gmail.com,'.$_POST['mail'];
            $subject = 'Visiter Rome : votre réservation du circuit '.$valid['visite'];

            $msg = '<h1>'.$_POST['nom'].',</h1>';
            $msg .= "<strong>Blablabla.</strong>";

            $head = "From: reservation@visiter-rome.com \r\n";
            $head .= "MIME-Version: 1.0 \r\n";
            $head .= "Content-Type: text/html; charset-utf-8 \r\n";

            if(mail($to,$subject,$msg,$head))
                echo 'mail <strong>sent</strong>';
            else
                echo 'mail <strong>not sent</strong>';
        }
        else
            $gerer_les_erreurs = 'va falloir les gérer, ouais…';
    }
?>

<?php get_header(); ?>
<?php now_in(__FILE__) ?>

<?php

/*  Vérifie si the_post est pas vide.
*/

if(have_posts())
    while(have_posts()) {
        the_post();
        $p_flag = 1; // permet de vérifier plus loin si the_post est pas vide.
    }
else
    $p_flag = 0;

?>

<main>

<?php if($p_flag === 1): ?>
    <h2><?php the_title(); ?></h2>
<?php endif; ?>

<h1 style="color: #770000;">Note : penser au mode de paiement (ou bien osef).</h1>

<form action="" method="post">

    <!-- choix de visite et date -->
    <fieldset class="fset--visite">
        <legend>La visite</legend>
        <label for="visite">Choix de la visite
            <select id="visite" name="visite" id="visite" required><?php echo $option_str; ?></select>
        </label>
        <label for="date">Date
            <input  id="date" name="date" type="date"
                    value="<?php echo (isset($_POST['date']) ? $_POST['date'] : ''); ?>"
                    required>
        </label>
        <label for="heure">Heure souhaitée
            <input  id="heure" name="heure" type="time"
                    value="<?php echo (isset($_POST['heure']) ? $_POST['heure'] : ''); ?>"
                    required>
        </label>
    </fieldset>

    <!-- nb de participants -->
    <fieldset class="fset--participants">
        <legend>Nombre de participants</legend>
        (Bon, faut se fixer.)
        <label for="nb_gens">Combien de personnes participeront à la visite&nbsp;?
            <input  id="nb_gens" name="nb_gens" type="range" placeholder=""
                    value="<?php echo (isset($_POST['nb_gens']) ? $_POST['nb_gens'] : ''); ?>"
                    min="1" max="40" step="1" value="5" required>
        </label>
        <label for="nb_gens">Combien de personnes participeront à la visite&nbsp;?
            <input  id="nb_gens" name="nb_gens" type="number" placeholder=""
                    value="<?php echo (isset($_POST['nb_gens']) ? $_POST['nb_gens'] : ''); ?>"
                    min="1" max="40" step="1" value="5" required>
        </label>
    </fieldset>

    <!-- coordonnées -->
    <fieldset class="fset--contact">
        <legend>Vos coordonnées</legend>

        <label for="nom">Vos nom et prénom, ou le nom de votre association, école…
            <input  id="nom" name="nom" type="text"
                    value="<?php echo (isset($_POST['nom']) ? $_POST['nom'] : ''); ?>"
                    placeholder="Jules César" required>
        </label>

        <label for="mail">Mail
            <input  id="mail" name="mail" type="email"
                    value="<?php echo (isset($_POST['mail']) ? $_POST['mail'] : ''); ?>"
                    placeholder="venividi@v.ici" required>
        </label>

        <label for="tel">Tél.
            <input  id="tel" name="tel" type="tel"
                    value="<?php echo (isset($_POST['tel']) ? $_POST['tel'] : ''); ?>"
                    placeholder="(+32) (2) 123 45 67" required>
        </label>
    </fieldset>

    <!-- newsletter -->
    <fieldset class="fset--checkbox">
        <legend>Lettre d’information</legend>
        <label for="newsletter">S’inscrire à la lettre d’information.
            <input  id="newsletter" name="newsletter"
                    type="checkbox" <?php echo $newsletter_box; ?>>
        </label>
    </fieldset>

    <button>Envoyer la réservation</button>
</form>

<?php if($p_flag === 1): ?>
    <h2><?php the_content(); ?></h2>
<?php endif; ?>

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>