<?php
    //Per evitare di accedere direttamente alla pagina
    require_once('../includes/loggedin.php');   

    //Controllo che la variabile superglobale "$_SESSION['cart']" sia stata creata e che al suo interno contenga degli elementi, ad indicare che dei prodotti sono stati aggiunti al carrello tramite il bottone in product_availability.php, altrimenti, viene stampato un messaggio di "Carrello vuoto".
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        $totaleCarrello = 0;
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

            $totaleCarrello += $subtotale;

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
        
        $totaleCarrelloF = number_format($totaleCarrello, 2, ',', '.');
        ?>
            <div class="separator3"></div>
            <div class="checkout-section">
                <div class="total">
                    <span class="total-label">Totale</span>
                    <span class="total-amount">€<?=  $totaleCarrelloF ?></span>
                </div>
                <div class="pagamento">
                    <span class="total-label">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160v240h640v-240H160Zm0 240v-480 480Z"/></svg>
                        Modalità di pagamento
                    </span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                        Pagamento alla consegna 
                    </span>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
                        Il pagamento avverrà al termine della consegna. Tieni pronto l'importo esatto o un metodo di pagamento elettronico. 
                    </div>
                </div>

                <div class="place-delivery">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M160-280v-480H80v-80h160v480h600v80H160Zm80 200q-33 0-56.5-23.5T160-160q0-33 23.5-56.5T240-240q33 0 56.5 23.5T320-160q0 33-23.5 56.5T240-80Zm40-320v-240h240v240H280Zm80-80h80v-80h-80v80Zm200 80v-240h240v240H560Zm80-80h80v-80h-80v80ZM760-80q-33 0-56.5-23.5T680-160q0-33 23.5-56.5T760-240q33 0 56.5 23.5T840-160q0 33-23.5 56.5T760-80ZM360-480h80-80Zm280 0h80-80Z"/></svg>
                        Consegna
                    </div>
                    <div>
                        <span>Luogo di consegna: 
                            <?php require('../includes/student_place_delivery.php');?>
                        </span>
                    </div>
                </div>

                <button id="confirmBtn" class="confirm-button">
                    Conferma ordine
                </button>
            </div>
        <?php
    } else {
        ?>
            <div class="empty-cart-container">
                <div class="cart-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>
                </div>
                
                <h2 class="cart-title">Il tuo carrello è vuoto</h2>
                
                <p class="cart-message">
                    Sembra che tu non abbia ancora aggiunto articoli al tuo carrello. 
                    Esplora i nostri prodotti e trova qualcosa che ti piace!
                </p>
            </div>
        <?php
    }
?>