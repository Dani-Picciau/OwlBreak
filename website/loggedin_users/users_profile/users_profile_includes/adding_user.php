<?php
    //Dato che il file non può essere acceduto direttamente, ma solo tramite require(...) questa rappresenta un ulteriore precauzione per specificare quali tipi di utenti hanno accesso alla pagina
    require_once(__DIR__. '/../../../includes/loggedin.php');
    require_once(__DIR__. '/../../../includes/mysqli_connect_user.php');
?>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recupero tutti i dati dal form
        $userName           = trim($_POST['name'] ?? '');
        $userSurname        = trim($_POST['surname'] ?? '');
        $userRole           = trim($_POST['role'] ?? '');
        $userPlaceDelivery  = trim($_POST['delivery-place'] ?? '');
        $userPassword       = trim($_POST['defaultPassword'] ?? '');
        $userConfirmPssw    = trim($_POST['defaultPasswordConfirm'] ?? '');

        //Da qui, avendo recuperato tutti i dati necessari, iniziano i controlli:
        //1. form non compilato correttamente
        if (empty($userName) || empty($userSurname) || empty($userRole) || empty($userPlaceDelivery) || empty($userPassword) || empty($userConfirmPssw)){ 
            $_SESSION['input_not_filled_add_user'] = true;
            $_SESSION['show_add_user'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        }
        //2. Se nessun campo è stato lasciato vuoto controllo che la password scelta per l'utente e la conferma coincidano.
        else if($userPassword != $userConfirmPssw){
            $_SESSION['confirm_add_user'] = true;
            $_SESSION['show_add_user'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        }
        //3. Controllo dei requisiti password, vanno effettuati lato php perché alla procedura la password arriverà già hashata, rendendo impossibile il controllo dei requisiti all'interno della procedura
        else if (
            strlen($userPassword) < 8 ||
            !preg_match('/[A-Z]/', $userPassword) ||
            !preg_match('/[a-z]/', $userPassword) ||
            !preg_match('/\d/', $userPassword) ||
            !preg_match('/[!@#$%^&*()_+\-=\[\]{};\'":\\\\|,.<>\/?]/', $userPassword)
        ) {
            $_SESSION['requiremets_add_user'] = true;
            $_SESSION['show_add_user'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        }
        /*4. Tutti i controlli sono stati superati, procedo quindi a richiamare la procedura di inserimento.
        I controlli relativi alle lettere maiuscole e minuscole, cosi come la creazione automatica dell'email, vengono gestiti all'interno della procedura. */
        else{
            $userPassword_hashed = password_hash($userPassword, PASSWORD_DEFAULT);

            $stmt = $dbc->prepare("CALL insert_cliente(?, ?, ?, ?, ?, @out_messaggio)");
            $stmt->bind_param("sssss", $userName, $userSurname, $userRole, $userPlaceDelivery, $userPassword_hashed);
            $stmt->execute();
            $stmt->close();

            // Recupero del valore dell'output
            $result_mess = mysqli_query($dbc, "SELECT @out_messaggio AS messaggio");
            $row = mysqli_fetch_assoc($result_mess);
            $messaggio = $row['messaggio'] ?? '';

            $_SESSION['added_user'] = true;
            $_SESSION['show_add_user'] = true;
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