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
<div class ='flex'>

    <div>
        <?php $ControllerUtilisateur->GetFormConnexion(); ?>
    </div>

    <div>
    <?php if(empty($_SESSION['id_utilisateur'])) : ?>

        <?php $ControllerUtilisateur->GetFormInscription(); ?>

    <?php else : ?>
        <a href='/compte'><h2>Compte</h2></a>
    <?php endif; ?>
    </div>

</div>

<?php require 'footer/footer.php' ?>