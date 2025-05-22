<?php
	require('../../includes/loggedin.php');
    require_once('../../includes/mysqli_connect_user.php');

    check_user_type('Addetto-Consegne');
    echo "<h1>ciao addetto consegne!</h1>";
?>