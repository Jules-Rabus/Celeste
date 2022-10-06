<div class='flex'>
    <form id='formulaire_ajout_artiste' method='post'>
        <fieldset>
            <legend>Ajouter un artiste</legend>
            <p>
                <label for='nom_artiste'>Nom de l'artiste :</label>
                <input id='nom_artiste' type='text'  name='nom_artiste' placeholder="Nom de l'artiste">
            </p>
            <p>
                <label for='style_artiste'>Style de l'artiste</label>
                <input id='style_artiste' type='text' maxlength='50' name='style_artiste' placeholder="Style de l'artiste">
            </p>
            <p class='submit'>
                <input type='submit' value='Ajouter'>
            </p>
        </fieldset>
    </form>
    <?php if(isset($code_erreur)) : ?>

        <?php if($code_erreur === 1) : ?>
            <h2>Le style de l'artiste est trop long</h2>
        <?php elseif($code_erreur === 2) : ?>
            <h2>Le nom de l'artiste est trop long</h2>
        <?php endif; ?>

    <?php endif; ?>


    <?php if(!empty($artistes)) : ?>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Style</th>
                    <th>Concert à venir</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

        <?php foreach($artistes as $artiste) :?>
            <tr>
                <td><?=$artiste['id']?></td>
                <td><?=$artiste['nom']?></td>
                <td><?=$artiste['style']?></td>
                <td><?=$artiste['date_futur']?></td>
                <td>
                    <?php if(!$artiste['date_futur']) : ?>
                    <form id='formulaire_gestion_artiste_<?=$artiste['id']?>' method='post'>
                        <input type='number' name='id_artiste' value='<?=$artiste['id']?>' hidden required >
                        <p class='submit' >
                            <input type='submit' name='Supprimer' value='Supprimer'>
                        </p>
                    </form>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif;?>

</div>