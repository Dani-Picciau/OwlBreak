<?php
    //Per evitare di accedere direttamente alla pagina
    require_once('../includes/loggedin.php');   

    //Controllo che la variabile superglobale "$_SESSION['cart']" sia stata creata e che al suo interno contenga degli elementi, ad indicare che dei prodotti sono stati aggiunti al carrello tramite il bottone in product_availability.php, altrimenti, viene stampato un messaggio di "Carrello vuoto".
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        //Se entrabe le condizioni sono confermate, significa che all'interno dell'array associativo sono stati aggiunti dei prodotti, che andrò a scorrere tramite un foreach per la creazione dei vari item nel carrello.
        foreach ($_SESSION['cart'] as $nomeProdotto => $quantità) {

            //Sapendo su quali prodotti devo lavorare faccio una query con condizione di "nome = $nomeProdotto" per estrapolare in modo diretto le informazioni dei prodotti all'interno del carrello.
            $sql = "SELECT nome, prezzo, disponibilità FROM prodotto WHERE nome = '$nomeProdotto'";
            if (!$result = mysqli_query($dbc, $sql)) {
                die("DB query error: " . mysqli_error($dbc));
            }
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $nomeProdotto = $row['nome'];
            $prezzo = $row['prezzo'];
            $prezzoFormattato = number_format($prezzo, 2, ',', '.'); //formatto il prezzo per averlo a mio piacimento

            // Recupero l'immagine del prodotto da product_mapping.php
            if (isset($mapped_products[$nomeProdotto])) {
                $imageFile = $mapped_products[$nomeProdotto]['img'];
                $imagePath = '../images/product_images/' . $imageFile;
            } else {
                $imageFile = 'default.jpg';
            }
            
            // Calcolo il prezzo totale per il prodotto in base alla quantità scelta
            $subtotale = $prezzo * $quantità;
            $subtotaleFormattato = number_format($subtotale, 2, ',', '.');
            

            // Creo l'item all'interno del carrello
            ?>
                <div class="cart-item">
                    <div class="box1">
                        <div>
                            <img src="<?= htmlspecialchars($imagePath) ?>">
                        </div> 
                        <div class="specific">
                            <span><?= htmlspecialchars($nomeProdotto) ?></span>
                            <hr>
                            <span>Quantità x <?= $quantità ?></span>
                            <span>€<?= $subtotaleFormattato ?></span>
                        </div>
                    </div>   
                    <div class="box2">
                        <div class="quantity-control">
                            <!-- Diminuisci quantità -->
                            <button class="quantity-button" type="button"
                                data-action="decrease" 
                                data-product-name="<?= htmlspecialchars($nomeProdotto) ?>">
                                <svg viewBox="0 0 24 24"><path d="M5 12h14"></path></svg>
                            </button>

                            <span class="quantity-value"><?= $quantità ?></span>

                            <!-- Aumenta quantità -->
                            <button class="quantity-button" type="button"
                                data-action="increase" 
                                data-product-name="<?= htmlspecialchars($nomeProdotto) ?>">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 5v14"></path>
                                    <path d="M5 12h14"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Rimuovi prodotto -->
                        <button class="delete-item" type="button" 
                            data-action="remove" 
                            data-product-name="<?= htmlspecialchars($nomeProdotto) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px">
                                <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                            </svg>
                            Rimuovi
                        </button>
                    </div>
                </div>
            <?php
        }
    } else {
        echo "<p>Il carrello è vuoto.</p>";
    }
?>