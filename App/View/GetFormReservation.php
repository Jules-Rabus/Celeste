    <?php if($erreur != 3) : ?>

        <article>
            <p><img src='images/<?=$concert->getImage()?>' loading='lazy' alt='Image<?=$concert->getNom()?>' /></p>
            <h2><?=$concert->getNom()?> : <?=$concert->getDate()?></h2>
        </article>

        <?php if($erreur != 2 && $erreur != 1) : ?>

            <?php if(!isset($reservation_effectue)) : ?>

                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <tr>
                                    <form id='formulaire_panier_quantite_<?=$id_concert?>' method='get'>
                                    <td>Or : 70 €</td>
                                    <td>
                                        <select name="places[place_or]" size="1">
                                            <?php for( $i = 0; $i< $places['place_or_reservable'] +1; $i++) : ?>

                                                <?php if(isset($_SESSION['places']) && $i == $_SESSION['places']['place_or']) : ?>
                                                     <option selected='selected'><?=$i?></option>
                                                <?php else : ?>
                                                    <option><?=$i?></option>
                                                <?php endif; ?>

                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td><?=$prix['place_or']?> €</td>
                                </tr>
                                <tr>
                                    <td>Argent : 55 €</td>
                                    <td>
                                        <select name="places[place_argent]" size="1">
                                            <?php for( $i = 0; $i< $places['place_argent_reservable'] +1; $i++) : ?>

                                                <?php if(isset($_SESSION['places']) && $i == $_SESSION['places']['place_argent']) : ?>
                                                    <option selected='selected'><?=$i?></option>
                                                <?php else : ?>
                                                    <option><?=$i?></option>
                                                <?php endif; ?>

                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td><?=$prix['place_argent']?> €</td>
                                </tr>
                                <tr>
                                    <td>Bronze : 45 €</td>
                                    <td>
                                        <select name="places[place_bronze]" size="1">
                                            <?php for( $i = 0; $i< $places['place_bronze_reservable'] +1; $i++) : ?>

                                                <?php if(isset($_SESSION['places']) && $i == $_SESSION['places']['place_bronze']) : ?>
                                                    <option selected='selected'><?=$i?></option>
                                                <?php else : ?>
                                                    <option><?=$i?></option>
                                                <?php endif; ?>

                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td><?=$prix['place_bronze']?> €</td>
                                </tr>
                                <tr>
                                    <td>Fosse : 35 €</td>
                                    <td>
                                        <select name="places[place_fosse]" size="1">
                                            <?php for( $i = 0; $i< $places['place_fosse_reservable'] +1; $i++) : ?>

                                                <?php if(isset($_SESSION['places']) && $i == $_SESSION['places']['place_fosse']) : ?>
                                                    <option selected='selected'><?=$i?></option>
                                                <?php else : ?>
                                                    <option><?=$i?></option>
                                                <?php endif; ?>

                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td><?=$prix['place_fosse']?> €</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type='number' value='<?=$id_concert?>' name='id_concert' hidden required >
                                        <input type='submit' value='Calculer'>
                                    </td>
                                    <td><?=$prix['total']?> €</td>
                                </tr>
                                </form>
                            </tr>
                        </tbody>
                    </table>

                    <?php if(isset($prix)) : ?>
                        <h3>Le prix est de <?=$prix['total']?> €</h3>
                    <?php endif ; ?>

                    <?php if( isset($_SESSION['reservation_possible']) && $_SESSION['reservation_possible'] === 0) : ?>
                        <h3>Réservation impossible</h3>
                    <?php endif; ?>

                    <?php if( !$reservation_vide)  : ?>

                            <form id='formulaire_concert_ajouter' method='post'>
                                <p class='submit'>
                                    <input type='submit' value='Ajouter au panier' name='ajouter'/>
                                </p>
                            </form>
                            <script>
                                function copier() {
                                    var copyText = document.getElementById("lien");
                                    copyText.select();
                                    copyText.setSelectionRange(0, 99999); /* For mobile devices */
                                    navigator.clipboard.writeText(copyText.value);
                                }
                            </script>
                            <input type='text' value='<?="https://" .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>' id='lien' hidden>
                            <button onclick="copier()">Partager le concert</button>

                    <?php endif ; ?>

                <?php else : ?>

                    <?php if($reservation_effectue == 1) :?>
                        <h3> Réservation effectué </h3>
                    <?php else : ?>
                        <h3> Il y a un problème avec la réservation </h3>
                    <?php endif ; ?>

                <?php endif ; ?>

            <?php elseif($erreur == 1) : ?>
                <h2>Le concert est passé</h2>
            <?php else : ?>
                <h2>Le concert a été annulé</h2>
            <?php endif; ?>

        <?php else : ?>
            <h2>Ce concert n'existe pas <a href='concert.php'>Page Concert</a></h2>
        <?php endif; ?>
                    
    </div>