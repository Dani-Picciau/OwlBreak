<?php
    // Controlla se è una richiesta AJAX
    $isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    // Ricevo il nome del prodotto dal bottone di product_availability
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['add_to_cart'])){
            $nomeProdotto = $_POST['add_to_cart']; //salvo il nome del prodotto all'interno di una variabile
        
            /* Creo all'interno della sessione un array $_SESSION['cart']' che associo ad ogni prodotto inviato tramite il bottone 'add-to-cart'. Se non esiste già il prodotto che si vuole aggiungere gli si assegna 0 altrimenti si continua con +1 ripetutamente */
            $_SESSION['cart'][$nomeProdotto] = ($_SESSION['cart'][$nomeProdotto] ?? 0) + 1;
            
        } else if (isset($_POST['increase'])){
            $nomeProdotto = $_POST['increase'];
            $_SESSION['cart'][$nomeProdotto]++;
        } else if (isset($_POST['decrease'])){
            $nomeProdotto = $_POST['decrease'];

            if ($_SESSION['cart'][$nomeProdotto] > 1) {
                $_SESSION['cart'][$nomeProdotto]--;
            } else {
                unset($_SESSION['cart'][$nomeProdotto]);
            }
        } else if (isset($_POST['remove'])){
            $nomeProdotto = $_POST['remove'];
            unset($_SESSION['cart'][$nomeProdotto]);
        }

        // Se è una richiesta AJAX, termina qui e non fare redirect
        if ($isAjaxRequest) {
            // Restituisci un JSON con i dati aggiornati del carrello
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'cartCount' => isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0
            ]);
            exit;
        }
    }
?>