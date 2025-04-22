<?php
	require('../includes/loggedin.php');
    check_user_type(['Personale-Docente', 'Personale-Ata']);

    echo "<h1>ciao professore/ata!</h1>";
?>