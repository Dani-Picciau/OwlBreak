<?php
    //Dato che il file non può essere acceduto direttamente, ma solo tramite require(...) questa rappresenta un ulteriore precauzione per specificare quali tipi di utenti hanno accesso alla pagina
    require_once(__DIR__. '/../../../includes/loggedin.php');
    require_once(__DIR__. '/../../../includes/mysqli_connect_user.php');
?>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recupero tutti i dati dal form
        $userEmail = trim($_POST['Email'] ?? '');

        //Da qui, avendo recuperato tutti i dati necessari, iniziano i controlli:
        /* 1. Input non inserito, eseguo il controllo anche lato php nel caso in cui "required" venisse tolto con gli strumenti di sviluppo. 
        !!N.B!! -> Questo è l'unico controllo perché l'esistenza dell'email da rimuovere viene verificata all'interno della procedura, per cui se viene inserita un'email che non identifica nessun utente, recupero il messaggio di errore dalla procedura e lo inserisco in una variabile di sessione che mi indica il risultato. */
        if (empty($userEmail)){ 
            $_SESSION['input_not_filled_remove_user'] = true;
            $_SESSION['show_remove_user'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        }
        //2. Tutti i controlli sono stati superati, procedo quindi a richiamare la procedura di rimozione cliente.
        else{
            $stmt = $dbc->prepare("CALL elimina_cliente(?, @out_messaggio)");
            $stmt->bind_param("s",$userEmail);
            $stmt->execute();
            $stmt->close();

            // Recupero del valore dell'output
            $result_mess = mysqli_query($dbc, "SELECT @out_messaggio AS messaggio");
            $row = mysqli_fetch_assoc($result_mess);
            $messaggio = $row['messaggio'] ?? '';

            if($messaggio == 'Cliente non trovato') $_SESSION['removed_user_error'] = true;
            else $_SESSION['removed_user'] = true;
            
            $_SESSION['show_remove_user'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        } 
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