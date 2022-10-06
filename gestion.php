<?php

    function chargerClasse($classe)
    {
        $classe=str_replace('\\','/',$classe);
        require $classe . '.php';
    }

    spl_autoload_register('chargerClasse'); //Autoload

    session_start();
    require_once 'fonction.php';
    verification_page();

    use App\Controller\ControllerSalle;
    use App\Controller\ControllerConcert;
    use App\Controller\ControllerReservation;
    use App\Controller\ControllerArtiste;

    if( !empty($_GET['gestion']) && $_GET['gestion'] == 'concert'){
        $ControllerConcert = new ControllerConcert();
    }

    if( !empty($_GET['gestion']) && $_GET['gestion'] == 'salle'){
        $ControllerSalle = new ControllerSalle();
    }

    if( !empty($_GET['gestion']) && $_GET['gestion'] == 'reservation'){
        $ControllerReservation = new ControllerReservation();
    }

    if( !empty($_GET['gestion']) && $_GET['gestion'] == 'artiste'){
        $ControllerArtiste = new ControllerArtiste();
    }
    require 'header/header.php';

?>

    <div class='menu_get'>
        <a href='?gestion=salle'>Salle</a>
        <a href='?gestion=concert'>Concert</a>
        <a href='?gestion=reservation'>Réservation</a>
        <a href='?gestion=artiste'>Artiste</a>
    </div>

        <?php if(!empty($_GET['gestion']) && $_GET['gestion'] == 'concert') : ?>
            <?php $ControllerConcert->Gestion()?>
        <?php endif ; ?>

        <?php if(!empty($_GET['gestion']) && $_GET['gestion'] == 'reservation') : ?>
            <?php $ControllerReservation->Gestion()?>
        <?php endif ; ?>

        <?php if(!empty($_GET['gestion']) && $_GET['gestion'] == 'salle') : ?>
            <?php $ControllerSalle->Gestion()?>
        <?php endif ; ?>

        <?php if(!empty($_GET['gestion']) && $_GET['gestion'] == 'artiste') : ?>
            <?php $ControllerArtiste->Gestion()?>
        <?php endif ; ?>

<?php require 'footer/footer.php';?>
