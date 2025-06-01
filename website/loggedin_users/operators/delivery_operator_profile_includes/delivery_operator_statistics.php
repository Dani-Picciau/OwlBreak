<?php
    if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
        // Il file è stato eseguito direttamente
        http_response_code(403);
        header("location: /owlbreak/website/includes/error_403.php");
    }
    require_once(__DIR__ . '/../../../includes/loggedin.php');
    check_user_type('Addetto-Consegne');

    echo'ciao';
?>