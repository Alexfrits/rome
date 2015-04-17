<aside>
    <h2>Nos visites</h2>
    <ul>

<?php

// récupération des catégories (taxonomies) de visite

$args = [
    'taxonomy'      => 'visites',
    'hide_empty'    => 1
];
$visites = get_categories($args); ?>

<?php foreach ($visites as $i => $v): ?>
    <li><a href=<?php echo '"'.home_url().'/visites/'.$v->slug.'">'.$v->name; ?></a></li>
<?php endforeach; ?>

    </ul>
    <h2>Infos Pratiques</h2>
    <ul>
        <li><a href="pages/musee.html">Musées</a></li>
        <li><a href="#">Transports</a></li>
        <li><a href="#">Shopping</a></li>
        <li><a href="#">Manger</a></li>
        <li><a href="#">Dormir</a></li>
    </ul>
    <h2>Liens Utiles</h2>
    <ul>
        <li><a href="#"> sites officiels</a></li>
        <li><a href="#">tourisme</a></li>
    </ul>
</aside>