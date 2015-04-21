<?php


/* FICHIER EN TRAVAUX GRAVE */

/* RESSOURCES

https://codex.wordpress.org/Data_Validation
http://code.tutsplus.com/tutorials/data-sanitization-and-validation-with-wordpress--wp-25536
https://codex.wordpress.org/Function_Reference/ent2ncr
https://codex.wordpress.org/Function_Reference/sanitize_text_field
https://codex.wordpress.org/Class_Reference/wpdb
https://codex.wordpress.org/Function_Reference/wp_mail
*/

// global $wpdb; /* pour utiliser l'objet DB de WP, si on améliore en stockant en DB les réservations */

include_once('dev-helpers.php');


/*  récupération des catégories (taxonomies) de visite
    on s'en sert à la vérif du formulaire et dans le HTML du formulaire.
*/

    // récupération du slug de visite à partir de l'URL (utile dan sle cas où on vient d'une visite)
    $visit_referer = explode('/', wp_get_referer());
    $visit_referer = ($visit_referer [0] != null ? $visit_referer[count($visit_referer) - 2] : '');

    $visites_query = [
        'taxonomy'      => 'visites',
        'hide_empty'    => 1
    ];

    $visites = get_categories($visites_query);
    $option_str = '<option value=""></option>';
    $visit_flag = 0;

    foreach ($visites as $i => $v) {
        $selected = '';

        // traitement du slug de visite reçu en $_POST (ou en $_SERVER['HTTP_REFERER'] via wp_get_referer)

        if($visit_referer == $v->slug)
            $selected = ' selected';

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


         /* valid form, go! */
        if($err_flag == 0) {

            // AJAX status
            $to_ajax['status'] = 0;

            // mail stuff

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

        /* unvalid form, manage errors */
        else {

            // AJAX status
            $to_ajax['status'] = 'Merci de corriger les erreurs suivantes.';

            // keys with errors
            $err_list = array_keys($valid,'0');
            a($err_list);

            // init error messages (NEED IMPROVMENTS)
            $err_msg_full_list = [
                'visite' => 'Veuillez choisir une visite',
                'date' => 'La date doit être comprise entre demain et '.$visit_max.' jours plus tard (en vrai, donner la date + prévoir placeholder "jj/mm/aaaa" pour fallback).',
                'heure' => 'L’heure doit ressemble à 14:00',
                'nb_gens' => 'Désolé, on prend pas les groupes de plus de '.$gens_max.' personnes',
                'nom' => 'Un nom en moins de 2 caractères&nbsp;? Allons…',
                'mail' => 'Une adresse électronique ressemble à <em>nom@domaine.com</em>.',
                'tel' => 'Ce numéro de téléphone est moche.'
            ];

            // store error msg like $err_msg_liste['mail'] = 'Format de mail incorrect.'
            foreach ($err_list as $e) {
                $to_ajax['errors'][$e] = $err_msg_full_list[$e];
            }
        }
    }

a($to_ajax);

// AJAX

if(isset($_POST['ajax'])) {
    if(isset($error))
        echo json_encode($to_ajax);
    exit;
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

<form id="reservation" action="" method="post">

    <!-- choix de visite et date -->
    <fieldset class="fset--visite">
        <legend>La visite</legend>
        <label for="visite">Choix de la visite
            <?php echo (isset($err_msg_list['visite']) ? '<p class="err-msg">'.$err_msg_list['visite'].'</p>' : '') ?>
            <p><?php echo '';  ?></p>
            <select id="visite" name="visite" id="visite" required><?php echo $option_str; ?></select>
        </label>
        <label for="date">Date
            <?php echo (isset($err_msg_list['date']) ? '<p class="err-msg">'.$err_msg_list['date'].'</p>' : '') ?>
            <input  id="date" name="date" type="date"
                    value="<?php echo (isset($_POST['date']) ? $_POST['date'] : ''); ?>"
                    required>
        </label>
        <label for="heure">Heure souhaitée
            <?php echo (isset($err_msg_list['heure']) ? '<p class="err-msg">'.$err_msg_list['heure'].'</p>' : '') ?>
            <input  id="heure" name="heure" type="time" step="900"
                    value="<?php echo (isset($_POST['heure']) ? $_POST['heure'] : ''); ?>"
                    required>
        </label>
        <label for="nb_gens">Nombre de participants
            <?php echo (isset($err_msg_list['nb_gens']) ? '<p class="err-msg">'.$err_msg_list['nb_gens'].'</p>' : '') ?>
            <input  id="nb_gens" name="nb_gens" type="number" placeholder=""
                    value="<?php echo (isset($_POST['nb_gens']) ? $_POST['nb_gens'] : ''); ?>"
                    min="1" max="40" step="1" value="5" required>
        </label>
    </fieldset>

    <!-- coordonnées -->
    <fieldset class="fset--contact">
        <legend>Vos coordonnées</legend>

        <label for="nom">Vos nom et prénom, ou le nom de votre association, école…
            <?php echo (isset($err_msg_list['nom']) ? '<p class="err-msg">'.$err_msg_list['nom'].'</p>' : '') ?>
            <input  id="nom" name="nom" type="text"
                    value="<?php echo (isset($_POST['nom']) ? $_POST['nom'] : ''); ?>"
                    placeholder="Jules César" required>
        </label>

        <label for="mail">Mail
            <?php echo (isset($err_msg_list['mail']) ? '<p class="err-msg">'.$err_msg_list['mail'].'</p>' : '') ?>
            <input  id="mail" name="mail" type="email"
                    value="<?php echo (isset($_POST['mail']) ? $_POST['mail'] : ''); ?>"
                    placeholder="venividi@v.ici" required>
        </label>

        <label for="tel">Tél.
            <?php echo (isset($err_msg_list['tel']) ? '<p class="err-msg">'.$err_msg_list['tel'].'</p>' : '') ?>
            <input  id="tel" name="tel" type="tel"
                    value="<?php echo (isset($_POST['tel']) ? $_POST['tel'] : ''); ?>"
                    placeholder="(+32) (2) 123 45 67" required>
        </label>
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