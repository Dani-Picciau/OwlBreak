<?php
    if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
        //Se la condizione è verificata significa che il file è stato cercato in modo diretto nella barra di ricerca e qualsiasi sia il tipo di utente, esso viene rispedito alla home. 
        http_response_code(403);
        session_start();
        require_once(__DIR__. '/../../../includes/redirect_users.php');
        redirect_users($_SESSION['user_type']);
    }

    //Dato che il file non può essere acceduto direttamente, ma solo tramite require(...) questa rappresenta un ulteriore precauzione per specificare quali tipi di utenti hanno accesso alla pagina
    require_once(__DIR__ . '/../../../includes/loggedin.php');
    check_user_type('Studente', 'Personale-Docente', 'Personale-Ata', 'Personale-Segreteria');
?>

<?php
    if($_SESSION['user_type'] === 'Studente') {
        ?>
            <span>
                L'ordine verrà consegnato presso: <?php echo $_SESSION['luogoConsegna']; ?>
            </span>
            <span>
                Luogo di consegna non modificabile
            </span>
        <?php
    } else{
        ?>
            <form method="POST" action="customer_user_includes/change_place_delivery.php">
                <span>
                    <div class="editable-delivery">
                        <span>L'ordine verrà consegnato presso:</span>
                        <div>
                            <input type="text" id="luogoConsegna" name="luogoConsegna" value="<?php echo $_SESSION['luogoConsegna']; ?>" required>
                            <button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M840-680v480q0 33-23.5 56.5T760-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h480l160 160Zm-80 34L646-760H200v560h560v-446ZM480-240q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM240-560h360v-160H240v160Zm-40-86v446-560 114Z"/></svg>
                            </button>
                        </div>
                    </div>
                </span>
            </form>
            <span>
                Scegli il tuo luogo di consegna.
            </span>
            <section class="success-message" id="successMessage-placeDelivery">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#1f1f1f"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                <span>Luogo di consegna aggiornato con successo!</span>
            </section>
            <section class="error-message" id="errorMessage-placeDelivery">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#1f1f1f"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                <span>È necessario compilare tutti i campi, si prega di riprovare.</span>
            </section>
        <?php
    }
?>
    <script>
        /*Qui gestisco il tempo di visualizzazione per i box di:
        - successo cambiamento luogo di consegna;
        - errore cambiamento luogo di consegna.
        Una volta che i messaggi vengono visualizzati, scompaiono dopo 7000ms=7s.
        
        */
        document.addEventListener('DOMContentLoaded', function() {
        const successMessage_placeDelivery = document.getElementById('successMessage-placeDelivery');
        const errorMessage_placeDelivery = document.getElementById('errorMessage-placeDelivery');

        if (successMessage_placeDelivery && successMessage_placeDelivery.style.display !== 'none') {
            setTimeout(() => {
            successMessage_placeDelivery.style.display = 'none';
            }, 7000);
        }

        if (errorMessage_placeDelivery && errorMessage_placeDelivery.style.display !== 'none') {
            setTimeout(() => {
            errorMessage_placeDelivery.style.display = 'none';
            }, 7000);
        }
        });
    </script>
<?php