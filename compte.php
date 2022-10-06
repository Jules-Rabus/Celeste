<?php

    function chargerClasse($classe)
    {
        $classe=str_replace('\\','/',$classe);
        require $classe . '.php';
    }

    spl_autoload_register('chargerClasse'); //Autoload

    use App\Controller\ControllerUtilisateur;

    session_start();
    require_once 'fonction.php';
    verification_page();

    $ControllerUtilisateur = new ControllerUtilisateur();

    require 'header/header.php';
?>

    <div class='menu_get'>
        <a href='?compte=commande'>Mes commandes</a>
        <a href='?compte=information'>Mes informations</a>
        <a href='?compte=mdp'>Changement Mot de Passe</a>
    </div>

    <div class='flex'>
        <?php if(!empty($_GET['compte']) && $_GET['compte'] == 'commande') : ?>
            <?php $ControllerUtilisateur->GetCommandes()?>
        <?php endif ; ?>

        <?php if(!empty($_GET['compte']) && $_GET['compte'] == 'information') : ?>
            <?php $ControllerUtilisateur->GetFormInformation()?>
        <?php endif ; ?>

        <?php if(!empty($_GET['compte']) && $_GET['compte'] == 'mdp') : ?>
            <?php $ControllerUtilisateur->GetFormMdpChangement()?>
        <?php endif ; ?>
    </div>

<?php require 'footer/footer.php'?>
