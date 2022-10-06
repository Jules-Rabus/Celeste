<div class='flex'>
    <?php if(!isset($_GET['action'])) : ?>
        <table>
            <thead>
                <tr>
                    <th>Votre nom</th>
                    <th>Votre email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?=$utilisateur['nom']?></td>
                    <td><?=$utilisateur['email']?></td>
                    <td>
                        <form id='formulaire_gestion_compte' method="get">
                            <input name='compte' value='information' hidden required>
                            <p class='submit'>
                               <input type='Submit' name='action' value='Supprimer'>
                            </p>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php elseif($_GET['action'] == 'Supprimer') : ?>

        <div>
            <h2>Etes vous sur de vouloir supprimer votre compte ?</h2>
            <p>Vous garderez vos reservations en cours.</p>
            <a href='?compte=information'>Annuler</a>
        </div>

        <form id='formulaire_gestion_compte' method="post">
            <fieldset>
                <legend>Suppression du compte</legend>
                <input name='id_utilisateur' value='<?=$utilisateur['id']?>' hidden required>
                <p class='submit'>
                    <input type='Submit' name='action' value='Supprimer'>
                </p>
            </fieldset>
        </form>

    <?php endif;?>
</div>
