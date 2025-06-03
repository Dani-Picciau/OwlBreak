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
            <form>
                <span>
                    <div class="editable-delivery">
                        <span>L'ordine verrà consegnato presso:</span>
                        <div>
                            <input type="text" value="<?php echo $_SESSION['luogoConsegna']; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M840-680v480q0 33-23.5 56.5T760-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h480l160 160Zm-80 34L646-760H200v560h560v-446ZM480-240q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM240-560h360v-160H240v160Zm-40-86v446-560 114Z"/></svg>
                        </div>
                    </div>
                </span>
            </form>
            <span>
                Scegli il tuo luogo di consegna.
            </span>
        <?php
    }
?>