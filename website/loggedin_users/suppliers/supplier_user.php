<?php
    require_once(__DIR__. '/../../includes/loggedin.php'); 
    check_user_type('Fornitore');
    echo "<h1>ciao, sono un fornitore!</h1>";
?>