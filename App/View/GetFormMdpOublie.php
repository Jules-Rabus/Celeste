<script src="https://www.google.com/recaptcha/api.js?render=6Lfud0EaAAAAAFm5ya2d9v1zeGDu2D7QFBZsKXS6"></script>
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute('xxxxx', { action: 'contact' }).then(function (token) { // clé effacé
            document.querySelector('#recaptchaResponse').value = token;
        });
    });
</script>

    <div>

        <?php if($erreur != 8) : ?>

            <?php if($erreur != 7) : ?>

                <?php if($_SESSION['numform'] == 0) : ?>

                    <div>
                        <form id="formulaire_mdp_oublie_information" method='post'>
                            <fieldset>
                                <legend>Mot de passe oublié</legend>
                                <p>
                                    <label for='email'>Email :</label>
                                    <input type='email' id='email' name='email'  minlength='5' maxlength='50' autocomplete='email' placeholder='Entrez votre mail' required>
                                </p>
                                <p>
                                    <label for='nom'>Nom :</label>
                                    <input type='text' id='nom' name='nom' maxlength='30' pattern='[a-zA-Z]{1,30}' title='Rentrez uniquement des lettres' autocomplete='family-name' placeholder='Entrez votre nom'  required>
                                </p>
                                <p class='submit'>
                                    <input type='submit' value='Valider'/>
                                </p>
                            </fieldset>
                        </form>
                    </div>

                    <?php if($erreur == 1 ) : ?>
                        <h2>Il n'y a aucun compte qui correspond, essaie restant : <?=$_SESSION['nbr_erreur']?></h2>
                    <?php endif; ?>

                <?php endif; ?>


                <?php if($_SESSION['numform'] == 1 && $erreur != 3) : ?>

                    <?php if($erreur != 3) : ?>
                        <h2>Pour obtenir le code de récupération, veuillez consulter vos mail. </h2>
                            <div>
                                <form id='formulaire_mdp_oublie_code' method='post'>
                                    <fieldset>
                                        <legend>Mot de passe oublié</legend>
                                            <p>
                                                <label for='code'>Entrer le code de récupération :</label>
                                                <input type='number' id='code' min='100000' max='999999' step='1' name='code' placeholder='Entrez le code de récupération'  required>
                                            </p>
                                            <p class='submit'>
                                                 <input type='submit' value='Valider' />
                                            </p>
                                            <input type='hidden' name='recaptcha_response' id='recaptchaResponse'>
                                    </fieldset>
                                </form>
                            </div>
                        <?php endif; ?>

                    <?php if($erreur == 1) : ?>
                        <h2>Le code de vérification est incorrecte, essaie restant : <?=$_SESSION['nbr_erreur']?></h2>
                    <?php endif; ?>

                    <?php if($erreur == 2) : ?>
                        <h2>Le captacha n'est pas correcte</h2>
                    <?php endif; ?>

                    <?php if($erreur == 3) : ?>
                        <h2>Vous avez mis plus de 10 minutes pour récupérer le code</h2>
                    <?php endif; ?>


                <?php endif; ?>

                <?php if ($_SESSION['numform'] == 2) : ?>

                    <div>
                        <form id="formulaire_mdp_oublie_saisie_mdp" action='motdepasseoublie.php' method='post'>
                            <p>
                                <label for='mdp1'>Entrer  votre nouveau mot de passe :</label>
                                <input type='password' name='mdp1'  minlength='8' maxlength='30' pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,30})$' title='Au moins : 8 caractères en tout, 1 chiffre, 1 masjuscule, 1 minuscule, un caractère spéciale: - + ! * $ @ % _ ' title='Au moins : 8 caractères en tout, 1 chiffres, 1 masjuscule, 1 minuscule ' autocomplete='new-password' placeholder='Entrez votre mot de passe'  required>
                            </p>
                            <p>
                                <label for='mdp2'>Confirmer votre nouveau mot de passe :</label>
                                <input type='password' name='mdp2'  minlength='8' maxlength='30' pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,30})$' title='Au moins : 8 caractères en tout, 1 chiffre, 1 masjuscule, 1 minuscule, un caractère spéciale: - + ! * $ @ % _ ' autocomplete='new-password' placeholder='Confirmez votre mot de passe'  required>
                            </p>
                            <p class='submit'>
                                <input type='submit' value='Valider' />
                            </p>
                        </form>
                    </div>

                    <?php if($erreur == 1 ) : ?>
                        <h2>Le mot de passe est identique à votre ancien mot de passe</h2>
                    <?php endif; ?>

                    <?php if($erreur == 2 ) : ?>
                        <h2>Il y a un problème technique</h2>
                    <?php endif; ?>

                    <?php if($erreur == 3 ) : ?>
                        <h2>Veuillez entrer un mot de passe différent de votre email</h2>
                    <?php endif; ?>

                    <?php if($erreur == 4 ) : ?>
                        <h2>Votre mot de passe doit faire entre 8 et 30 caractères</h2>
                    <?php endif; ?>

                    <?php if($erreur == 5 ) : ?>
                        <h2>Le formulaire est incomplet</h2>
                    <?php endif; ?>

                <?php endif; ?>

                <?php if($_SESSION['numform'] != 3 && isset($_SESSION['nbr_erreur'])) : ?>

                    <?php if($_SESSION['nbr_erreur'] > 0) : ?>
                        <form id='formulaire_mdp_oublie' method='post'>
                                <input name='reset' value='1' hidden required>
                            <p class='submit'>
                                <input type='submit' value='Recommencer' />
                            </p>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if($_SESSION['numform'] == 3) : ?>
                    <h2>Votre mot de passe a bien été changé, un mail de confirmation vous a été envoyé</h2>
                <?php endif; ?>

            <?php else : ?>
                    <h2>Vous avez dépasse le nombre d'essaie possible</h2>
            <?php endif; ?>

        <?php else : ?>

            <h2>Veuillez vous déconnecter pour utiliser le mot de passe oublié</h2>
            <?=$this->GetFormConnexion()?>

        <?php endif; ?>
    </div>

