<?php

$expediteur = 'Celeste@sav.fr';
$entete = array("From" => $expediteur, "Reply-To" => $expediteur, "X-Mailer" => "PHP/" . phpversion(), "MIME-Version" => "1.0", "Content-type" => " text/html; charset=charset=iso-8859-1");

$header = "
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta http-equiv='Content-Type' content='text/html' charset='utf-8' />
        <title>" . $sujet . " Celeste.fr</title>
        <style>
            body{
                font-family: Calibri;
                margin: 1rem;                
                padding: 3rem;
                color: black;
                text-align: center;
            }
            
            .message{
                border-style: solid;
                border-color: grey;
            }
            
            article{
                text-align: center;
            }

            article h1{
                font-size: 2rem;
            }
            
            article p{
                font-size: 1.4rem;
                margin: 1rem;
                text-decoration: none;
            }
            
            footer div{
                background-color: darkred;
                font-size: 1.5rem;
                 padding: 0.5rem;
                 display: inline;
                 text-align: center;'
            }
            
            footer a{
                padding: 1rem;
                color: white;
                text-decoration: none;
            }
            
            footer p{
                font-size: 1rem;
            }
            
            .gras{
                font-weight: bold;
            }

        </style>
    </head>
    
    <body>
        <main>";
