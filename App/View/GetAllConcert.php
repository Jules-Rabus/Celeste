
<?php foreach($concerts as $concert) : ?>
    <article>
        <p><img src='images/<?=$concert->getImage()?>' loading='lazy' alt='Image<?=$concert->getNom()?>' /></p>
        <h2><?=$concert->getArtiste()->getNom()?> : <?=$concert->getDate()?></h2>
        <?php if($concert->getPlaces()['prix']) : ?>
            <h3>A partir de <?=$concert->getPlaces()['prix']?> €</h3>
            <p><a href='concert?id_concert=<?=$concert->getId()?>'>Réserver</a></p>
        <?php else : ?>
            <p><a href='concert?id_concert=<?=$concert->getId()?>'>Consulter</a></p>
        <?php endif; ?>
    </article>
<?php endforeach ;?>