<?php
    //Per evitare di accedere direttamente alla pagina
    require_once('../../../includes/loggedin.php');  
    require_once('../../../includes/mysqli_connect_user.php');
 
    require_once('load_orders.php');

    if(count($ordiniAttesa)>0){
        foreach ($ordiniAttesa as $ordine) {
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
    } else {
        ?>
            <div class="empty-panel-container">
                <div class="panel-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M620-163 450-333l56-56 114 114 226-226 56 56-282 282Zm220-397h-80v-200h-80v120H280v-120h-80v560h240v80H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v200ZM480-760q17 0 28.5-11.5T520-800q0-17-11.5-28.5T480-840q-17 0-28.5 11.5T440-800q0 17 11.5 28.5T480-760Z"/></svg>
                </div>
                
                <h2 class="panel-title">Ben fatto, ordini consegnati!</h2>
                
                <p class="panel-message">
                    Sembra che tu abbia consegnato tutti gli ordini della giornata. Non ci sono altri ordini in sospeso.
                </p>
            </div>
        <?php
    }
?>