<?php
    session_start(); // Serve per fare in modo che la sessione attiva continui ad accedere ai dati di sessione salvati
    session_unset(); // Elimino tutte le variabili di sessione
    session_destroy(); // Elimina la sessione attiva e tutti i dati salvati nel server
    header("location: /owlbreak/website/login_files/index.php");
    exit();
?>