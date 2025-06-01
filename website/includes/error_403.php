<?php
    session_start();

    // Se l'utente è loggato e ha un tipo di utente valido, lo reindirizzo
    if (isset($_SESSION['user_type'])) {
        switch ($_SESSION['user_type']) {
            case 'Studente':
            case 'Personale-Docente':
            case 'Personale-Ata': 
            case 'Personale-Segreteria': {
                $link = "/owlbreak/website/loggedin_users/customers/customer_user.php"; 
                break;
            }
            case 'Titolare': {
                $link = " /owlbreak/website/loggedin_users/operators/holder_operator_user.php"; 
                break;
            }
            case 'Addetto-Consegne': {
                $link = "/owlbreak/website/loggedin_users/operators/delivery_operator_user.php";
                break;
            }
            case 'Addetto-Vendite': {
                $link = "/owlbreak/website/loggedin_users/operators/sales_operator_user.php"; 
                break;
            }
            case 'Fornitore': {
                $link = "/owlbreak/website/loggedin_users/operators/supplier_user.php"; 
                break;
            }
            default: {
                $link = "/owlbreak/website/login_files/index.php"; // fallback alla login
                break;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>403 - Accesso Negato</title>
        <style>
            :root{
                --font-family-inter:Inter, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            }

            *{
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                font-family: var(--font-family-inter);
                font-weight: 500;
            }

            body {
                background-color: #f8f9fa;
                color: #343a40;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                text-align: center;
                padding: 20px;
            }

            .error-container {
                max-width: 500px;
                padding: 40px;
                border-radius: 16px;
                background: #fff;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            }

            h1 {
                font-size: 5rem;
                color: #dc3545;
            }

            h2 {
                font-size: 1.8rem;
                margin-top: 10px;
                margin-bottom: 20px;
            }

            p {
                font-size: 1rem;
                margin-bottom: 30px;
            }

            a.button {
                text-decoration: none;
                padding: 12px 24px;
                background-color: #007bff;
                color: #fff;
                border-radius: 8px;
                font-weight: bold;
                transition: background-color 0.3s;
                font-family: var(--font-family-inter);
                font-weight: 500;
            }

            a.button:hover {
                background-color: #0056b3;
            }

            .icon {
                margin-bottom: 20px;
                fill: #dc3545;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q54 0 104-17.5t92-50.5L228-676q-33 42-50.5 92T160-480q0 134 93 227t227 93Zm252-124q33-42 50.5-92T800-480q0-134-93-227t-227-93q-54 0-104 17.5T284-732l448 448Z"/></svg>
            <h1>403</h1>
            <h2>Accesso Non Autorizzato</h2>
            <p>Mi dispiace, non hai i permessi per accedere a questa pagina.</p>
            <a href="<?= $link ?>" class="button">Torna alla Home</a>
        </div>
    </body>
</html>


