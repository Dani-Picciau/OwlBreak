<?php
	require('../includes/loggedin.php');
    check_user_type('Addetto-Vendite'); // ← solo studenti ammessi
    echo "<h1>ciao addetto vendite!</h1>";
?>