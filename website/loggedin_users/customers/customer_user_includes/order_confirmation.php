<?php
    //Dato che il file non può essere acceduto direttamente, ma solo tramite require(...) questa rappresenta un ulteriore precauzione per specificare quali tipi di utenti hanno accesso alla pagina
    require_once(__DIR__ . '/../../../includes/loggedin.php');
    check_user_type('Studente', 'Personale-Docente', 'Personale-Ata', 'Personale-Segreteria');
?>

<?php
    // Se l'utente ha cliccato il pulsante per tornare alla home
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_session'])) {
        unset($_SESSION['order_results'], $_SESSION['order_success']);
        header('Location: ../customer_user.php');
        exit;
    }

    if (!isset($_SESSION['order_results']) || !isset($_SESSION['order_success'])) {
        //Controllo che le variabili di sessione provenienti da "order_submit.php" siano state create, se la condizione è verificata significa quindi che la pagina è stata acceduta in modo diretto e reindirizza alla home
        http_response_code(403);
        session_start();
        require_once(__DIR__. '/../../../includes/redirect_users.php');
        redirect_users($_SESSION['user_type']);
    }

    $results = $_SESSION['order_results'];
    $all_ok  = $_SESSION['order_success'];
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/owlbreak/website/images/logo.svg" type="svg">
        <title>Esito Ordine</title>

        <!-- Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        
        <style>
            :root {
                /* colori per light mode */
                --gradient-start: #c5e8f7;
                --gradient-end: #e9d5f0;
                --background-color: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
                --header-bg: rgba(255, 255, 255, 0.2);
                --content-bg: white;
                --hover-bg: rgb(241 245 249 / 1);
                --text-color: black;
                --separator-color: rgb(226, 232, 240);
                --border-color: rgb(212, 217, 222);
                --card-header-bg: rgb(240, 245, 249);
                --accent-color: rgb(79 70 229 / 1);
                --back-top-bg: rgba(255, 255, 255, 0.8);
                --back-top-hover: white;
                --card-overlay-gradient: linear-gradient(180deg, rgba(21, 22, 24, 0.24) 0%, #151618 100%);
                --card-text-color: #fff;
                --shadow-color: rgba(0, 0, 0, 0.2);
                --unavailable-label-bg: rgb(200, 0, 0);
                --unavailable-label-color: #fff;
                --unavailable-text-color: #666;
                --initials-bg: cadetblue;
                --font-family-inter: Inter, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                
                /* Additional colors for order results */
                --success-color: #10B981;
                --success-bg: #D1FAE5;
                --error-color: #EF4444;
                --error-bg: #FEE2E2;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: var(--font-family-inter);
                background: var(--background-color);
                min-height: 100vh;
                color: var(--text-color);
                line-height: 1.6;
                font-weight: 400;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 2rem 1rem;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .card {
                background: var(--content-bg);
                border-radius: 16px;
                box-shadow: 0 10px 25px var(--shadow-color);
                overflow: hidden;
                backdrop-filter: blur(10px);
                border: 1px solid var(--border-color);
            }

            .header {
                background: var(--header-bg);
                padding: 2rem;
                text-align: center;
                backdrop-filter: blur(10px);
                border-bottom: 1px solid var(--separator-color);
            }

            .header h1 {
                font-size: 2rem;
                font-weight: 600;
                color: var(--text-color);
                margin-bottom: 0.5rem;
            }

            .header .icon {
                font-size: 3rem;
                margin-bottom: 1rem;
                opacity: 0.8;
            }

            .content {
                padding: 2rem;
            }

            .results-list {
                list-style: none;
                margin-bottom: 2rem;
            }

            .result-item {
                display: flex;
                align-items: center;
                padding: 1rem;
                margin-bottom: 0.75rem;
                border-radius: 12px;
                border-left: 4px solid transparent;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }

            .result-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .result-item.success {
                background: var(--success-bg);
                border-left-color: var(--success-color);
            }

            .result-item.error {
                background: var(--error-bg);
                border-left-color: var(--error-color);
            }

            .result-icon {
                margin-right: 1rem;
                font-size: 1.5rem;
            }

            .result-item.success .result-icon {
                color: var(--success-color);
            }

            .result-item.error .result-icon {
                color: var(--error-color);
            }

            .result-content {
                flex: 1;
            }

            .product-name {
                font-weight: 600;
                margin-bottom: 0.25rem;
            }

            .result-message {
                font-size: 0.9rem;
                opacity: 0.8;
            }

            .summary {
                background: var(--card-header-bg);
                padding: 1.5rem;
                border-radius: 12px;
                margin-bottom: 2rem;
                text-align: center;
                border: 1px solid var(--separator-color);
            }

            .summary.all-success {
                background: var(--success-bg);
                border-color: var(--success-color);
            }

            .summary.has-errors {
                background: var(--error-bg);
                border-color: var(--error-color);
            }

            .summary-icon {
                font-size: 2.5rem;
                margin-bottom: 0.5rem;
                display: block;
            }

            .summary.all-success .summary-icon {
                color: var(--success-color);
            }

            .summary.has-errors .summary-icon {
                color: var(--error-color);
            }

            .summary p {
                font-size: 1.1rem;
                font-weight: 500;
                margin-bottom: 0.5rem;
            }

            .summary a {
                color: var(--accent-color);
                text-decoration: none;
                font-weight: 500;
                transition: opacity 0.3s ease;
            }

            .summary a:hover {
                opacity: 0.8;
            }

            .action-buttons {
                display: flex;
                gap: 1rem;
                justify-content: center;
                margin-top: 2rem;
            }

            .btn {
                padding: 0.875rem 2rem;
                border: none;
                border-radius: 12px;
                font-weight: 600;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                font-family: var(--font-family-inter);
            }

            .btn-primary {
                background: var(--accent-color);
                color: white;
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
            }

            .btn-secondary {
                background: var(--back-top-bg);
                color: var(--text-color);
                border: 1px solid var(--border-color);
            }

            .btn-secondary:hover {
                background: var(--back-top-hover);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .btn-icon {
                font-size: 1.2rem;
            }

            @media (max-width: 640px) {
                .container {
                    padding: 1rem;
                }

                .header {
                    padding: 1.5rem;
                }

                .header h1 {
                    font-size: 1.5rem;
                }

                .content {
                    padding: 1.5rem;
                }

                .action-buttons {
                    justify-content: center;
                    flex-direction: row;
                }

                .result-item {
                    flex-direction: column;
                    text-align: center;
                    gap: 0.5rem;
                }

                .result-icon {
                    margin-right: 0;
                }
            }

            /* Animation for page load */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .card {
                animation: fadeInUp 0.6s ease-out;
            }

            .result-item {
                animation: fadeInUp 0.6s ease-out;
            }

            .result-item:nth-child(1) { animation-delay: 0.1s; }
            .result-item:nth-child(2) { animation-delay: 0.2s; }
            .result-item:nth-child(3) { animation-delay: 0.3s; }
            .result-item:nth-child(4) { animation-delay: 0.4s; }
            .result-item:nth-child(5) { animation-delay: 0.5s; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="header">
                    <span class="material-icons icon">receipt_long</span>
                    <h1>Esito dell'Ordine</h1>
                </div>

                <div class="content">
                    <ul class="results-list">
                        <?php foreach ($results as $index => $r): ?>
                            <li class="result-item <?= $r['message'] === 'Ordine effettuato con successo' ? 'success' : 'error' ?>">
                                <span class="material-icons result-icon">
                                    <?= $r['message'] === 'Ordine effettuato con successo' ? 'check_circle' : 'error' ?>
                                </span>
                                <div class="result-content">
                                    <div class="product-name">
                                        <?= htmlspecialchars($r['product']) ?> (x<?= $r['quantity'] ?>)
                                    </div>
                                    <div class="result-message">
                                        <?= htmlspecialchars($r['message']) ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="summary <?= $all_ok ? 'all-success' : 'has-errors' ?>">
                        <span class="material-icons summary-icon">
                            <?= $all_ok ? 'check_circle' : 'warning' ?>
                        </span>
                        <?php if ($all_ok): ?>
                            <p>Tutti gli ordini sono stati effettuati con successo!</p>
                        <?php else: ?>
                            <p>Ci sono stati degli errori durante l'elaborazione.</p>
                        <?php endif; ?>
                    </div>

                    <div class="action-buttons">
                        <!-- Form per tornare alla home in modo sicuro -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="clear_session" value="1">
                            <button type="submit" class="btn btn-primary">
                                <span class="material-icons btn-icon">home</span>
                                Torna alla Home
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>