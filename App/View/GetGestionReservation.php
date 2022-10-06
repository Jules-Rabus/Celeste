<div class='flex'>
<form id='formulaire_gestion_reservation' method="get">
    <fieldset>
        <legend>Recherche réservation</legend>
        <p>
            <label for='recherche_type'>Type de recherche :</label>
            <select name='recherche_type' id='recherche_type' size='1' required>
                <option value=''>Type de recherche</option>

                <?php if(isset($_GET['recherche_type'])) : ?>

                    <?php if($_GET['recherche_type'] == "Nom") : ?>
                         <option selected>Nom</option>
                    <?php else : ?>
                        <option>Nom</option>
                    <?php endif; ?>

                    <?php if($_GET['recherche_type'] == "Email") : ?>
                         <option selected>Email</option>
                    <?php else : ?>
                        <option>Email</option>
                    <?php endif; ?>

                    <?php if($_GET['recherche_type'] == "Id_Concert") : ?>
                        <option selected>Id_Concert</option>
                    <?php else : ?>
                        <option>Id_Concert</option>
                    <?php endif; ?>

                    <?php if($_GET['recherche_type'] == "Numéro de réservation") : ?>
                        <option selected>Numéro de réservation</option>
                    <?php else : ?>
                        <option>Numéro de réservation</option>
                    <?php endif; ?>

                    <?php if($_GET['recherche_type'] == "Date") : ?>
                        <option selected>Date</option>
                    <?php else : ?>
                        <option>Date</option>
                    <?php endif; ?>

                <?php else : ?>

                    <option>Nom</option>
                    <option>Email</option>
                    <option>Concert</option>
                    <option>Numéro de réservation</option>
                    <option>Date</option>

                <?php endif;?>

            </select>
        </p>
        <p>
            <label for='recherche'>Recherche :</label>
            <input id='recherche' name='recherche' required>
        </p>
        <input name='gestion' value='reservation' hidden required>
        <p class='submit'>
            <input type='submit' value='Rechercher'>
        </p>
    </fieldset>
</form>

        <?php if(!empty($reservations)) : ?>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Concert</th>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Mail</th>
                    <th>Place_or</th>
                    <th>Place_argent</th>
                    <th>Place_bronze</th>
                    <th>Place_fosse</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservations as $reservation) :?>

                    <tr>
                        <td><?=$reservation['reservation']['id']?></td>
                        <td><?=$reservation['concert']->getNom()?></td>
                        <td><?=$reservation['concert']->getDate()?></td>
                        <td><?=$reservation['utilisitateur']['nom']?></td>
                        <td><?=$reservation['utilisitateur']['email']?></td>
                        <td><?=$reservation['reservation']['place_or']?></td>
                        <td><?=$reservation['reservation']['place_argent']?></td>
                        <td><?=$reservation['reservation']['place_bronze']?></td>
                        <td><?=$reservation['reservation']['place_fosse']?></td>
                        <td><?=$reservation['reservation']['statut']?></td>
                        <td>
                            <?php if($reservation['reservation']['statut'] != -1) : ?>
                                <form id='formulaire_gestion_reservation_<?=$reservation['reservation']['id']?>' method='post'>
                                    <input type='number' name='id_reservation' value='<?=$reservation['reservation']['id']?>' hidden required >
                                    <p>
                                        <select name='raison' size='1' required>
                                            <option value=''>Raison</option>
                                            <option>Covid</option>
                                            <option>Indisponibilité artiste</option>
                                            <option>Problème technique</option>
                                            <option>Autre</option>
                                        </select>
                                    </p>
                                    <p class='submit'>
                                        <input type='submit' name='Annuler' value='Annuler'>
                                    </p>
                                </form>
                            <?php else : ?>
                                Cette réservation a été annulé
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php else : ?>
            <h2>Cette recherche n'a pas permi de trouver de réservation</h2>
        <?php endif; ?>

</div>