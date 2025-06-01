<?php
    require_once(__DIR__. '/../../includes/loggedin.php'); 
    check_user_type('Titolare');
    echo "<h1>ciao titolare!</h1>";
?>