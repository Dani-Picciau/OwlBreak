<?php
    require_once('../../../includes/loggedin.php');
    require_once('../../../includes/mysqli_connect_user.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recupero tutti i dati dal form
        $currentPassword  = trim($_POST['currentPassword'] ?? '');
        $newPassword      = trim($_POST['newPassword'] ?? '');
        $confirmPassword  = trim($_POST['confirmPassword'] ?? '');

        //Recupero la tabella del cliente in base al tipo di utente
        switch($_SESSION['user_type']){
            case 'Studente':
            case 'Personale-Docente':
            case 'Personale-Ata': 
            case 'Personale-Segreteria':{
                $is_cliente = true;
                $primary_key = $_SESSION['email'];

                $procedura = 'cambio_pssw_cliente';
                $hashed_passw = "SELECT passw FROM cliente WHERE email = '$primary_key'";
                $result = mysqli_query($dbc, $hashed_passw);
                if (!$result) {
                    die("DB query error: " . mysqli_error($dbc));
                }
                $row = mysqli_fetch_assoc($result);
                break;
            }
            case 'Titolare':
            case 'Addetto-Consegne':
            case 'Addetto-Vendite':{
                $is_cliente = false;
                $primary_key = $_SESSION['CodiceID'];

                $procedura = 'cambio_pssw_operatore';
                $hashed_passw = "SELECT passw FROM operatore WHERE CodiceID = '$primary_key'";
                $result = mysqli_query($dbc, $hashed_passw);
                if (!$result) {
                    die("DB query error: " . mysqli_error($dbc));
                }
                $row = mysqli_fetch_assoc($result);
                break;
            }
            case 'Fornitore':{
                $is_cliente = false;
                $primary_key = $_SESSION['CodiceID'];
                $procedura = 'cambio_pssw_fornitore';
                $hashed_passw = "SELECT passw FROM fornitore WHERE CodiceID = '$primary_key'";
                $result = mysqli_query($dbc, $hashed_passw);
                if (!$result) {
                    die("DB query error: " . mysqli_error($dbc));
                }
                $row = mysqli_fetch_assoc($result);
                break;
            }
        }

        //Da qui, avendo recuperato tutti i dati necessari, iniziano i controlli:
        //1. form non compilato correttamente
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)){ 
            $_SESSION['input_not_filled'] = true;
            $_SESSION['show_security_after_refresh'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        }
        //2. Controllo che la password corrente inserita sia effettivamente quella dell'utente
        else if(!password_verify($currentPassword, $row['passw'])){  
            $_SESSION['currentPassword_noMatch'] = true;
            $_SESSION['show_security_after_refresh'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        } 
        //3. Controllo che la nuova password sia diversa da quella attuale
        else if($newPassword == $currentPassword){
            $_SESSION['equal_password'] = true;
            $_SESSION['show_security_after_refresh'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        }
        //4. Se i controlli 1. e 2. passano, allora mi assicuro che la nuova password sia stata inserita correttamente
        else if ($newPassword !== $confirmPassword){
            $_SESSION['confirm'] = true;
            $_SESSION['show_security_after_refresh'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        }
        //5. Controllo dei requisiti password
        else if (
            strlen($newPassword) < 8 ||
            !preg_match('/[A-Z]/', $newPassword) ||
            !preg_match('/[a-z]/', $newPassword) ||
            !preg_match('/\d/', $newPassword) ||
            !preg_match('/[!@#$%^&*()_+\-=\[\]{};\'":\\\\|,.<>\/?]/', $newPassword)
        ) {
            $_SESSION['requiremets'] = true;
            $_SESSION['show_security_after_refresh'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        }
        //6. Tutti i controlli superati, quindi metto l'hash sulla nuova password e richiamo la procedura per il cambio password
        else {
            $newPassword_hashed = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $dbc->prepare("CALL $procedura(?, ?, @out_messaggio)");

            if($is_cliente) $stmt->bind_param("ss", $primary_key, $newPassword_hashed);
            else $stmt->bind_param("is", $primary_key, $newPassword_hashed);

            $stmt->execute();
            $stmt->close();

            // Recupero del valore dell'output
            $result_mess = mysqli_query($dbc, "SELECT @out_messaggio AS messaggio");
            $row = mysqli_fetch_assoc($result_mess);
            $messaggio = $row['messaggio'] ?? '';

            $_SESSION['password_changed'] = true;
            $_SESSION['show_security_after_refresh'] = true;
            header("Location:/owlbreak/website/loggedin_users/users_profile/user_profile.php");
            exit;
        }
    }
?>