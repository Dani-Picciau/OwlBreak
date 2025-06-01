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
            <div class="filters">
                <div class="filter-group">
                    <label for="date-filter">Data:</label>
                    <input type="date" id="date-filter" name="date-filter">
                </div>
                <div class="filter-group">
                    <label for="status-filter">Stato:</label>
                    <select id="status-filter" name="status-filter">
                        <option value="all">Tutti</option>
                        <option value="delivered">Consegnati</option>
                        <option value="pending">In attesa</option>
                    </select>
                </div>
            </div>
        
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
               <p>Nessun ordine trovato.</p>
        <?php
    }
    mysqli_free_result($result);
?>
    