    <?php if(!isset($code_changement) || isset($code_changement) && $code_changement) : ?>

        <form id='formulaire_changement_mdp' method='post'>
            <fieldset>
                <legend>Changement mots de passe</legend>
                <p>
                    <label for='mdp_actuelle'>Mot de passe actuel:</label>
                    <input id='mdp_actuelle' type='password' name='mdp_actuelle' minlength='8' maxlength='30' pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,30})$' title='Au moins : 8 caractères en tout, 1 chiffre, 1 masjuscule, 1 minuscule, un caractère spéciale: - + ! * $ @ % _ ' autocomplete='current-password' placeholder='Entrez votre mot de passe actuelle' required>
                </p>
                <p>
                    <label for='mdp_nouveau'>Nouveau Mot de passe :</label>
                    <input id='mdp_nouveau' type='password' name='mdp_nouveau' minlength='8' maxlength='30' pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,30})$' title='Au moins : 8 caractères en tout, 1 chiffre, 1 masjuscule, 1 minuscule, un caractère spéciale: - + ! * $ @ % _ ' autocomplete='new-password' placeholder='Entrez votre nouveau mot de passe' required>
                </p>
                <p>
                    <label for='mdp_nouveau_confirmation'>Confirmation nouveau Mot de passe:</label>
                    <input id='mdp_nouveau_confirmation' type='password' name='mdp_nouveau_confirmation' minlength='8' maxlength='30' pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,30})$' title='Au moins : 8 caractères en tout, 1 chiffre, 1 masjuscule, 1 minuscule, un caractère spéciale: - + ! * $ @ % _ ' autocomplete='new-password' placeholder='Confirmer votre nouveau mot de passe' required>
                </p>
                <p class='submit'>
                    <input type='submit' value='Changer mot de passe'/>
                </p>
            </fieldset>
        </form>

    <?php endif; ?>

    <?php if(isset($code_changement)) : ?>

        <?php switch($code_changement) : case 0 : ?>
            <h2>Votre mot de passe a bien été changé</h2>
            <?php break; ?>

        <?php case 1 : ?>
            <h2>Changement échoué </h2>
            <?php break; ?>

        <?php case 2 : ?>
            <h2>Votre mot de passe est identique à celui actuelle</h2>
            <?php break; ?>

        <?php case 3: ?>
            <h2>Le mot de passe doit etre différent de votre nom ou de votre mail</h2>
            <?php break; ?>

        <?php case 4: ?>
            <h2>Les mots de passe ne sont pas identiques</h2>
            <?php break; ?>

        <?php case 5: ?>
            <h2>Le mots de passe ne correspond pas au critères</h2>
            <?php break; ?>

        <?php endswitch; ?>

    <?php endif;?>

