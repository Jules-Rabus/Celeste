
<?php if ( !isset($_SESSION['id_utilisateur'])) : ?>

    <form id='formulaire_connexion' method='post'> <!-- sans action pour poster sur la meme page -->
        <fieldset>
            <legend>Connexion</legend>
            <p>
                <label for='email_connexion'>Email :</label>
                <input id='email_connexion' type='email' name='email'  minlength='5' maxlength='50' autocomplete='email' placeholder='Entrez votre mail' required>
            </p>
            <p>
                <label for='mdp_connexion' >Mot de passe :</label>
                <input id='mdp_connexion' type='password' name='mdp' minlength='8' maxlength='30' pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,30})$' title='Au moins : 8 caractères en tout, 1 chiffre, 1 masjuscule, 1 minuscule, un caractère spéciale: - + ! * $ @ % _ ' autocomplete='current-password' placeholder='Entrez votre mot de passe' required>
            </p>
            <p class='submit'>
                <input name='numform' type='number' value='2' hidden required>
                <input type='submit' value='Se connecter'/>
            </p>
        </fieldset>
    </form>

    <a class='lien' href='motdepasseoublie.php'><h2>Mot de passe oublié</h2></a>

<?php else : ?>

    <form id='formulaire_deconnexion' method='post'>
        <fieldset>
            <legend>Déconnexion</legend>
            <p class='submit'>
                <input name='numform' type='number' value='3' hidden required>
                <input type='submit' value='Déconnexion' name='reset' />
            </p>
        </fieldset>
    </form>

<?php endif ; ?>

<?php if(isset($code_connexion)) : ?>

    <?php if($code_connexion == 0) : ?>
        <h2>Connexion réussi</h2>
    <?php endif ; ?>

    <?php if($code_connexion == 1) : ?>
        <h2>L'identifiant ou le mot de passe est incorrecte</h2>
    <?php endif ; ?>

<?php endif ; ?>