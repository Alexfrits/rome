<?php

/* RESSOURCES

https://codex.wordpress.org/Data_Validation
http://code.tutsplus.com/tutorials/data-sanitization-and-validation-with-wordpress--wp-25536
https://codex.wordpress.org/Function_Reference/ent2ncr
https://codex.wordpress.org/Function_Reference/sanitize_text_field
https://codex.wordpress.org/Class_Reference/wpdb
https://codex.wordpress.org/Function_Reference/wp_mail
*/

// global $wpdb; /* pour utiliser l'objet DB de WP, si on améliore en stockant en DB les réservations */

$visit_max = 90; // on peut réserver jusqu'à $visit_max jours à l'avance
$gens_max = 40; // nombre max. de participants à une visite


// format de date jj/mm/yyyy + date comprise entre demain et dans N jours
function date_check($date,$nb_days_further) {

    if($date != '') {

            $visit_time = strtotime($date);
            $now_time = time();
            $nb_days_further = 90;

            $valid_date = (
                preg_match("/(2[0-9]{3})-(0[0-9]|1[0-2])-([0-2][0-9]|3[01])/", $date)
                && $visit_time > $now_time
                && $visit_time < $now_time + 86400 * $nb_days_further
                ? 1 : 0
            );
            return $valid_date;
    }
    else
        return 0;
}

// format de l'heure (ex. : 13:37)
function heure_check($heure) {
    $valid_heure = (preg_match("/([01][0-9]|2[0-3]):[0-5][0-9]/", $heure) ? 1 : 0);
    return $valid_heure;
}

function nb_gens_check($nb_gens,$nb_gens_max) {
    $nb_gens = intval($nb_gens);
    $valid_gens = ($nb_gens > 0 && $nb_gens <= $nb_gens_max ? 1 : 0);
    return $valid_gens;
}

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

        // vérification du slug de visite reçu en $_POST (ou en $_SERVER['HTTP_REFERER'] via wp_get_referer)

        if($visit_referer == $v->slug)
            $selected = ' selected';

        if($visit_flag == 0 && isset($_POST['visite'])) {
            if($_POST['visite'] == $v->slug) {
                $valid['visite'] = $v->name; // nom du circuit choisi envoyé par mail
                $visit_flag = 1;
                $selected = ' selected';
            }
            else
                $valid['visite'] = 0;
        }

        // définition de la liste de visites
        $option_str .= '<option value="'.$v->slug.'"'.$selected.'>'.$v->name.'</option>';
    }

    // $_POST['newsletter'] will be set only if checkbox is checked
    $newsletter_box = (isset($_POST['newsletter']) ? 'checked' : '');

/* vérification du formulaire */

    if(isset($_POST['visite'])) {

        $valid['date'] = date_check($_POST['date'], $visit_max);
        $valid['heure'] = heure_check($_POST['heure']);
        $valid['nb_gens'] = nb_gens_check($_POST['nb_gens'], $gens_max);

        if( !isset($_POST['fset-check']) || $_POST['fset-check'] == 'contact') {

            // nom (au moins 3 caractères)
            $valid['nom'] = (strlen($_POST['nom']) > 2 ? 1 : 0);

            // mail
            $valid['mail'] = (is_email($_POST['mail']) ? 1 : 0);

            // tél : à faire sérieusement
            $valid['tel'] = (strlen($_POST['tel']) > 5 ? 1 : 0);
        }

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
            if(!isset($_POST['fset-check'])) {

                $to = 'meduzen@gmail.com,frits.alex@gmail.com,'.$_POST['mail'];
                $subject = 'Visiter Rome : votre réservation du circuit '.$valid['visite'];

                $msg = '<h1>'.$_POST['nom'].',</h1>';
                $msg .= "<strong>Blablabla.</strong>";

                $head = "From: reservation@visiter-rome.com \r\n";
                $head .= "MIME-Version: 1.0 \r\n";
                $head .= "Content-Type: text/html; charset-utf-8 \r\n";

                if(mail($to,$subject,$msg,$head)) {
                    $to_ajax['mail'] = 1;
                    $to_ajax['mail_msg'] = 'Votre demande de réservation a été enregistrée et vous est envoyée par mail. Nous y répondrons dès que possible.';
                    $mail_msg_class = 'form-ok';
                }
                else {
                    $to_ajax['mail'] = 0;
                    $to_ajax['mail_msg'] = 'La Poste est en grève, votre réservation n’a pu aboutir. Décrochez donc votre téléphone pour nous appeler. :(';
                    $mail_msg_class = 'form-no';
                }
            }
        }

        /* unvalid form, manage errors */
        else {

            // AJAX status
            $to_ajax['status'] = 'Merci de corriger les erreurs suivantes.';

            // keys with errors
            $err_list = array_keys($valid,'0');
            // a($err_list);

            // init error messages (NEED IMPROVMENTS)
            $err_msg_list = [
                'visite' =>'Vous n’avez pas choisi de visite.',
                'date' =>'La date doit être comprise entre '.date('d/m/Y',time() + 86400).' et '.date('d/m/Y',time() + $visit_max * 86400).'.',
                'heure' =>'Le format d’heure doit ressembler à, par exemple, 14:00.',
                'nb_gens' =>'Max. : '.$gens_max.' personnes par visite.',
                'nom' => 'Un nom en moins de 2 caractères&nbsp;? Allons…',
                'mail' => 'Une adresse électronique ressemble à <em>nom@domaine.com</em>.',
                'tel' => 'Ce numéro de téléphone est moche.'
            ];

            // store error msg like $err_msg_liste['mail'] = 'Format de mail incorrect.'
            foreach ($err_list as $e) {
                $to_ajax['errors'][$e] = $err_msg_list[$e];
            }
        }
    }

