<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        require_once(__DIR__ . '/../../../includes/loggedin.php');
        // Tutti i tipi di utenti cliente che possono accedere a questa pagina
        check_user_type('Studente', 'Personale-Docente', 'Personale-Ata', 'Personale-Segreteria');
        require_once('../../../includes/mysqli_connect_user.php');


        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            die('<p>Carrello vuoto. <a href="user_cart.php">Torna al carrello</a></p>');
        }

        $emailCliente = $_SESSION['email'];
        $results      = [];

        foreach ($_SESSION['cart'] as $nomeProdotto => $quantita) {
            // Chiama la procedura per ciascun prodotto
            $stmt = $dbc->prepare("CALL effettua_ordine(?, ?, ?, @out_messaggio)");
            $stmt->bind_param("ssi", $emailCliente, $nomeProdotto, $quantita);
            $stmt->execute();
            $stmt->close();

            // Recupera il messaggio OUT
            $res = $dbc->query("SELECT @out_messaggio AS messaggio");
            $row = $res->fetch_assoc();
            $results[] = [
                'product'  => $nomeProdotto,
                'quantity' => $quantita,
                'message'  => $row['messaggio']
            ];
        }

        // Svuoto il carrello se tutti gli ordini sono andati a buon fine
        $all_ok = true;
        foreach ($results as $r) {
            if ($r['message'] !== 'Ordine effettuato con successo') {
                $all_ok = false;
                break;
            }
        }

        if ($all_ok) {
            unset($_SESSION['cart']);
        }

        // Salvo i risultati in sessione per mostrarli nella pagina di conferma
        $_SESSION['order_results'] = $results;
        $_SESSION['order_success'] = $all_ok;

        // Reindirizza alla pagina di conferma
        header('Location: order_confirmation.php');
        exit;
    } else {
        if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
            //Se la condizione è verificata significa che il file è stato cercato in modo diretto nella barra di ricerca e qualsiasi sia il tipo di utente, esso viene rispedito alla home. 
            http_response_code(403);
            session_start();
            require_once(__DIR__. '/../../../includes/redirect_users.php');
            redirect_users($_SESSION['user_type']);
        }
    }

?>