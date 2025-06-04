<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" href="/owlbreak/website/images/logo.svg" type="svg">
        <title>Login Page</title>

        <!-- Normalize CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/modern-normalize@2.0.0/modern-normalize.min.css" />
        <link rel="stylesheet" href="index.css?v=<?=time()?>" />

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="logo-container">
            <img src="../images/logo.svg" alt="Logo OwlBreak" />
        </div>
        <div class="loader-container">
            <svg viewBox="0 0 925 160" preserveAspectRatio="xMidYMid meet">
                <text x="50%" y="50%" dy=".32em" text-anchor="middle" class="text-body">
                    Welcome to OwlBreak.
                </text>
            </svg>
        </div>
        <div class="log-in">
            <h2>Accedi al tuo account</h2>
        </div>
        <div class="container">
            <form action="index.php" method="POST">
				<div>
					<input type="text" name="login-email" id="login-email" required>
					<label for="login-username">email</label>
				</div>

                <div class="input-wrapper">
                    <input type="password" name="login-pwd" id="login-passw" required>
					<label for="login-pwd">Password</label>
                    <span onclick="togglePassword('login-passw')">
                        <!-- occhio aperto/chiuso -->
                        <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/></svg>

                        <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M792-56 624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM480-320q11 0 20.5-1t20.5-4L305-541q-3 11-4 20.5t-1 20.5q0 75 52.5 127.5T480-320Zm292 18L645-428q7-17 11-34.5t4-37.5q0-75-52.5-127.5T480-680q-20 0-37.5 4T408-664L306-766q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302ZM587-486 467-606q28-5 51.5 4.5T559-574q17 18 24.5 41.5T587-486Z"/></svg>
                    </span>
                </div>

                <div>
                    <?php
                        if($_SERVER['REQUEST_METHOD'] == 'POST'){
                            require('../includes/redirect_users.php');
                            require('../includes/mysqli_connect.php');

                            // Controllo che l'email non sia vuota, se ok mando i dati con POST
                            if (!empty($_POST['login-email'])) {
                                $email = mysqli_real_escape_string($dbc, trim($_POST['login-email']));
                            } else {
                                $email = NULL;
                                echo '<p class="error-message">Hai dimenticato di inserire l\'email!</p>';
                            }

                            // Controllo che la password non sia vuota, se ok mando i dati con POST
                            if (!empty($_POST['login-pwd'])) {
                                $passw = mysqli_real_escape_string($dbc, trim($_POST['login-pwd']));
                            } else {
                                $passw = NULL;
                                echo '<p class="error-message">Hai dimenticato di inserire la password!</p>';
                            }

                            if($email && $passw){ // Se gli input non sono vuoti.
                                $queries = [
                                    [
                                        "query" => "SELECT * FROM cliente WHERE email = '$email'",
                                        "type" => "cliente"
                                    ],
                                    [
                                        "query" => "SELECT * FROM operatore WHERE email = '$email'",
                                        "type" => "operatore"
                                    ],
                                    [
                                        "query" => "SELECT * FROM fornitore WHERE email = '$email'",
                                        "type" => "fornitore"
                                    ]
                                ];
                                $found = false;
                                foreach($queries as $q){
                                    $result = mysqli_query($dbc, $q['query']) or trigger_error("Query: {$q['query']}\n<br>MySQL Error: " . mysqli_error($dbc));
                                    if (@mysqli_num_rows($result) == 1) { // A match was made.
                                        $found = true;
                                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                        mysqli_free_result($result);
                                        if(password_verify($passw, $row['passw'])){
                                            switch ($q['type']) {
                                                case 'cliente': {
                                                    session_start();
                                                    $_SESSION['loggato'] = true;
                                                    $_SESSION['user_type'] = $row['tipoCliente'];
                                                    $_SESSION['db_pass'] = 'Cliente';

                                                    //Store the user info in the session:
                                                    $_SESSION['email'] = $row['email']; 
                                                    $_SESSION['nome'] = $row['nome']; 
                                                    $_SESSION['cognome'] = $row['cognome']; 
                                                    $_SESSION['luogoConsegna'] = $row['luogoConsegna']; 
                                                    //Redirect the user
                                                    redirect_users($row['tipoCliente']);
                                                    break;
                                                }
                                                case 'operatore': {
                                                    session_start();
                                                    $_SESSION['loggato'] = true;
                                                    $_SESSION['user_type'] = $row['ruolo'];
                                                    $_SESSION['db_pass'] = 'Operatore';

                                                    //Store the user info in the session:
                                                    $_SESSION['CodiceID'] = $row['CodiceID'];
                                                    $_SESSION['email'] = $row['email']; 
                                                    $_SESSION['nome'] = $row['nome']; 
                                                    $_SESSION['cognome'] = $row['cognome']; 
                                                    //Redirect the user
                                                    redirect_users($row['ruolo']);
                                                    break;
                                                }
                                                case 'fornitore': {
                                                    session_start();
                                                    $_SESSION['loggato'] = true;
                                                    $_SESSION['user_type'] = 'Fornitore';
                                                    $_SESSION['db_pass'] = 'Fornitore';

                                                    //Store the user info in the session:
                                                    $_SESSION['email'] = $row['email']; 
                                                    $_SESSION['nomeAzienda'] = $row['nomeAzienda'];
                                                    $_SESSION['nomeTitolare'] = $row['nomeTitolare'];
                                                    //Redirect the user
                                                    redirect_users('Fornitore');
                                                    break;
                                                }
                                                break;
                                            }
                                        } else {
                                            echo '<p class="error-message">Password non corretta.</p>';
                                            break;
                                        }
                                    }
                                }
                                if (!$found) echo '<p class="error-message">L\'account non esiste.</p>';
                            }
                            mysqli_close($dbc); 
                        }
                    ?>
                </div>

				<button type="submit" class="button" id="btn-submit">
					<span class="button--text">Accedi</span>
				</button>
			</form>
        </div>
        <script src="index.js"></script> 
    </body>
</html>