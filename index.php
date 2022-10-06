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

    use App\Model\ConcertModel;
    use App\Model\ArtisteModel;
    use App\Model\SalleModel;
    use App\Entity\Concert;

    $date = date('Y-m-d');

    $concerts = (new ConcertModel())->GetAll(""," WHERE date >= '".$date."' ORDER BY date LIMIT 2");

    $salles = (new SalleModel())->GetAll();

    $artistes = (new ArtisteModel())->GetAll("","LIMIT 5");

    foreach ($concerts as $cle => $concert){
        $concerts[$cle] = new Concert($concert);
    }

        require 'header/header.php';
?>

<div class='flex'>

    <div class='index'>
        <h2>Bienvenue sur Celeste.fr</h2>
        <p>Sur Celeste.fr vous pouvez réserver les concerts de vos artistes favoris</p>
        <ul>
            <li>Artistes :
                <?php foreach ($artistes as $artiste) : ?>
                    <?=$artiste['nom']?>
                <?php endforeach;?>
            </li>
            <li>Salles :
                <?php foreach ($salles as $salle) : ?>
                    <?=$artiste['nom']?>
                <?php endforeach;?>
            </li>
        </ul>
    </div>

    <div>
        <div class='index'>
            <h2>Trouvez tout les <a href='concert'>Concerts</a></h2>
            <div class='flex'>
                <?php require ('App/View/GetAllConcert.php') ?>
            </div>
        </div>
    </div>

</div>




<?php require 'footer/footer.php';?>

