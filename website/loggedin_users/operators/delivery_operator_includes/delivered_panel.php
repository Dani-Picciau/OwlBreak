<?php
    //Per evitare di accedere direttamente alla pagina
    require_once('../../../includes/loggedin.php');  
    require_once('../../../includes/mysqli_connect_user.php');
    
    require_once('load_orders.php');

    foreach ($ordiniConsegnati as $ordine) {
        $nome           = $ordine['nome'];
        $cognome        = $ordine['cognome'];
        $luogoConsegna  = $ordine['luogoConsegna']; 
        $email          = $ordine['emailCliente'];
        $data           = $ordine['data'];
        $ora            = $ordine['ora'];
        $stato          = $ordine['consegnato'];

        $sql_prod ="SELECT o.nomeProdotto, o.quantità, (p.prezzo* o.quantità) as totale_row
                    FROM ordine as o, prodotto as p
                    WHERE p.nome = o.nomeProdotto AND o.emailCliente = '$email' AND o.data = '$data' AND o.ora = '$ora'
                    GROUP BY o.nomeProdotto
                ";
        if (!$res_prod = mysqli_query($dbc, $sql_prod)) {
            die("DB query error: " . mysqli_error($dbc));
        }

        ?>
            <div class="order active" data-category="consegnato"
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
                        <span class="status delivered">Consegnato</span>
                        <label class="toggle-slider active">
                            <span class="thumb active"></span>
                        </label>
                    </div>
                </section>
                <hr>
                <span>Prodotti ordinati:</span>
                <div>
                    <?php
                        $totale=0;
                        while ($prodotto = mysqli_fetch_assoc($res_prod)) {
                            $totale += $prodotto['totale_row'];
                            ?> 
                                <span class="product-item" data-product="<?= $prodotto['nomeProdotto'] ?>">
                                    <?= $prodotto['nomeProdotto'] ?> x <?= $prodotto['quantità'] ?>
                                </span> 
                            <?php
                        }
                    ?>
                </div>
                <footer>
                    <span>Totale: €<?=number_format($totale, 2)?></span>
                </footer>
            </div>
        <?php
    }
?>