// AJAX

if(isset($_POST['ajax'])) {
    echo json_encode($to_ajax);
}

else { ?>

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

    <?php // mail msg ?>
    <?php if(isset($to_ajax['mail_msg'])): ?>
        <p class="<?php echo $mail_msg_class; ?>"><strong><?php echo $to_ajax['mail_msg']; ?></strong></p>
    <?php endif; ?>

    <form id="reservation" method="post">

        <!-- choix de visite et date -->
        <fieldset class="fset--visite">
            <legend>La visite</legend>

            <?php // errors management
                if(isset($to_ajax['errors']) && count($to_ajax['errors']) > 0): ?>
                    <ul class="err-msg">
                    <?php
                        echo (isset($to_ajax['errors']['visite']) ?'<li>'.$to_ajax['errors']['visite'].'</li>' : '');
                        echo (isset($to_ajax['errors']['date']) ?'<li>'.$to_ajax['errors']['date'].'</li>' : '');
                        echo (isset($to_ajax['errors']['heure']) ?'<li>'.$to_ajax['errors']['heure'].'</li>' : '');
                        echo (isset($to_ajax['errors']['nb_gens']) ?'<li>'.$to_ajax['errors']['nb_gens'].'</li>' : '');
                    ?>
                    </ul>
            <?php endif; ?>

            <label for="visite">Choix de la visite
                <select
                    <?php echo (isset($to_ajax['errors']['visite']) ? 'class="err-field" ' : '') ?>
                    id="visite" name="visite" id="visite" required autofocus>
                    <?php echo $option_str; ?>
                </select>
            </label>
            <label for="date">Date
                <input  <?php echo (isset($to_ajax['errors']['date']) ? 'class="err-field" ' : '') ?>
                        id="date" name="date" type="date"
                        value="<?php echo (isset($_POST['date']) ? $_POST['date'] : ''); ?>"
                        required>
            </label>
            <label for="heure">Heure souhaitée
                <input  <?php echo (isset($to_ajax['errors']['heure']) ? 'class="err-field" ' : '') ?>
                        id="heure" name="heure" type="time" step="900"
                        value="<?php echo (isset($_POST['heure']) ? $_POST['heure'] : ''); ?>"
                        required>
            </label>
            <label for="nb_gens">Nombre de participants
                <input  <?php echo (isset($to_ajax['errors']['nb_gens']) ? 'class="err-field" ' : '') ?>
                        id="nb_gens" name="nb_gens" type="number" placeholder=""
                        value="<?php echo (isset($_POST['nb_gens']) ? $_POST['nb_gens'] : ''); ?>"
                        min="1" max="40" step="1" value="5" required>
            </label>
        </fieldset>

        <!-- coordonnées -->
        <fieldset class="fset--contact">
            <legend>Vos coordonnées</legend>

            <?php // errors management
                if(isset($to_ajax['errors']) && count($to_ajax['errors']) > 0): ?>
                    <ul class="err-msg">
                    <?php
                        echo (isset($to_ajax['errors']['nom']) ?'<li>'.$to_ajax['errors']['nom'].'</li>' : '');
                        echo (isset($to_ajax['errors']['mail']) ?'<li>'.$to_ajax['errors']['mail'].'</li>' : '');
                        echo (isset($to_ajax['errors']['tel']) ?'<li>'.$to_ajax['errors']['tel'].'</li>' : '');
                    ?>
                    </ul>
            <?php endif; ?>

            <label for="nom">Vos nom et prénom, ou le nom de votre association, école…
                <input  <?php echo (isset($to_ajax['errors']['nom']) ? 'class="err-field" ' : '') ?>
                        id="nom" name="nom" type="text"
                        value="<?php echo (isset($_POST['nom']) ? $_POST['nom'] : ''); ?>"
                        placeholder="Jules César" required>
            </label>

            <label for="mail">Mail
                <input  <?php echo (isset($to_ajax['errors']['mail']) ? 'class="err-field" ' : '') ?>
                        id="mail" name="mail" type="email"
                        value="<?php echo (isset($_POST['mail']) ? $_POST['mail'] : ''); ?>"
                        placeholder="venividi@v.ici" required>
            </label>

            <label for="tel">Tél.
                <input  <?php echo (isset($to_ajax['errors']['tel']) ? 'class="err-field" ' : '') ?>
                        id="tel" name="tel" type="tel"
                        value="<?php echo (isset($_POST['tel']) ? $_POST['tel'] : ''); ?>"
                        placeholder="(+32) (2) 123 45 67" required>
            </label>
            <label for="newsletter">S’inscrire à la lettre d’information.
                <input  id="newsletter" name="newsletter"
                        type="checkbox" <?php echo $newsletter_box; ?>>
            </label>
        </fieldset>

        <button class="button">Envoyer la réservation</button>
    </form>

    <?php if($p_flag === 1): ?>
        <h2><?php the_content(); ?></h2>
    <?php endif; ?>

    </main>

    <?php get_sidebar(); ?>
    <?php get_footer(); ?>

<?php } ?>
