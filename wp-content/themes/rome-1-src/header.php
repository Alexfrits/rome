<!doctype html>
<html <?php language_attributes(); ?>>
<?php include('dev-helpers.php'); ?>
<head>
	<meta charset="utf-8">
	<title><?php wp_title(); ?></title>
	<?php wp_head(); // isn't it too heavy? ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div id="wrapper">
<header>
	<h1><a href="<?php echo home_url();?>">Visiter Rome</a></h1>
	<nav>
    	<ul>
			<li><a href="">Accueil</a> </li>
			<li><a href="">Visites</a> </li>
			<li><a href="">Guides</a></li>
			<li><a href="<?php echo home_url(); ?>/tarifs-et-reservations/">Tarifs et réservations</a> </li>
	    </ul>
	</nav>
    <div id="deco"></div>
</header>