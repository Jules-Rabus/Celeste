<?php
$sujet = "Confirmation de votre inscription";
require 'MailHeader.php';
require 'MailFooter.php';

$message_html = "
    <article class='message'>
        <h1>" . $sujet . "</h1>

        <p>" . message_heure()['debut'] . ", Madame / Monsieur " . $nom . ", <br><br>
            Votre inscription a bien été enregistré. <br><br>
            Si vous n'êtes pas à l'origine de cette inscription veuillez nous contacter. <br><br>
        </p>

    </article>"  . $footer;