<?php
    session_start();

    if(!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true){
        header("location: ../login_files/index.php");
        exit;
    }

	function check_user_type($expected_user_type) {
		if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== $expected_user_type) {
			require('../includes/redirect_users.php');
			redirect_users($_SESSION['user_type']);
			exit();
		}
	}
?>