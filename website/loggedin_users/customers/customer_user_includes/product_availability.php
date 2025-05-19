<?php
    //Per evitare di accedere direttamente alla pagina
    require_once('../../includes/loggedin.php');   

    //Fetch dei prodotti
    $sql = "SELECT nome, prezzo, disponibilità FROM prodotto";
    if (!$result = mysqli_query($dbc, $sql)) {
        die("DB query error: " . mysqli_error($dbc));
    }

    // Ciclo e creo le card dei prodotti
    while ($row = mysqli_fetch_assoc($result)) {
        $nome       = $row['nome'];
        $prezzo     = number_format($row['prezzo'], 2, ',', '.');
        $disponibile = (bool)$row['disponibilità'];
    
        // Assegno al prodotto un immagine, una descrizione e una categoria in modo da poterli smistare
        if (isset($mapped_products[$nome])) {
            $imgFile      = $mapped_products[$nome]['img'];
            $descrizione  = $mapped_products[$nome]['desc'];
            $categoria    = $mapped_products[$nome]['category'];
        } else { //Nel caso non si trovasse un prodotto questo permette di non generare warning
            $imgFile      = 'default.jpg';
            $descrizione  = "Scopri il nostro prodotto: $nome.";
            $categoria    = 'Uncategorized';
        }
        
        // percorso immagine di default
        $imgPath    = '../../images/product_images/'.$imgFile;
    
        // se non disponibile, aggiungo la classe
        $extraClass = $disponibile ? "" : " unavailable";
        ?>
            <div class="product<?= $extraClass ?>" data-category="<?= $categoria ?>" data-name="<?= strtolower($nome) ?>">
                <figure>
                    <img src="<?= $imgPath ?>" alt="<?= $nome ?>">
                    <figcaption>
                        <main>
                            <p><?= $descrizione ?></p>
                        </main>
        
                        <footer>
                            <div>
                                <p class="small">Costo</p>
                                <p class="price"><?= $prezzo ?>€</p>
                            </div>

                            <!-- Aggiunta al carrello -->
                            <button 
                                type="button"
                                class="add-to-cart"
                                data-action="add_to_cart"
                                data-product-name="<?= htmlspecialchars($nome) ?>">
                                                                    
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                            </button>
                            
                        </footer>
                    </figcaption>
                </figure>
                <div class="product-name">
                    <?= $nome ?>
                </div>
            </div>
        <?php
    } // end while
    
    mysqli_free_result($result);
?>