<?php
    require('../includes/loggedin.php');
    check_user_type('Studente'); 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User account</title>
        <!-- Normalize CSS -->
        <link rel="stylesheet" href="student_user.css" />
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <nav>
                <div class="description">
                    <div class="site-name">
                        <img src="../images/logo.svg" alt="OwlBreak Logo" class="logo">
                        <h3>OwlBreak</h3>
                    </div>
                    <h1>Portale Studenti</h1>
                </div>
                <div class="account">
                    <div>
                        <?php
                            echo "<p>". $_SESSION['nome'] . "</p>";
                        ?>
                    </div>
                    <div class="settings">
                        <?php
                            echo "<p>". substr($_SESSION['nome'], 0, 1) . substr($_SESSION['cognome'], 0, 1) ."</p>";
                        ?>
                    </div>
                </div>
            </nav>
        </header>
        <a href="logout.php"><ion-icon name="log-out" id="logout"></ion-icon><br><b id="NomeLO">logout</b></a>
    </body>
</html>
