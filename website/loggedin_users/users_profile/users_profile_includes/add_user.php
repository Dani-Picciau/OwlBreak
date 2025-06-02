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

        <div class="success-message" id="successMessage">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#1f1f1f"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
            <span>Password cambiata con successo!</span>
        </div>

        <div class="error-message" id="errorMessage">
            <!-- da definire -->
        </div>

        <form class="password-form" id="passwordForm" method="post" action="users_profile_includes/change_password.php">

            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" placeholder="Inserisci nome cliente" required>
            </div>
            
            <div class="form-group">
                <label for="surname">Cognome</label>
                <input type="text" id="surname" name="surname" placeholder="Inserisci cognome cliente" required>
            </div>
            
            <div class="form-group">
                <label for="select-role">Ruolo</label>
                <div class="form-select">
                    <select>
                        <option value="Studente">Studente</option>
                        <option value="Personale-Ata">Personale-Ata</option>
                        <option value="Personale-Docente">Personale-Docente</option>
                        <option value="Personale-Segreteria">Personale-Segreteria</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="delivery-place">Luogo consegna</label>
                <input type="text" id="delivery-place" name="delivery-place" placeholder="Inserisci luogo di consegna" required>
            </div>

            <div class="form-group">
                <label for="defaultPassword">Default password</label>
                <div class="input-wrapper">
                    <input type="password" id="defaultPassword" name="defaultPassword" required>
                    <span onclick="togglePassword('defaultPassword')">
                        <!-- occhio aperto/chiuso -->
                        <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/></svg>

                        <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M792-56 624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM480-320q11 0 20.5-1t20.5-4L305-541q-3 11-4 20.5t-1 20.5q0 75 52.5 127.5T480-320Zm292 18L645-428q7-17 11-34.5t4-37.5q0-75-52.5-127.5T480-680q-20 0-37.5 4T408-664L306-766q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302ZM587-486 467-606q28-5 51.5 4.5T559-574q17 18 24.5 41.5T587-486Z"/></svg>
                    </span>
                </div>

                <div class="password-requirements">
                    <h4>Requisiti password:</h4>
                    <ul class="requirements-list">
                        <li><span class="requirement-icon requirement-invalid2" id="lengthReq2">✓</span> Almeno 8 caratteri</li>
                        <li><span class="requirement-icon requirement-invalid2" id="uppercaseReq2">✓</span> Una lettera maiuscola</li>
                        <li><span class="requirement-icon requirement-invalid2" id="lowercaseReq2">✓</span> Una lettera minuscola</li>
                        <li><span class="requirement-icon requirement-invalid2" id="numberReq2">✓</span> Un numero</li>
                        <li><span class="requirement-icon requirement-invalid2" id="specialReq2">✓</span> Un carattere speciale</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="defaultPasswordConfirm">Conferma default password</label>
                <div class="input-wrapper">
                    <input type="password" id="defaultPasswordConfirm" name="defaultPasswordConfirm" required>
                    <span onclick="togglePassword('defaultPasswordConfirm')">
                        <!-- occhio aperto/chiuso -->
                        <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/></svg>

                        <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M792-56 624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM480-320q11 0 20.5-1t20.5-4L305-541q-3 11-4 20.5t-1 20.5q0 75 52.5 127.5T480-320Zm292 18L645-428q7-17 11-34.5t4-37.5q0-75-52.5-127.5T480-680q-20 0-37.5 4T408-664L306-766q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302ZM587-486 467-606q28-5 51.5 4.5T559-574q17 18 24.5 41.5T587-486Z"/></svg>
                    </span>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary">Annulla</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Aggiungi utente</button>
            </div>
        </form>
    <?php
?>