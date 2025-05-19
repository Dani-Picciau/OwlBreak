<?php
    session_start(); //is needed to continue the active session to access to the session data stored
    session_unset(); //this function delete all session variables
    session_destroy(); //this function delete the active session and all the data stored in the server
    header("location: /owlbreak/website/login_files/index.php");
    exit();
?>