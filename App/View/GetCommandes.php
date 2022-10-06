
<div class='flex'>

    <?php if(!empty($_GET['compte']) && $_GET['compte'] == 'commande' && !empty($commandes)) : ?>

        <div>
                <h2>À venir : </h2>
            <div class='concert flex'>

                <?php foreach($commandes as $id => $commande) :?>
                    <?php if ($date <= $commande['concert']->getDate()) :?>

                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Numéro de réservation</th>
                                    <th>Nom du concert</th>
                                    <th>Date du concert</th>
                                    <th>Place_or</th>
                                    <th>Place_argent</th>
                                    <th>Place_bronze</th>
                                    <th>Place_fosse</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td><img class='images' src='images/<?=$commande['concert']->getImage()?>'></td>
                                    <td><?=$id?></td>
                                    <td><?=$commande['concert']->getNom()?></td>
                                    <td><?=$commande['concert']->getDate()?></td>
                                    <td><?=$commande['reservation']['place_or']?></td>
                                    <td><?=$commande['reservation']['place_argent']?></td>
                                    <td><?=$commande['reservation']['place_bronze']?></td>
                                    <td><?=$commande['reservation']['place_fosse']?></td>
                                    <td>

                                        <?php if(!$commande['reservation']['statut']) : ?>
                                        <form id='formulaire_reservation_annulation_<?=$id?>' method='post'>
                                            <input type='number' name='id_reservation' value='<?=$id?>' hidden required>
                                            <p class='submit'>
                                                <input type='submit' name='Annuler' value='Annuler'>
                                            </p>
                                        </form>
                                        <?php else : ?>
                                            Cette réservation a été annulé
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    <?php endif ; ?>
                <?php endforeach ; ?>

            </div>
        </div>

        <div>

            <h2>Passé : </h2>

            <div class='concert flex'>

                <?php foreach($commandes as $id => $commande) : ?>

                    <?php if ( $date > $commande['concert']->getDate()) : ?>

                        <table>
                            <thead>
                            <tr>
                                <td>Image</td>
                                <td>Numéro de réservation</td>
                                <td>Nom du concert</td>
                                <td>Date du concert</td>
                                <td>Place_or</td>
                                <td>Place_argent</td>
                                <td>Place_bronze</td>
                                <td>Place_fosse</td>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td><img class='images' src='images/<?=$commande['concert']->getImage()?>'</td>
                                <td><?=$id?></td>
                                <td><?=$commande['concert']->getNom()?></td>
                                <td><?=$commande['concert']->getDate()?></td>
                                <td><?=$commande['reservation']['place_or']?></td>
                                <td><?=$commande['reservation']['place_argent']?></td>
                                <td><?=$commande['reservation']['place_bronze']?></td>
                                <td><?=$commande['reservation']['place_fosse']?></td>
                            </tr>
                            </tbody>

                        </table>

                    <?php endif ; ?>
                <?php endforeach ; ?>

            </div>
        </div>

    <?php else : ?>
        <h2> Vous n'avez aucune commande</h2>
    <?php endif; ?>

    <?php if(isset($code_erreur)) : ?>
        <?php if($code_erreur === 1) : ?>
            <h2>Vous devez etre connecté pour acceder à vos commandes</h2>
        <?php endif; ?>
    <?php endif; ?>

</div>