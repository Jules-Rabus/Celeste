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
    
    use App\Controller\ControllerUtilisateur;

    $ControllerUtilisateur = new ControllerUtilisateur();

    require 'header/header.php';

?>
    <div class='flex'>
        <?=$ControllerUtilisateur->GetFormMdpOublie()?>
    </div>

<?php require 'footer/footer.php'?>

