
    <?php if(empty($_GET['id_concert'])) : ?>

    <div class='flex'>
        <form id='formulaire_ajout_concert>' method='post' enctype="multipart/form-data" >
            <fieldset>
                <legend>Ajout d'un concert</legend>
                <p>
                    <label for='concert_nom'>Nom du concert :</label>
                    <input type='text' id='concert_nom' name='concert_nom' placeholder='Nom du concert' maxlength='50' required>
                </p>
                <p>
                    <label for='concert_date'>Date du concert :</label>
                    <input type='date' id='concert_date' name='concert_date' min='<?=$date_concert_ajout?>' value='<?=$date_concert_ajout?>' required>
                </p>
                <p>
                    <select name='concert_salle' id='concert_salle' size='1' required>
                            <option value=''>Salle</option>
                        <?php foreach($salles as $salle) : ?>
                            <option value='<?=$salle['id']?>'><?=$salle['nom']?></option>
                        <?php endforeach ; ?>
                    </select>
                </p>
                <p>
                    <select name='concert_artiste' id='concert_artiste' size='1' required>
                            <option value=''>Artiste</option>
                        <?php foreach($artistes as $artiste) : ?>
                            <option value='<?=$artiste['id']?>'><?=$artiste['nom']?></option>
                        <?php endforeach ; ?>
                    </select>
                </p>
                <p>
                    <label for="concert_image">Image :</label>
                    <input type="file" id="concert_image" name="concert_image" accept=".jpg, .jpeg, .png" required/>
                </p>
                <p class='submit'>
                    <input type='submit' value='Ajouter concert'/>
                </p>
            </fieldset>
        </form>
        <?php if(isset($erreur)) : ?>
            <h2>Il y a un problème avec le formulaire</h2>
        <?php endif; ?>
    </div>

    <div class='flex, gestion'>

        <?php foreach($concerts as $concert) :?>
            <article>
                <img src='images/<?=$concert['concert']->getImage()?>' loading='lazy' alt='Image<?=$concert['concert']->getNom()?>' >
                <div>
                    <h2><?=$concert['concert']->getNom()?></h2>
                    <div>
                        <div>
                            <p><?=$concert['concert']->getDate()?></p>
                            <p><?=$concert['concert']->getArtiste()->getNom()?></p>
                            <p><?=$concert['concert']->getArtiste()->getStyle()?></p>
                            <p><?=$concert['concert']->getSalle()->getNom()?></p>
                        </div>
                        <div>
                            <p>Place or : <?=$concert['places']['place_or_reserve']?> / <?=$concert['places']['place_or'] ?></p>
                            <p>Place argent : <?=$concert['places']['place_argent_reserve']?> / <?=$concert['places']['place_argent'] ?></p>
                            <p>Place bronze : <?=$concert['places']['place_bronze_reserve']?> / <?=$concert['places']['place_bronze'] ?></p>
                            <p>Place fosse : <?=$concert['places']['place_fosse_reserve']?> / <?=$concert['places']['place_fosse'] ?></p>
                        </div>
                    </div>
                    <div>

                        <?php if(!$concert['concert']->getStatut()) : ?>
                            <div class='formulaire_gestion_concert'>
                                <form id='formulaire_gestion_concert_<?=$concert['concert']->getId()?>' method='get'>
                                    <p>
                                        <input type='checkbox' id='Decaler_<?=$concert['concert']->getId()?>' name='decaler'>
                                        <label for='Decaler_<?=$concert['concert']->getId()?>'>Décaler</label>
                                    </p>
                                    <p>
                                        <input type='checkbox' id='Annuler_<?=$concert['concert']->getId()?>' name='annuler'>
                                        <label for='Annuler_<?=$concert['concert']->getId()?>'>Annuler</label>
                                    </p>
                                    <input name='gestion' value='concert' hidden required>
                                    <input name='id_concert' type='number' value='<?=$concert['concert']->getId()?>' hidden required>
                                    <p class='submit'>
                                        <input type='submit' value='Confirmer'/>
                                    </p>
                                </form>
                            </div>
                        <?php else : ?>
                            <h2> Ce concert a été annulé</h2>
                        <?php endif; ?>
                    </div>
                    <div class="gestion_concert_pourcentage">
                        <div style="min-width: fit-content; width:<?=round($concert['places']['places_reserve']/$concert['places']['places'],2)*100?>%">
                            <p><?=round($concert['places']['places_reserve']/$concert['places']['places'],2)*100?>%</p>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach;?>

    </div>

    <?php else :?>

    <div class='flex, gestion'>

        <?php if(!empty($concert)) :?>

            <article>
                <img src='images/<?=$concert['concert']->getImage()?>' loading='lazy' alt='Image<?=$concert['concert']->getNom()?>' >
                <div>
                    <h2><?=$concert['concert']->getNom()?></h2>
                    <div>
                        <div>
                            <p><?=$concert['concert']->getDate()?></p>
                            <p><?=$concert['concert']->getArtiste()->getNom()?></p>
                            <p><?=$concert['concert']->getArtiste()->getStyle()?></p>
                            <p><?=$concert['concert']->getSalle()->getNom()?></p>
                        </div>
                        <div>
                            <p>Place or : <?=$concert['places']['place_or_reserve']?> / <?=$concert['places']['place_or'] ?></p>
                            <p>Place argent : <?=$concert['places']['place_argent_reserve']?> / <?=$concert['places']['place_argent'] ?></p>
                            <p>Place bronze : <?=$concert['places']['place_bronze_reserve']?> / <?=$concert['places']['place_bronze'] ?></p>
                            <p>Place fosse : <?=$concert['places']['place_fosse_reserve']?> / <?=$concert['places']['place_fosse'] ?></p>
                        </div>
                    </div>
                    <div>
                        <div class='formulaire_gestion_concert'>
                            <form id='formulaire_gestion_concert_<?=$id_concert?>' method='post'>

                                <?php if(isset($_GET['decaler'])) : ?>
                                    <p>
                                        <input type='checkbox' id='Decaler_<?=$id_concert?>' name='decaler'>
                                        <label for='Decaler_<?=$id_concert?>'>Décaler</label>
                                    </p>
                                    <p>
                                        <select name="date_decalage" required>
                                                <option value=''>Date</option>
                                            <?php foreach($date_decalage_possible as $date) : ?>
                                                <option><?=$date?></option>
                                            <?php endforeach ; ?>
                                        </select>
                                    </p>

                                <?php elseif(isset($_GET['annuler'])) : ?>
                                    <p>
                                        <input type='checkbox' id='Annuler_<?=$id_concert?>' name='annuler'>
                                        <label for='Annuler_<?=$id_concert?>'>Annuler</label>
                                    </p>

                                <?php endif ; ?>

                                <p>
                                    <select name='raison' size='1' required>
                                        <option value=''>Raison</option>
                                        <option>Covid</option>
                                        <option>Indisponibilité artiste</option>
                                        <option>Problème technique</option>
                                        <option>Autre</option>
                                    </select>
                                </p>

                                <input name='id_concert' type='number' value='<?=$id_concert?>' hidden required>
                                <p class='submit'>
                                    <input type='submit' value='Confirmer'>
                                </p>
                            </form>
                        </div>
                    </div>
                    <div class="gestion_concert_pourcentage">
                        <div style="min-width: fit-content; width:<?=round($concert['places']['places_reserve']/$concert['places']['places'],2)*100?>%;">
                            <p><?=round($concert['places']['places_reserve']/$concert['places']['places'],2)*100?>%</p>
                        </div>
                    </div>
                </div>

                <?php if(isset($_GET['decaler'])) : ?>
                    <div>
                        <h2>Si décalage :</h2>
                        <p>Nombre de mail à envoyer : <?=$impact['mail']?> </p>
                        <p>Nombre de client impacté : <?=$impact['client']?> </p>
                    </div>

                <?php elseif(isset($_GET['annuler'])) : ?>
                    <div>
                        <h2>Si annulation :</h2>
                        <p>Manque à gagner : <?=$concert['places']['perte']?> €</p>
                        <p>Nombre de mail à envoyer : <?=$impact['mail']?> </p>
                        <p>Nombre de client impacté : <?=$impact['client']?> </p>
                    </div>
                <?php endif; ?>

            </article>

        <?php elseif(isset($concert_annule)) : ?>

            <?php if($concert_annule) : ?>
                <h2>Ce concert a été annulé</h2>
            <?php else : ?>
                <h2>Concert inexistant</h2>
            <?php endif; ?>
        <?php endif; ?>

    <?php endif; ?>
    </div>
