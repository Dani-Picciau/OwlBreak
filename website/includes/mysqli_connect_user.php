<?php
    require_once('loggedin.php');

    // Verifica che siano settate anche le credenziali per il database
	if (!isset($_SESSION['user_type'], $_SESSION['db_pass'])) {
		// Se mancano, significa che qualcosa è andato storto nel login
		// e non è sicuro procedere
		header("location: /owlbreak/website/login_files/index.php");
		exit;
	}

    define('DB_HOST', 'localhost');
    define('DB_USER', $_SESSION['user_type']);
    define('DB_PASSWORD', $_SESSION['db_pass']);
    define('DB_NAME', 'owlbreak');

    $dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        OR die('Could not connect to MySQL: ' . mysqli_connect_error());
    mysqli_set_charset($dbc, 'utf8');
?>