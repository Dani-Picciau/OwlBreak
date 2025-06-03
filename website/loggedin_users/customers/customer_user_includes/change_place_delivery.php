<?php
    //Dato che il file non può essere acceduto direttamente, ma solo tramite require(...) questa rappresenta un ulteriore precauzione per specificare quali tipi di utenti hanno accesso alla pagina
    require_once(__DIR__ . '/../../../includes/loggedin.php');
    check_user_type('Personale-Docente', 'Personale-Ata', 'Personale-Segreteria');
    require_once(__DIR__. '/../../../includes/mysqli_connect_user.php');
?>


<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $emailUtente = $_SESSION['email'];
        $luogoConsegna = trim($_POST['luogoConsegna'] ?? '');

        //Da qui, avendo recuperato tutti i dati necessari, iniziano i controlli:
        //1. form non compilato correttamente
        if (empty($luogoConsegna)) {
            $_SESSION['input_not_filled_place_delivery'] = true;
            $_SESSION['show_cart'] = true;
            header("Location:/owlbreak/website/loggedin_users/customers/customer_user.php");
            exit;
        } else{
            $stmt = $dbc->prepare("CALL cambio_luogo_consegna(?, ?, @out_messaggio)");
            $stmt->bind_param("ss",$emailUtente , $luogoConsegna);
            $stmt->execute();
            $stmt->close();

            // Recupero del valore dell'output
            $result_mess = mysqli_query($dbc, "SELECT @out_messaggio AS messaggio");
            $row = mysqli_fetch_assoc($result_mess);
            $messaggio = $row['messaggio'] ?? '';

            $_SESSION['changed_place_delivery'] = true;
            $_SESSION['show_cart'] = true;
            header("Location:/owlbreak/website/loggedin_users/customers/customer_user.php");
            exit;
        }
    } else{
        if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
            //Se la condizione è verificata significa che il file è stato cercato in modo diretto nella barra di ricerca e qualsiasi sia il tipo di utente, esso viene rispedito alla home. 
            http_response_code(403);
            session_start();
            require_once(__DIR__. '/../../../includes/redirect_users.php');
            redirect_users($_SESSION['user_type']);
        }
    }
?>