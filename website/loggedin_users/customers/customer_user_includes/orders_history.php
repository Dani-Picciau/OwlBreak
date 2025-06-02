<?php
    if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
        //Se la condizione è verificata significa che il file è stato cercato in modo diretto nella barra di ricerca e qualsiasi sia il tipo di utente, esso viene rispedito alla home. 
        http_response_code(403);
        session_start();
        require_once(__DIR__. '/../../../includes/redirect_users.php');
        redirect_users($_SESSION['user_type']);
    }

    //Dato che il file non può essere acceduto direttamente, ma solo tramite require(...) questa rappresenta un ulteriore precauzione per specificare quali tipi di utenti hanno accesso alla pagina
    require_once(__DIR__ . '/../../../includes/loggedin.php');
    check_user_type('Studente', 'Personale-Docente', 'Personale-Ata', 'Personale-Segreteria');
?>

<?php
    $email = $_SESSION['email'];
    //Fetch degli ordini
    $sql = "SELECT nomeProdotto, data, ora, quantità, consegnato FROM Ordine WHERE emailCliente = '$email' ORDER BY data DESC, ora DESC, nomeProdotto";
    $result = mysqli_query($dbc, $sql);
    if (!$result) {
        die("DB query error: " . mysqli_error($dbc));
    }
    
    if (mysqli_num_rows($result) > 0){
        ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Prodotto</th>
                        <th>Data</th>
                        <th>Ora</th>
                        <th>Quantità</th>
                        <th>Stato</th>
                    </tr>
                </thead>
                <tbody>
        <?php 
        
        while ($row = mysqli_fetch_assoc($result)){
            $nome = $row['nomeProdotto'];
            $data = date("d/m/Y", strtotime($row['data']));
            $ora = $row['ora'];
            $quantità = $row['quantità'];
            $stato = $row['consegnato'] ? 'Consegnato' : 'In attesa';
            $classeStato = $row['consegnato'] ? 'delivered' : 'pending';

            ?>
                <tr>
                    <td><?= $nome ?></td>
                    <td data-date="2025-05-04"><?= $data ?></td>
                    <td><?= $ora ?></td>
                    <td class="quantity"><?= $quantità ?></td>
                    <td><span class="status <?= $classeStato ?>"><?= $stato ?></span></td>
                </tr>
            <?php
        }
        
        ?>
                </tbody>
            </table>
        <?php
    }else{
        ?>
            <div class="empty-cart-container">
                <div class="cart-icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="m865-210-73-73 40-397H450l-10-80h200v-160h80v160h200l-55 550ZM625-449ZM819-28 27-820l57-57L876-85l-57 57ZM40-200v-80h600v80H40ZM80-40q-17 0-28.5-11.5T40-80v-40h600v40q0 17-11.5 28.5T600-40H80Zm282-559v80q-5 0-11-.5t-11-.5q-59 0-111.5 20T147-440h374l80 80H40q0-121 93.5-180.5T340-600q5 0 11 .5t11 .5Zm-22 159Z"/></svg>
                </div>
                
                <h2 class="cart-title">Nessun ordine effettuato</h2>
                
                <p class="cart-message">
                    Sembra che tu non abbia ancora una cronologia ordini. 
                    Esplora i nostri prodotti e trova qualcosa che ti piace!
                </p>
            </div>
        <?php
    }
    mysqli_free_result($result);
?>
    