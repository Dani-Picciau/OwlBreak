<?php
    require_once('../../includes/loggedin.php');
    // Tutti i tipi di utenti cliente che possono accedere a questa pagina
    check_user_type('Personale-Segreteria');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User profile</title>
        <!-- Normalize CSS -->
        <link rel="stylesheet" href="school_secretery_profile.css?v=<?=time()?>" />

        <!-- Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;500;700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php
            require('../../includes/header.php');
        ?>
        <h1>segretario</h1>
    </body>
</html>