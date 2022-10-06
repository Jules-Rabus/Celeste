<?php
	require_once 'fonction.php';
?>

<!DOCTYPE html>
<html lang='fr'>

<head>
    <title>Celeste-Projet-Web</title>
    <meta charset='UTF-8'>
    <link rel='icon' href='header/favicon.ico'/>
    <link href='css/css_header.css' rel='stylesheet' type='text/css'>
    <link href='css/css_footer.css' rel='stylesheet' type='text/css'>
    <link href='css/css.css' rel='stylesheet' type='text/css'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <script src='header/menu.js'></script>
</head>

<body>
	<header>
		<div>
            <img id='ouverture_menu' class='image_menu' src='header/menu.svg' alt='ouverture_menu'>
            <a href='<?=lcfirst(nom_page(1))?>'><h1><?=nom_page(0)?></h1></a>
            <?php if(!empty($_SESSION['panier'])) : ?>
                <div>
                <a href='/panier'><img class='image_menu' src='header/basket.svg' alt='panier_menu'></a>
                    <h1><?=count($_SESSION['panier'])?></h1>
                </div>
            <?php endif; ?>
        </div>
		<nav id='ferme' class='menu'>
			<div id='fermeture_menu'><img class='image_menu' src='header/close.svg' alt='fermeture_menu'></div>

				<a href='index'>Accueil</a>

			<?php foreach( page_menu() as $page) : ?>

				<?=$page?>

			<?php endforeach ; ?>
        </nav>
	</header>
<main>

	<?php if( isset($_SESSION['verification_page'])) :?>

		<?php if ($_SESSION['verification_page'] == 1) :?>

			<h2>Vous devez etre connecté pour acceder à cette page </h2>

			<a href='connexion.php'> Page de connexion </a>

		<?php else :?>
		
			<h2>Vous n'avez pas les droits pour cette page</h2>

			<a href='index.php'> Page de connexion </a>

		<?php endif ;?>

		<?php unset($_SESSION['verification_page']) ?>

	<?php endif ; ?>
