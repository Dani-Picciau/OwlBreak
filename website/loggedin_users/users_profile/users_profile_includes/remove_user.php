<?php
    if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
        //Se la condizione è verificata significa che il file è stato cercato in modo diretto nella barra di ricerca e qualsiasi sia il tipo di utente, esso viene rispedito alla home. 
        http_response_code(403);
        session_start();
        require_once(__DIR__. '/../../../includes/redirect_users.php');
        redirect_users($_SESSION['user_type']);
    }

    //Dato che il file non può essere acceduto direttamente, ma solo tramite require(...) questa rappresenta un ulteriore precauzione per specificare quali tipi di utenti hanno accesso alla pagina
    require_once(__DIR__. '/../../../includes/loggedin.php');
?>

<?php
    ?>
        <!-- <div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="m438-338 226-226-57-57-169 169-84-84-57 57 141 141Zm42 258q-139-35-229.5-159.5T160-516v-244l320-120 320 120v244q0 152-90.5 276.5T480-80Zm0-84q104-33 172-132t68-220v-189l-240-90-240 90v189q0 121 68 220t172 132Zm0-316Z"/>
            </svg>
            Cambia password
        </div> -->

        <div class="success-message" id="successMessage-removeUser">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
            <span>Utente rimosso con successo!</span>
        </div>

        <div class="error-message" id="errorMessage-removeUser">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
            <?php
                if (isset($_SESSION['input_not_filled_remove_user'])) {
                    ?> <span>È necessario compilare tutti i campi, si prega di riprovare.</span> <?php
                }
                elseif (isset($_SESSION['removed_user_error'])) {
                    ?> <span>Il cliente inserito non esiste, si prega di riprovare.</span> <?php
                }
            ?>
        </div>

        <form class="password-form" id="removeUserForm" method="POST" action="users_profile_includes/removing_user.php">

            <div class="form-group">
                <label for="Email">Email</label>
                <input type="text" id="Email" name="Email" placeholder="Inserisci email cliente" required>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="reset-form-remove-user">Annulla</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Rimuovi utente</button>
            </div>
        </form>
    <?php
?>