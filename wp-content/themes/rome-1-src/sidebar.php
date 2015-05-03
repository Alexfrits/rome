<aside>
    <?php

    /*
    *   **TODO**
    *   transformer ce bout de code
    *   en fonction réutilisable ailleurs
    */

    // récupération des catégories (taxonomies) custom (paramètre '_builtin' => false)

    /* this syntax doesn't work with PHP < 5.4 */

        // $args = [
        //     'public' => true,
        //     '_builtin' => false
        // ];

    /* this works with PHP 5.4 */

        $args = array(
            'public' => true,
            '_builtin' => false
        );


    $output = 'name';
    $tax = get_taxonomies($args, $output);

    foreach ($tax as $t => $v) {
        if ($t === 'visites' || $t === 'infospratiques') {
            // affiche le titre de la taxo
            echo '<h2>' . $v->labels->name . '</h2>';
            // crée la liste des items de la catégorie
            echo '<ul>';
            $args = array(
                'taxonomy'      => $v->name,
                'hide_empty'    => 1
            );
            $cat = get_categories($args);

             foreach ($cat as $i => $u) {
                echo '<li><a href="' . home_url() . '/' . $v->name . '/' . $u->slug . '">' . $u->name . '</a></li>';
             };
            echo '</ul>';
        }
    }
    ?>
    <!-- Liens utiles en dur car on ne l'a toujours pas intégré dans l'admin-->
    <h2>Liens Utiles</h2>
    <ul>
        <li><a href="#"> sites officiels</a></li>
        <li><a href="#">tourisme</a></li>
    </ul>

</aside>