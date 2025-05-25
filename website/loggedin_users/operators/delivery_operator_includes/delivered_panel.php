<?php
    //Per evitare di accedere direttamente alla pagina
    require_once('../../includes/loggedin.php');   

    foreach ($ordiniConsegnati as $ordine) {
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
            <div class="order" data-category="consegnato">
                <section>
                    <div>
                        <span><?=$nome?> <?=$cognome?></span>
                        <span>Luogo di consegna: <?=$luogoConsegna?></span>
                        <span>Ordinato alle <?=$ora?></span>
                    </div>
                    <div>
                        <span class="status delivered">Consegnato</span>
                        <label class="toggle-slider">
                            <input type="checkbox" class="orderToggle" checked=true />
                            <span class="thumb"></span>
                        </label>
                    </div>
                </section>
                <hr>
                <span>Prodotti ordinati:</span>
                <div>
                    <?php
                        while ($prodotto = mysqli_fetch_assoc($res_prod)) {
                            ?> <span><?= $prodotto['nomeProdotto'] ?> x <?= $prodotto['quantità'] ?></span> <?php
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