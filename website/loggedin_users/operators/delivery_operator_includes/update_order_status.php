<?php
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        //Se la condizione è verificata significa che il file è stato cercato in modo diretto nella barra di ricerca e qualsiasi sia il tipo di utente, esso viene rispedito alla home. 
        http_response_code(403);
        session_start();
        require_once(__DIR__. '/../../../includes/redirect_users.php');
        redirect_users($_SESSION['user_type']);
    }

    //Dato che il file non può essere acceduto direttamente, ma solo tramite require(...) questa rappresenta un ulteriore precauzione per specificare quali tipi di utenti hanno accesso alla pagina
    require_once(__DIR__. '/../../../includes/loggedin.php'); 
    check_user_type('Addetto-Consegne'); 
    require_once('../../../includes/mysqli_connect_user.php');
?>

<?php
    // inizio file, niente whitespace!
    header('Content-Type: application/json; charset=utf-8');

    // cattura eventuali errori in JSON
    set_error_handler(function($errno, $errstr) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => "PHP Error: $errstr"]);
        exit;
    });

    // valida input
    $email     = $_POST['email']   ?? '';
    $data      = $_POST['date']    ?? '';
    $ora       = $_POST['time']    ?? '';
    $nomeProd  = $_POST['product'] ?? '';
    $operatore = $_SESSION['CodiceID'];

    // chiami la stored procedure
    $stmt = $dbc->prepare("CALL segna_ordine_consegnato(?, ?, ?, ?, ?, @msg);");
    $stmt->bind_param('ssssi', $data, $ora, $email, $nomeProd, $operatore);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $stmt->error]);
        exit;
    }

    // preleva OUT parameter
    $res = $dbc->query("SELECT @msg AS message");
    $msg = ($res->fetch_assoc())['message'] ?? '';

    // invia JSON di risposta
    echo json_encode(['success' => true, 'message' => $msg]);
    exit;
?>