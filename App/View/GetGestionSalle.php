<div class='flex'>
<form id='formulaire_ajout_salle>' method='post' >
        <fieldset>
            <legend>Ajouter une salle</legend>
            <p>
                <label for='salle_nom'>Nom de la salle :</label>
                <input type='text' id='salle_nom' name='salle_nom' placeholder='Nom de la salle' maxlength='50' required>
            </p>
            <p>
                <label for='salle_place_or'>Nombre de place or :</label>
                <input type='number' id='salle_place_or' name='salle_place_or' step='1' min='0' placeholder='Nombre de place or' required>
            </p>
            <p>
                <label for='salle_place_argent'>Nombre de place argent :</label>
                <input type='number' id='salle_place_argent' name='salle_place_argent' step='1' min='0' placeholder='Nombre de place argent' required>
            </p>
            <p>
                <label for='salle_place_bronze'>Nombre de place bronze :</label>
                <input type='number' id='salle_place_bronze' name='salle_place_bronze' step='1' min='0' placeholder='Nombre de place bronze' required>
            </p>
            <p>
                <label for='salle_place_fosse'>Nombre de place en fosse :</label>
                <input type='number' id='salle_place_fosse' name='salle_place_fosse' step='1' min='0' placeholder='Nombre de place en fosse' required>
            </p>
            <p class='submit'>
                <input type='submit' value='Ajouter la salle'/>
            </p>
        </fieldset>
    </form>
</div>

<div class='gestion'>
    <?php foreach($salles as $salle) :?>

        <article class='gestion_salle' id='salle_<?=$salle['salle']->getId()?>'>
            <div>
                <h2><?=$salle['salle']->getNom()?></h2>
                <p>Place or : <?=$salle['salle']->getPlace_or()?></p>
                <p>Place argent : <?=$salle['salle']->getPlace_Argent()?></p>
                <p>Place bronze : <?=$salle['salle']->getPlace_Bronze()?></p>
                <p>Place fosse : <?=$salle['salle']->getPlace_Fosse()?></p>
                <p>Place : <?=$salle['salle']->GetPlaces()?></p>
            </div>
            <div>
                <?php if(!empty($salle['concerts'])) : ?>
                    <?php foreach( $salle['concerts'] as $concert) :?>
                        <a  href='gestion_concert#formulaire_gestion_concert_<?=$concert['concert']->getId()?>' ><?=$concert['concert']->getNom()?> : <?=$concert['concert']->getDate()?></a>
                        <div class="gestion_concert_pourcentage">
                            <div style="min-width: fit-content ;width:<?=$concert['places']['places_reserve']/$concert['places']['places']*100?>%;">
                                <p><?=round($concert['places']['places_reserve']/$concert['places']['places'],2)*100?>%</p>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </article>

    <?php endforeach ; ?>
 </div>
