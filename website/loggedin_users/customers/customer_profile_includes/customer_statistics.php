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
    /* Totale speso da un cliente */
    $query1 = "
        SELECT SUM((
            SELECT prezzo
            FROM prodotto
            WHERE nome = o.nomeProdotto
        ) * o.quantità) AS totale_speso
        FROM ordine o
        WHERE o.emailCliente = '$email';
    ";
    $result = mysqli_query($dbc, $query1); 
    if (!$result) die("DB query error: " . mysqli_error($dbc));
    $row = mysqli_fetch_assoc($result);
    $totale_speso = $row['totale_speso'];
    $totale_speso_formattato = number_format($totale_speso, 2, ',', '.');
    ?>
        <div class="statistic">
            <div class="statistic-type">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160v240h640v-240H160Zm0 240v-480 480Z"/></svg>
                Totale speso
            </div>
            <p>€ <?= $totale_speso_formattato ?></p>
        </div>
    <?php

    /* Ordini effettuati da un cliente */
    $query2 = "
        SELECT COUNT(DISTINCT data, ora) AS ordini_effettuati
        FROM ordine
        WHERE emailCliente = '$email';
    ";
    $result = mysqli_query($dbc, $query2); 
    if (!$result) die("DB query error: " . mysqli_error($dbc));
    $row = mysqli_fetch_assoc($result);
    $ordini_effettuati = $row['ordini_effettuati'];
    ?>
        <div class="statistic">
            <div class="statistic-type">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M200-80q-33 0-56.5-23.5T120-160v-480q0-33 23.5-56.5T200-720h80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720h80q33 0 56.5 23.5T840-640v480q0 33-23.5 56.5T760-80H200Zm0-80h560v-480H200v480Zm280-240q83 0 141.5-58.5T680-600h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85h-80q0 83 58.5 141.5T480-400ZM360-720h240q0-50-35-85t-85-35q-50 0-85 35t-35 85ZM200-160v-480 480Z"/></svg>
                Ordini effettuati
            </div>
            <p><?=$ordini_effettuati?></p>
        </div>
    <?php

    /* Media per ordine di un cliente */
    $media_per_ordine = 0;
    if ($ordini_effettuati > 0 && $totale_speso > 0) {
        $media_per_ordine = $totale_speso / $ordini_effettuati;
    }
    $media_per_ordine_formattata = number_format($media_per_ordine, 2, ',', '.');
    ?>
        <div class="statistic">
            <div class="statistic-type">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M440-183v-274L200-596v274l240 139Zm80 0 240-139v-274L520-457v274Zm-80 92L160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11Zm200-528 77-44-237-137-78 45 238 136Zm-160 93 78-45-237-137-78 45 237 137Z"/></svg>
                Media per ordine
            </div>
            <p>€ <?= $media_per_ordine_formattata ?></p>
        </div>
    <?php

    /* Data ultimo ordine di un cliente */
    $query4 = "
        SELECT MAX(data) AS ultimo_ordine
        FROM ordine
        WHERE emailCliente = '$email';
    ";
    $result = mysqli_query($dbc, $query4); 
    if (!$result) die("DB query error: " . mysqli_error($dbc));
    $row = mysqli_fetch_assoc($result);
    $ultimo_ordine = $row['ultimo_ordine'];

    $data_formattata = "";
    if ($ultimo_ordine) {
        // Converto la stringa della data in un timestamp
        $timestamp = strtotime($ultimo_ordine);
        
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z"/></svg>
                Ultimo ordine
            </div>
            <p style="font-size: 1.45rem;"><?=$data_formattata?></p>
        </div>
    <?php

    /* Ordini in corso di un cliente */
    $query5 = "
        SELECT COUNT(*) AS ordini_in_corso
        FROM ordine
        WHERE emailCliente = '$email' AND consegnato = FALSE;

    ";
    $result = mysqli_query($dbc, $query5); 
    if (!$result) die("DB query error: " . mysqli_error($dbc));
    $row = mysqli_fetch_assoc($result);
    $ordini_in_corso = $row['ordini_in_corso'];
    ?>
        <div class="statistic">
            <div class="statistic-type">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z"/></svg>
                Ordini in corso
            </div>
            <p><?=$ordini_in_corso?></p>
        </div>
    <?php
?>