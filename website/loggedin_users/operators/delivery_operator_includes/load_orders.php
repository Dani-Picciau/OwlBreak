<?php
    //Per evitare di accedere direttamente alla pagina
    require_once(__DIR__. '/../../../includes/loggedin.php'); 
    check_user_type('Addetto-Consegne'); 

    if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
        // Il file è stato eseguito direttamente
        http_response_code(403);
        header("location: /owlbreak/website/includes/error_403.php");
    }
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