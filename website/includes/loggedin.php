<?php
    if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
        //Se la condizione è verificata significa che il file è stato cercato in modo diretto nella barra di ricerca e qualsiasi sia il tipo di utente, esso viene rispedito alla home. 
        http_response_code(403);
        header("location: /owlbreak/website/includes/error_403.php"); 
    }
?>

<?php
    session_start();

	if(!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true){
        header("location: /owlbreak/website/login_files/index.php");
        exit;
    }

	/*	
		In ogni pagina relativa ad un accesso, oltre a utilizzare 'if(!isset($_SESSION['loggato']) || $_SESSION['loggato'] 	!== true)' per evitare che l'utente possa cambiare pagine direttamente dall'interfaccia del login, richiamando logged_in.php ho la possibilità di utilizzare la funzione check_user_type().

		Avendo un solo login per diversi accessi, il controllo con solo 'loggato' non basterebbe e l'utente una volta eseguito l'accesso potrebbe cambiare manualmente account. Per evitare questo, all'interno di ogni pagina degli account devo richiamare check_user_type() e specificare all'interno i soli account che hanno accesso alla pagina.

		Ad esempio, se un utente è loggato come 'Studente' ma prova ad accedere a una pagina per 'Operatori', verrà reindirizzato automaticamente alla pagina corretta in base al suo ruolo,
   		perché 'Studente' non è tra i tipi di utente consentiti (non viene specificato nella chiamata a check_user_type()).
	*/

	//I tre puntini servono per dire che la funzione può ricevere un numero variabile di argomenti, che verranno raccolti in un array.
	function check_user_type(...$expected_user_types) {
		// Verifica se il tipo di utente corrente è tra quelli consentiti
		if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], $expected_user_types)) {
			require('redirect_users.php');
			redirect_users($_SESSION['user_type']);
			exit();
		}
	}


	// ——— Gestione cart_owner ———
	//Se l'utente non ha ancora un proprietario del carrello, lo inizializzo
	if (!isset($_SESSION['cart_owner'])) {
    	// il cart_owner è l'email dell'utente loggato
    	$_SESSION['cart_owner'] = $_SESSION['email'];
	}

	// Se il proprietario salvato non coincide con l'email corrente, significa che è cambiato utente e quindi resetto il carrello
	if ($_SESSION['cart_owner'] !== $_SESSION['email']) {
		unset($_SESSION['cart']);
		$_SESSION['cart_owner'] = $_SESSION['email'];
	}
?>