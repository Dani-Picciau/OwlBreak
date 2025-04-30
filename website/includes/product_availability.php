<?php
    require('../includes/mysqli_connect.php');
    require_once('../includes/loggedin.php');


    // 1. Fetch all products
    $sql = "SELECT nome, prezzo, disponibilità FROM prodotto";
    if (!$result = mysqli_query($dbc, $sql)) {
        die("DB query error: " . mysqli_error($dbc));
    }

    // 2. Loop and render cards
    while ($row = mysqli_fetch_assoc($result)) {
        $nome       = htmlspecialchars($row['nome'], ENT_QUOTES);
        $prezzo     = number_format($row['prezzo'], 2, ',', '.');
        $disponibile = (bool)$row['disponibilità'];
    
        // percorso immagine di default
        $imgPath    = "../images/product_images/coffee.jpg";
    
        // se non disponibile, aggiungo la classe
        $extraClass = $disponibile ? "" : " unavailable";
    
        // testo alternativo se vuoi una descrizione diversa
        $descrizione = "Lascia che il $nome ti racconti storie che durano più di una giornata.";
        ?>
        
        <div class="product<?= $extraClass ?>">
            <figure>
                <img src="<?= $imgPath ?>" alt="<?= $nome ?>">
                <figcaption>
                    <main>
                        <p><?= $descrizione ?></p>
                    </main>
    
                    <footer>
                        <div>
                            <p class="small">Cost</p>
                            <p class="price"><?= $prezzo ?>€</p>
                        </div>
    
                        <!-- Qui puoi sostituire con un button se vuoi fare ordine -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             height="24px" viewBox="0 -960 960 960"
                             width="24px" fill="#BD4C31">
                            <path d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40
                                     200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83
                                     31.5-156T197-763q54-54 127-85.5T480-880q83 0
                                     156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5
                                     156T763-197q-54 54-127 85.5T480-80Z"/>
                        </svg>
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
    mysqli_close($dbc);
    ?>