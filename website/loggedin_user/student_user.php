<?php
	require('../includes/loggedin.php');
    check_user_type('Studente'); 
    echo "<h1>ciao ". $_SESSION['nome']."! (studente)</h1>";
?>

<a href="logout.php"><ion-icon name="log-out" id="logout"></ion-icon><br><b id="NomeLO">logout</b></a>