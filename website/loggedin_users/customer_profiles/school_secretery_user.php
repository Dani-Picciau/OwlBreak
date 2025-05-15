<?php
	require('../includes/loggedin.php');
    check_user_type('Personale-Segreteria'); // ← solo studenti ammessi

    echo "<h1>ciao segreteria!</h1>";
?>