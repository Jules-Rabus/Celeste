<form id='formulaire_inscription' method='post'> <!-- sans action pour poster sur la meme page -->
        <fieldset>
            <legend>Inscription</legend>
            <p>
                <label for='nom'>Nom :</label>
                <input type='text' id='nom' name='nom' maxlength='30' pattern='[a-zA-Z]{1,30}' title='Rentrez uniquement des lettres' autocomplete='family-name' placeholder='Entrez votre nom'  required>
            </p>
            <p>
                <label for='email'>Email :</label>
                <input type='email' id='email' name='email' minlength='5' maxlength='50' autocomplete='email' placeholder='Entrez votre mail'  required>
            </p>
            <p>
                <label for='mdp1'>Mot de passe :</label>
                <input type='password' id='mdp1' name='mdp1' minlength='8' maxlength='30' pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,30})$' title='Au moins : 8 caractères en tout, 1 chiffre, 1 masjuscule, 1 minuscule, un caractère spéciale: - + ! * $ @ % _ ' autocomplete='new-password' placeholder='Entrez votre mot de passe'   required>
            </p>
            <p>
                <label for='mdp2'>Confirmation du mot de passe :</label>
                <input type='password' id='mdp2' name='mdp2' minlength='8' maxlength='30' pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,30})$' title='Au moins : 8 caractères en tout, 1 chiffre, 1 masjuscule, 1 minuscule, un caractère spéciale: - + ! * $ @ % _ ' autocomplete='new-password' placeholder='Confirmez votre mot de passe'   required>
            <p class='submit'>
                <input name='numform' type='number' value='1' hidden required>
                <input type='submit' value="S'inscrire"/>
            </p>
        </fieldset>
    </form>

    <?php if(isset($code_inscription)) : ?>

        <?php switch($code_inscription) : case 0 : ?>
                <h2>Inscription enregistré</h2>
             <?php break; ?>

            <?php case 1 : ?>
                <h2>Inscription échoué </h2>
            <?php break; ?>

            <?php case 2 : ?>
                <h2>L'email est déja enregistré</h2>
            <?php break; ?>

            <?php case 3: ?>
                <h2>L'email doit etre différent de votre nom ou de votre mot de passe</h2>
            <?php break; ?>

            <?php case 4: ?>
                <h2>Les mots de passe ne sont pas identiques</h2>
            <?php break; ?>

            <?php case 5: ?>
                <h2>Le mots de passe ne correspond pas au critères</h2>
            <?php break; ?>

            <?php case 6: ?>
                <h2>Le mail est invalide, trop long ou trop court</h2>
            <?php break; ?>

            <?php case 7: ?>
                 <h2>Il n'est pas possible de se créer un compte si vous êtes connecté</h2>
            <?php break; ?>

            <?php case 8: ?>
                <h2>Formulaire incomplet</h2>
            <?php break; ?>

        <?php endswitch; ?>

    <?php endif ; ?>