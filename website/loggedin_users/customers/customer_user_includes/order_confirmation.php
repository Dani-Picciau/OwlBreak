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
        <title>Esito Ordine</title>
        <style>
            body { font-family: sans-serif; max-width: 600px; margin: 2em auto; }
            .success { color: green; }
            .error   { color: red; }
            ul { padding-left: 1.2em; }
        </style>
    </head>
    <body>
        <h1>Esito dell'ordine</h1>

        <ul>
            <?php foreach ($results as $r): ?>
                <li class="<?= $r['message'] === 'Ordine effettuato con successo' ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($r['product']) ?> (x<?= $r['quantity'] ?>):
                    <?= htmlspecialchars($r['message']) ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($all_ok): ?>
            <p>Tutti gli ordini sono stati effettuati con successo!</p>
        <?php else: ?>
            <p>Ci sono stati degli errori. Puoi tornare al <a href="user_cart.php">carrello</a> e riprovare.</p>
        <?php endif; ?>

        <!-- Form per tornare alla home in modo sicuro -->
        <form method="POST">
            <input type="hidden" name="clear_session" value="1">
            <button type="submit">Torna alla Home</button>
        </form>
    </body>
</html>

