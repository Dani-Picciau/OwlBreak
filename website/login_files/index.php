<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
            <h2>Log in into your account</h2>
        </div>
        <div class="container">
            <form action="index.php" method="post">
				<div>
					<input type="text" name="login-email" id="login-email" required>
					<label for="login-username">email</label>
				</div>

				<div>
					<input type="password" name="login-pwd" id="login-pwd" required>
					<label for="login-pwd">Password</label>
				</div>

				<a href="#" id="forgot-pwd">Forgot Password?</a>

                <div>
                    <?php
                        if($_SERVER['REQUEST_METHOD'] == 'POST'){
                            require('../includes/redirect_users.php');
                            require('../includes/mysqli_connect.php');

                            // Validate the email address:
                            if (!empty($_POST['login-email'])) {
                                $email = mysqli_real_escape_string($dbc, trim($_POST['login-email']));
                            } else {
                                $email = NULL;
                                echo '<p class="error-message">You forgot to enter your email address!</p>';
                            }

                            // Validate the password:
                            if (!empty($_POST['login-pwd'])) {
                                $passw = mysqli_real_escape_string($dbc, trim($_POST['login-pwd']));
                            } else {
                                $passw = NULL;
                                echo '<p class="error-message">You forgot to enter your password!</p>';
                            }

                            if($email && $passw){ //if the input are not empty
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
                                foreach($queries as $q){
                                    $result = mysqli_query($dbc, $q['query']) or trigger_error("Query: {$q['query']}\n<br>MySQL Error: " . mysqli_error($dbc));
                                    if (@mysqli_num_rows($result) == 1) { // A match was made.
                                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                        mysqli_free_result($result);
                                        if(password_verify($passw, $row['passw'])){
                                            switch ($q['type']) {
                                                case 'cliente': {
                                                    session_start();
                                                    $_SESSION['loggato'] = true;
                                                    $_SESSION['user_type'] = $row['tipoCliente'];
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
                                                    //Store the user info in the session:
                                                    $_SESSION['email'] = $row['email']; 
                                                    $_SESSION['nome'] = $row['nome']; 
                                                    $_SESSION['cognome'] = $row['cognome']; 
                                                    $_SESSION['ruolo'] = $row['ruolo'];
                                                    //Redirect the user
                                                    redirect_users($row['ruolo']);
                                                    break;
                                                }
                                                case 'fornitore': {
                                                    session_start();
                                                    $_SESSION['loggato'] = true;
                                                    $_SESSION['user_type'] = 'Fornitore';
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
                                echo '<p class="error-message">L\'account non esiste.</p>';
                            } else echo '<p class="error-message">Si prega di riprovare.</p>';
                            mysqli_close($dbc); 
                        }
                    ?>
                </div>

				<button class="button" id="btn-submit">
					<span class="button--text">Log In</span>

					<!-- Air -->
					<div class="button--loader">
						<div></div>
						<div></div>
						<div></div>
					</div>
					<!-- End Air -->
				</button>
			</form>
        </div>
        <script src="script.js"></script> 
    </body>
</html>