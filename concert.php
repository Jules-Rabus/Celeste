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

    use App\Controller\ControllerConcert;

    $ControllerConcert = new ControllerConcert();

    require 'header/header.php';
?>

<div class='concert flex'>

    <?php if ( !isset($_GET['id_concert'])) : ?>
        <?=$ControllerConcert->GetAllConcert()?>
    <?php else : ?>
      <?=$ControllerConcert->GetFormReservation()?>
    <?php endif ; ?>
    
</div>

<?php require 'footer/footer.php'?>

