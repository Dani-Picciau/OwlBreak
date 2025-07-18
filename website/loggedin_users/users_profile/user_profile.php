<?php
    require_once(__DIR__ . '/../../includes/loggedin.php');
    require_once('../../includes/mysqli_connect_user.php');
    // Nessun controllo con check_user_type perché tutti gli utenti possono accedere alla pagina
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/owlbreak/website/images/logo.svg" type="svg">
        <title>User profile</title>
        <!-- Normalize CSS -->
        <link rel="stylesheet" href="user_profile.css?v=<?=time()?>" />

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
                <p>Il Tuo Profilo</p>
            </div>
            <div class="profile-box">
                <div class="menu-box">
                    <div class="menu-category" data-category="informazioni-personali">
                       <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
                        Informazioni personali
                    </div>
                    <div class="menu-category" data-category="sicurezza">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm240-120q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg>
                        Sicurezza
                    </div>
                    <div class="menu-category" data-category="statistiche">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M120-120v-80l80-80v160h-80Zm160 0v-240l80-80v320h-80Zm160 0v-320l80 81v239h-80Zm160 0v-239l80-80v319h-80Zm160 0v-400l80-80v480h-80ZM120-327v-113l280-280 160 160 280-280v113L560-447 400-607 120-327Z"/></svg>
                        Statistiche
                    </div>
                    <?php
                        if($_SESSION['user_type'] === 'Personale-Segreteria'){
                            ?>
                                <div class="menu-category" data-category="aggiunta-utente">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/></svg>
                                    Aggiungi utente
                                </div>
                                <div class="menu-category" data-category="modifica-utente">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M640-520v-80h240v80H640Zm-280 40q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/></svg>
                                    Rimuovi utente
                                </div>
                            <?php
                        }
                    ?>
                </div>
                <div class="separator2"></div>
                <div class="container">
                    <?php $email = $_SESSION['email']; ?>
                    <div class="personal-information-box">
                        <?php require('users_profile_includes/user_information.php'); ?>
                    </div>
                    <div class="security-box">
                        <?php require('users_profile_includes/user_security.php'); ?>
                    </div>
                    <div class="statistics-box">
                        <?php 
                            switch ($_SESSION['user_type']) {
                                case 'Studente':
                                case 'Personale-Docente':
                                case 'Personale-Ata':
                                case 'Personale-Segreteria':{
                                    require('../customers/customer_profile_includes/customer_statistics.php');
                                    break;
                                }
                                case 'Addetto-Consegne':{
                                    require('../operators/delivery_operator_profile_includes/delivery_operator_statistics.php');
                                    break;
                                }
                            }
                        ?>
                    </div>
                    <?php
                        if($_SESSION['user_type'] === 'Personale-Segreteria'){
                            ?>
                                <div class="add-user-box">
                                    <?php require('users_profile_includes/add_user.php'); ?>
                                </div>
                                <div class="remove-user-box">
                                    <?php require('users_profile_includes/remove_user.php'); ?>
                                </div>
                            <?php
                        }
                    ?>  
                </div>
            </div>
        </div>

        <script src="user_profile.js?v=1.02"></script>

        <!-- Solo quando faccio il submit del bottone in "user_security.php" imposto anche una variabile di sessione che al refresh della pagina mi permette  di mantenere selezionata la categoria "sicurezza". Al prossimo refresh la categoria selezionata torna ad essere la prima. 
        Lo script in questo punto, invece che nel file js dedicato, è necessario per riuscire a gestire php e javascript assieme -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
            const menuItems = document.querySelectorAll('.menu-category');
            
                // Controllo la variabile di sessione per capire su quale categoria fermarmi dopo il submit del bottone
                <?php if (isset($_SESSION['show_security_after_refresh']) && $_SESSION['show_security_after_refresh']): ?>
                    if (menuItems[1]) menuItems[1].click();
                    //Una volta settata la categoria, rimuovo la variabile di sessione
                    <?php unset($_SESSION['show_security_after_refresh']); ?>

                <?php elseif (isset($_SESSION['show_add_user']) && $_SESSION['show_add_user']): ?>
                    if (menuItems[3]) menuItems[3].click();
                    //Una volta settata la categoria, rimuovo la variabile di sessione
                    <?php unset($_SESSION['show_add_user']); ?>

                <?php elseif (isset($_SESSION['show_remove_user']) && $_SESSION['show_remove_user']): ?>
                    if (menuItems[4]) menuItems[4].click();
                    //Una volta settata la categoria, rimuovo la variabile di sessione
                    <?php unset($_SESSION['show_remove_user']); ?>
                <?php else: ?>
                    if (menuItems[0]) menuItems[0].click();
                <?php endif; ?>
            });
        </script>

        <!-- Qui gestisco i messaggi di errore o successo in base a quale tipo di sessione viene restituito da "change_password.php", "adding_user.php" o "removing_user.php"-->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                //Mostro il messaggio di conferma in base a quale file mi manda la variabile di sessione:
                //change_password.php
                <?php if (isset($_SESSION['password_changed'])): ?>
                    const successBox = document.getElementById('successMessage-security');
                    if (successBox) {
                        successBox.style.display = 'flex';
                    }
                    <?php unset($_SESSION['password_changed']); ?>
                //--------------------------------------------------
                //adding_user.php
                <?php elseif (isset($_SESSION['added_user'])): ?>
                    const succesBox2 = document.getElementById('successMessage-addUser');
                    if (succesBox2) {
                        succesBox2.style.display = 'flex';
                    }
                    <?php unset($_SESSION['added_user']); ?>
                //--------------------------------------------------
                //removing_user.php
                <?php elseif (isset($_SESSION['removed_user'])): ?>
                    const succesBox3 = document.getElementById('successMessage-removeUser');
                    if (succesBox3) {
                        succesBox3.style.display = 'flex';
                    }
                    <?php unset($_SESSION['removed_user']); ?>

                <?php endif; ?>

                //Mostro il messaggio errore in base a quale file mi manda la variabile di sessione:
                //change_password.php
                <?php if (isset($_SESSION['input_not_filled_security'])): ?>
                    const errorBox0 = document.getElementById('errorMessage-security');
                    if (errorBox0) {
                        errorBox0.style.display = 'flex';
                    }
                    <?php unset($_SESSION['input_not_filled_security']); ?>

                <?php elseif (isset($_SESSION['currentPassword_noMatch_security'])): ?>
                    const errorBox1 = document.getElementById('errorMessage-security');
                    if (errorBox1) {
                        errorBox1.style.display = 'flex';
                    }
                    <?php unset($_SESSION['currentPassword_noMatch_security']); ?>

                <?php elseif (isset($_SESSION['equal_password_security'])): ?>
                    const errorBox2 = document.getElementById('errorMessage-security');
                    if (errorBox2) {
                        errorBox2.style.display = 'flex';
                    }
                    <?php unset($_SESSION['equal_password_security']); ?>

                <?php elseif (isset($_SESSION['confirm_security'])): ?>
                    const errorBox3 = document.getElementById('errorMessage-security');
                    if (errorBox3) {
                        errorBox3.style.display = 'flex';
                    }
                    <?php unset($_SESSION['confirm_security']); ?>

                <?php elseif (isset($_SESSION['requiremets_security'])): ?>
                    const errorBox4 = document.getElementById('errorMessage-security');
                    if (errorBox4) {
                        errorBox4.style.display = 'flex';
                    }
                    <?php unset($_SESSION['requiremets_security']); ?>
                //--------------------------------------------------
                //adding_user.php
                <?php elseif (isset($_SESSION['input_not_filled_add_user'])): ?>
                    const errorBox5 = document.getElementById('errorMessage-addUser');
                    if (errorBox5) {
                        errorBox5.style.display = 'flex';
                    }
                    <?php unset($_SESSION['input_not_filled_add_user']); ?>

                <?php elseif (isset($_SESSION['confirm_add_user'])): ?>
                    const errorBox6 = document.getElementById('errorMessage-addUser');
                    if (errorBox6) {
                        errorBox6.style.display = 'flex';
                    }
                    <?php unset($_SESSION['confirm_add_user']); ?>

                <?php elseif (isset($_SESSION['requiremets_add_user'])): ?>
                    const errorBox7 = document.getElementById('errorMessage-addUser');
                    if (errorBox7) {
                        errorBox7.style.display = 'flex';
                    }
                    <?php unset($_SESSION['requiremets_add_user']); ?>
                //--------------------------------------------------
                //removing_user.php
                <?php elseif (isset($_SESSION['input_not_filled_remove_user'])): ?>
                    const errorBox8 = document.getElementById('errorMessage-removeUser');
                    if (errorBox8) {
                        errorBox8.style.display = 'flex';
                    }
                    <?php unset($_SESSION['input_not_filled_remove_user']); ?>

                <?php elseif (isset($_SESSION['removed_user_error'])): ?>
                    const errorBox9 = document.getElementById('errorMessage-removeUser');
                    if (errorBox9) {
                        errorBox9.style.display = 'flex';
                    }
                    <?php unset($_SESSION['removed_user_error']); ?>

                <?php endif; ?>
            });
        </script>
    </body>
</html>