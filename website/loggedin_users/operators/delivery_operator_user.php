<?php
	require_once('../../includes/loggedin.php');
    require_once('../../includes/mysqli_connect_user.php');

    check_user_type('Addetto-Consegne');
    

    $CodiceID = $_SESSION['CodiceID']; 

    $sql = "SELECT o.emailCliente, o.data, o.ora, c.nome, c.cognome, c.luogoConsegna, o.consegnato
            FROM ordine o, cliente c
            WHERE c.email = o.emailCliente AND o.OperatoreID = '$CodiceID'
            GROUP BY o.emailCliente, o.data, o.ora
            ORDER BY o.data DESC, o.ora DESC
        ";
    if (!$result = mysqli_query($dbc, $sql)) {
        die("DB query error: " . mysqli_error($dbc));
    }

    $ordiniAttesa = [];
    $ordiniConsegnati = [];

    /* Una volta eseguita la query, tramite il while scorro tutta la tabella risultante e separo le tuple degli ordini consegnati dalle tuple degli ordini ancora non consegnati, inserendole in due array appositi in modo tale da avere i dati pronti all'uso.
    In questo modo posso anche avere il numero di tuple all'interno di ogni array, semplicemente facendo count(). */
    while ($ordine = mysqli_fetch_assoc($result)) {
        if ($ordine['consegnato'] == TRUE) {
            $ordiniConsegnati[] = $ordine;
        } else {
            $ordiniAttesa[] = $ordine;
        }
    }                 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/owlbreak/website/images/logo.svg" type="svg">
        <title>User profile</title>
        <!-- Normalize CSS -->
        <link rel="stylesheet" href="delivery_operator_user.css?v=<?=time()?>" />

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
        
        <div class="content-box"  id="page-content">
            <div class="welcome-box">
                <p>Pannello consegne</p>
            </div>
            <div class="profile-box">
                <div class="menu-box">
                    <div class="menu-category" data-category="attesa">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M680-80q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80Zm67-105 28-28-75-75v-112h-40v128l87 87Zm-547 65q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v250q-18-13-38-22t-42-16v-212h-80v120H280v-120h-80v560h212q7 22 16 42t22 38H200Zm280-640q17 0 28.5-11.5T520-800q0-17-11.5-28.5T480-840q-17 0-28.5 11.5T440-800q0 17 11.5 28.5T480-760Z"/></svg>
                            In attesa
                        </div>
                        <span>
                            <p><?= count($ordiniAttesa) ?></p>
                        <span>
                    </div>
                    <div class="menu-category" data-category="consegnato">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="m424-312 282-282-56-56-226 226-114-114-56 56 170 170ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>
                            Consegnati
                        </div>
                        <span>
                            <p><?= count($ordiniConsegnati) ?></p>
                        <span>
                    </div>
                </div>
                <div class="separator2"></div>
                <div class="container">
                    <div class="waiting-box">
                        <?php if (count($ordiniAttesa) > 0): ?>
                            <?php require('delivery_operator_includes/waiting_panel.php'); ?>
                        <?php endif; ?>
                    </div>
                    <div class="delivered-box">
                        <?php if (count($ordiniConsegnati) > 0): ?>
                            <?php require('delivery_operator_includes/delivered_panel.php'); ?>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>

        <script src="delivery_operator_user.js?v=1.02"></script>
    </body>
</html>
