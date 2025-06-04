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
    check_user_type('Addetto-Consegne');
?>

<?php
    $CodiceID = $_SESSION['CodiceID'];

    /* Fatturato totale dell'operatore */
    $query1 = "
        SELECT SUM(p.prezzo * o.quantità) AS fatturato_totale
        FROM ordine o, prodotto p
        WHERE o.OperatoreID = $CodiceID
        AND o.consegnato = TRUE
        AND o.nomeProdotto = p.nome;
    ";
    $result = mysqli_query($dbc, $query1); 
    if (!$result) die("DB query error: " . mysqli_error($dbc));
    $row = mysqli_fetch_assoc($result);
    $fatturato_totale = $row['fatturato_totale'];
    $fatturato_totale_formattato = number_format($fatturato_totale, 2, ',', '.');
    ?>
        <div class="statistic">
            <div class="statistic-type">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M336-120q-91 0-153.5-62.5T120-336q0-38 13-74t37-65l142-171-97-194h530l-97 194 142 171q24 29 37 65t13 74q0 91-63 153.5T624-120H336Zm144-200q-33 0-56.5-23.5T400-400q0-33 23.5-56.5T480-480q33 0 56.5 23.5T560-400q0 33-23.5 56.5T480-320Zm-95-360h190l40-80H345l40 80Zm-49 480h288q57 0 96.5-39.5T760-336q0-24-8.5-46.5T728-423L581-600H380L232-424q-15 18-23.5 41t-8.5 47q0 57 39.5 96.5T336-200Z"/></svg>
                Fatturato totale
            </div>
            <p>€ <?= $fatturato_totale_formattato ?></p>
        </div>
    <?php

    /* Consegne effettuate nella data odierna */
    $query2 = "
        SELECT COUNT(*) AS consegne_oggi
        FROM ordine
        WHERE OperatoreID = $CodiceID
        AND consegnato = TRUE
        AND data = CURDATE();
    ";
    $result = mysqli_query($dbc, $query2); 
    if (!$result) die("DB query error: " . mysqli_error($dbc));
    $row = mysqli_fetch_assoc($result);
    $Consegne_effettuate = $row['consegne_oggi'];
    ?>
        <div class="statistic">
            <div class="statistic-type">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M40-160v-80h200v-80H80v-80h160v-80H122v-80h118v-118l-78-168 72-34 94 200h464l-78-166 72-34 94 200v520H40Zm440-280h160q17 0 28.5-11.5T680-480q0-17-11.5-28.5T640-520H480q-17 0-28.5 11.5T440-480q0 17 11.5 28.5T480-440ZM320-240h480v-360H320v360Zm0 0v-360 360Z"/></svg>
                Consegnati oggi
            </div>
            <p> <?= $Consegne_effettuate ?></p>
        </div>
    <?php

    /* Totale ordini consegnati */
    $query3 = "
    SELECT COUNT(*) AS ordini_consegnati
    FROM (
        SELECT data, ora, emailCliente
        FROM ordine
        WHERE OperatoreID = $CodiceID
        AND consegnato = TRUE
        GROUP BY data, ora, emailCliente
    ) AS ord_distinti;
    ";
    $result = mysqli_query($dbc, $query3); 
    if (!$result) die("DB query error: " . mysqli_error($dbc));
    $row = mysqli_fetch_assoc($result);
    $Totale_consegnati = $row['ordini_consegnati'];
    ?>
        <div class="statistic">
            <div class="statistic-type">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="m787-145 28-28-75-75v-112h-40v128l87 87Zm-587 25q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v268q-19-9-39-15.5t-41-9.5v-243H200v560h242q3 22 9.5 42t15.5 38H200Zm0-120v40-560 243-3 280Zm80-40h163q3-21 9.5-41t14.5-39H280v80Zm0-160h244q32-30 71.5-50t84.5-27v-3H280v80Zm0-160h400v-80H280v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40Z"/></svg>
                Totale consegnati
            </div>
            <p> <?= $Totale_consegnati ?></p>
        </div>
    <?php

    /* Data ultima consegna */
    $query4 = "
        SELECT MAX(data) AS ultima_data
        FROM ordine
        WHERE OperatoreID = $CodiceID
        AND consegnato = TRUE;
    ";
    $result = mysqli_query($dbc, $query4); 
    if (!$result) die("DB query error: " . mysqli_error($dbc));
    $row = mysqli_fetch_assoc($result);
    $data_ultima_consegna = $row['ultima_data'];

    $data_formattata = "";
    if ($data_ultima_consegna) {
        // Converto la stringa della data in un timestamp
        $timestamp = strtotime($data_ultima_consegna);
        
        // Localizzazioen italiana per avere il mese scritto in italiano
        setlocale(LC_TIME, 'it_IT.UTF-8', 'it_IT', 'it');
        
        // Formattazione per la data
        $giorno = date('j', $timestamp);
        $mese = strtolower(strftime('%B', $timestamp));
        $anno = date('Y', $timestamp);
        
        $data_formattata = "$giorno $mese $anno";
    }
    ?>
        <div class="statistic">
            <div class="statistic-type">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 27-3 53t-10 51q-14-16-32.5-27T794-418q3-15 4.5-30.5T800-480q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93q51 0 97.5-15t85.5-42q12 17 29.5 30t37.5 20q-51 41-114.5 64T480-80Zm290-160q-21 0-35.5-14.5T720-290q0-21 14.5-35.5T770-340q21 0 35.5 14.5T820-290q0 21-14.5 35.5T770-240Zm-158-52L440-464v-216h80v184l148 148-56 56Z"/></svg>
                Data ultima ord.
            </div>
            <p style="font-size: 1.45rem;"> <?= $data_formattata ?></p>
        </div>
    <?php

    /* Orario ultima consegna */
    $query5 = "
        SELECT data, MAX(ora) AS ultima_ora
        FROM ordine
        WHERE OperatoreID = $CodiceID
        AND consegnato = TRUE
        AND data = (
            SELECT MAX(data)
            FROM ordine
            WHERE OperatoreID = $CodiceID
                AND consegnato = TRUE
        );
    ";
    $result = mysqli_query($dbc, $query5); 
    if (!$result) die("DB query error: " . mysqli_error($dbc));
    $row = mysqli_fetch_assoc($result);
    $Orario_ultima_consegna = $row['ultima_ora'];
    ?>
        <div class="statistic">
            <div class="statistic-type">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 27-3 53t-10 51q-14-16-32.5-27T794-418q3-15 4.5-30.5T800-480q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93q51 0 97.5-15t85.5-42q12 17 29.5 30t37.5 20q-51 41-114.5 64T480-80Zm290-160q-21 0-35.5-14.5T720-290q0-21 14.5-35.5T770-340q21 0 35.5 14.5T820-290q0 21-14.5 35.5T770-240Zm-158-52L440-464v-216h80v184l148 148-56 56Z"/></svg>
                Ora ultima ord.
            </div>
            <p> <?= $Orario_ultima_consegna ?></p>
        </div>
    <?php
?>
