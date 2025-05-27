<?php
    //Per evitare di accedere direttamente alla pagina
    require_once('../../../includes/loggedin.php');  
    require_once('../../../includes/mysqli_connect_user.php');
 
    require_once('load_orders.php');

    foreach ($ordiniAttesa as $ordine) {
        $nome           = $ordine['nome'];
        $cognome        = $ordine['cognome'];
        $luogoConsegna  = $ordine['luogoConsegna']; 
        $email          = $ordine['emailCliente'];
        $data           = $ordine['data'];
        $ora            = $ordine['ora'];
        $stato          = $ordine['consegnato'];

        $sql_prod ="SELECT nomeProdotto, quantità
                    FROM ordine
                    WHERE emailCliente = '$email' AND data = '$data' AND ora = '$ora'
                    GROUP BY nomeProdotto
                ";
        if (!$res_prod = mysqli_query($dbc, $sql_prod)) {
            die("DB query error: " . mysqli_error($dbc));
        }
        ?>
            <div class="order" data-category="attesa"
                data-email="<?= $email ?>"
                data-date="<?= $data ?>"
                data-time="<?= $ora ?>">
                <section>
                    <div>
                        <span><?=$nome?> <?=$cognome?></span>
                        <span>Luogo di consegna: <?=$luogoConsegna?></span>
                        <span>Ordinato alle <?=$ora?></span>
                    </div>
                    <div>
                        <span class="status pending">In attesa</span>
                        <label class="toggle-slider">
                            <input type="checkbox" class="orderToggle"/>
                            <span class="thumb"></span>
                        </label>
                    </div>
                </section>
                <hr>
                <span>Prodotti ordinati:</span>
                <div>
                    <?php
                        while ($prodotto = mysqli_fetch_assoc($res_prod)) {
                            ?> 
                                <span class="product-item" data-product="<?= $prodotto['nomeProdotto'] ?>">
                                    <?= $prodotto['nomeProdotto'] ?> x <?= $prodotto['quantità'] ?>
                                </span> 
                            <?php
                        }
                    ?>
                </div>
                <footer>
                    <span>Totale: €8,50</span>
                </footer>
            </div>
        <?php
        
    } 
?>