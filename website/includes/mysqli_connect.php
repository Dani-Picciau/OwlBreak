<?php
    if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
        //Se la condizione è verificata significa che il file è stato cercato in modo diretto nella barra di ricerca e qualsiasi sia il tipo di utente, esso viene rispedito alla home. 
        http_response_code(403);
        header("location: /owlbreak/website/includes/error_403.php"); 
    }
?>

<?php
    define('db_host', 'localhost');
    define('db_user', 'root');
    define('db_password', '');
    define('db_name', 'owlbreak');

    // Creo la connessione al database
    $dbc = @mysqli_connect(db_host, db_user, db_password, db_name) OR die('Could not connect to MySQL: ' . mysqli_connect_error() );

    // Definisco la cifratura del database
    mysqli_set_charset($dbc, 'utf8');
?>