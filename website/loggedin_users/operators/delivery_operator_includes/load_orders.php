<?php
    if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
        //Se la condizione è verificata significa che il file è stato cercato in modo diretto nella barra di ricerca e qualsiasi sia il tipo di utente, esso viene rispedito alla home. 
        http_response_code(403);
        session_start();
        require_once(__DIR__. '/../../../includes/redirect_users.php');
        redirect_users($_SESSION['user_type']);
    }

    //Dato che il file non può essere acceduto direttamente, ma solo tramite require(...) questa rappresenta un ulteriore precauzione per specificare quali tipi di utenti hanno accesso alla pagina
    require_once(__DIR__. '/../../../includes/loggedin.php'); 
    check_user_type('Addetto-Consegne'); 
?>

<?php
    $CodiceID = $_SESSION['CodiceID']; 

    $sql = "SELECT o.emailCliente, o.data, o.ora, c.nome, c.cognome, c.luogoConsegna, o.consegnato
            FROM ordine o, cliente c
            WHERE c.email = o.emailCliente AND o.OperatoreID = '$CodiceID'
            GROUP BY o.emailCliente, o.data, o.ora
            ORDER BY o.data DESC, o.ora DESC
        ";
    if (!$result = mysqli_query($dbc, $sql)) {
        die("DB query error: " . mysqli_error($dbc));
    }

    $ordiniAttesa = [];
    $ordiniConsegnati = [];

    /* Una volta eseguita la query, tramite il while scorro tutta la tabella risultante e separo le tuple degli ordini consegnati dalle tuple degli ordini ancora non consegnati, inserendole in due array appositi in modo tale da avere i dati pronti all'uso.
    In questo modo posso anche avere il numero di tuple all'interno di ogni array, semplicemente facendo count(). */
    while ($ordine = mysqli_fetch_assoc($result)) {
        if ($ordine['consegnato'] == TRUE) {
            $ordiniConsegnati[] = $ordine;
        } else {
            $ordiniAttesa[] = $ordine;
        }
    }    
?>