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