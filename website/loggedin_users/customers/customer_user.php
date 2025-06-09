<?php
    require_once(__DIR__ . '/../../includes/loggedin.php');
    // Tutti i tipi di utenti cliente che possono accedere a questa pagina
    check_user_type('Studente', 'Personale-Docente', 'Personale-Ata', 'Personale-Segreteria');
    
    require_once('../../includes/mysqli_connect_user.php');
    $mapped_products = require('customer_user_includes/products_mapping.php');
    //Gestione parte ajax lato php
    require_once('customer_user_includes/customer_ajax.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/owlbreak/website/images/logo.svg" type="svg">
        <title>User account</title>
        <!-- Normalize CSS -->
        <link rel="stylesheet" href="customer_user.css?v=<?=time()?>" />

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
                <?php
                    echo "<p>Buongiorno ".$_SESSION['nome']. ", hai fame?</p>";
                ?>
            </div>
            <div class="order-box">
                <div class="menu-box">
                    <div class="menu-category" data-category="Panini e salse">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M160-120q-33 0-56.5-23.5T80-200v-120h800v120q0 33-23.5 56.5T800-120H160Zm0-120v40h640v-40H160Zm320-180q-36 0-57 20t-77 20q-56 0-76-20t-56-20q-36 0-57 20t-77 20v-80q36 0 57-20t77-20q56 0 76 20t56 20q36 0 57-20t77-20q56 0 77 20t57 20q36 0 56-20t76-20q56 0 79 20t55 20v80q-56 0-75-20t-55-20q-36 0-58 20t-78 20q-56 0-77-20t-57-20ZM80-560v-40q0-115 108.5-177.5T480-840q183 0 291.5 62.5T880-600v40H80Zm400-200q-124 0-207.5 31T166-640h628q-23-58-106.5-89T480-760Zm0 520Zm0-400Z"/></svg>
                        Panini e salse
                    </div>
                    <div class="menu-category" data-category="Dolci">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M804-282q17 9 30-4t4-30l-58-108-42 108 66 34Zm-200-38h48l96-238q3-8-1.5-13.5T736-580l-80-32q-9-3-17.5 2T628-596l-24 276Zm-296 0h48l-24-276q-2-11-10.5-15t-17.5-1l-80 32q-8 3-11.5 8.5T212-558l96 238Zm-152 38 66-34-42-108-58 108q-9 17 4 30t30 4Zm280-38h88l30-338q2-9-4.5-15.5T534-680H426q-8 0-14.5 6.5T406-658l30 338ZM138-200q-42 0-70-31.5T40-306q0-12 3.5-23.5T52-352l88-168q-14-40 1-79t53-55l80-32q14-5 28-7t28 1q14-29 39-48.5t57-19.5h108q32 0 57 19.5t39 48.5q14-2 28-.5t28 6.5l80 32q40 16 56 55t-2 77l88 168q6 11 9 23t3 25q0 45-30.5 75.5T814-200q-11 0-22-2.5t-22-7.5l-62-30H250l-56 30q-13 7-27.5 8.5T138-200Zm342-280Z"/></svg>
                        Dolci
                    </div>
                    <div class="menu-category" data-category="Snack salati">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-80 80-680q85-72 186.5-116T480-840q112 0 213.5 43.5T880-680L480-80Zm0-144 292-438q-65-45-139-71.5T480-760q-79 0-152.5 26.5T188-662l292 438ZM380-560q25 0 42.5-17.5T440-620q0-25-17.5-42.5T380-680q-25 0-42.5 17.5T320-620q0 25 17.5 42.5T380-560Zm100 200q25 0 42.5-17.5T540-420q0-25-17.5-42.5T480-480q-25 0-42.5 17.5T420-420q0 25 17.5 42.5T480-360Zm0 136Z"/></svg>
                        Snack salati
                    </div>
                    <div class="menu-category" data-category="Bevande">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M160-120v-80h640v80H160Zm160-160q-66 0-113-47t-47-113v-400h640q33 0 56.5 23.5T880-760v120q0 33-23.5 56.5T800-560h-80v120q0 66-47 113t-113 47H320Zm0-480h320-400 80Zm400 120h80v-120h-80v120ZM560-360q33 0 56.5-23.5T640-440v-320H400v16l72 58q2 2 8 16v170q0 8-6 14t-14 6H300q-8 0-14-6t-6-14v-170q0-2 8-16l72-58v-16H240v320q0 33 23.5 56.5T320-360h240ZM360-760h40-40Z"/></svg>
                        Bevande
                    </div>
                    <div class="menu-category" data-category="Caffetteria">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M440-240q-117 0-198.5-81.5T160-520v-240q0-33 23.5-56.5T240-840h500q58 0 99 41t41 99q0 58-41 99t-99 41h-20v40q0 117-81.5 198.5T440-240ZM240-640h400v-120H240v120Zm200 320q83 0 141.5-58.5T640-520v-40H240v40q0 83 58.5 141.5T440-320Zm280-320h20q25 0 42.5-17.5T800-700q0-25-17.5-42.5T740-760h-20v120ZM160-120v-80h640v80H160Zm280-440Z"/></svg>
                        Caffetteria
                    </div>
                    <div class="menu-category" data-category="Caramelle e condimenti">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-75 29-147t81-128.5q52-56.5 125-91T475-881q21 0 43 2t45 7q-9 45 6 85t45 66.5q30 26.5 71.5 36.5t85.5-5q-26 59 7.5 113t99.5 56q1 11 1.5 20.5t.5 20.5q0 82-31.5 154.5t-85.5 127q-54 54.5-127 86T480-80Zm-60-480q25 0 42.5-17.5T480-620q0-25-17.5-42.5T420-680q-25 0-42.5 17.5T360-620q0 25 17.5 42.5T420-560Zm-80 200q25 0 42.5-17.5T400-420q0-25-17.5-42.5T340-480q-25 0-42.5 17.5T280-420q0 25 17.5 42.5T340-360Zm260 40q17 0 28.5-11.5T640-360q0-17-11.5-28.5T600-400q-17 0-28.5 11.5T560-360q0 17 11.5 28.5T600-320ZM480-160q122 0 216.5-84T800-458q-50-22-78.5-60T683-603q-77-11-132-66t-68-132q-80-2-140.5 29t-101 79.5Q201-644 180.5-587T160-480q0 133 93.5 226.5T480-160Zm0-324Z"/></svg>
                        Caramelle
                    </div>
                    <div class="separator1"></div>
                    <div class="menu-category" data-category="Carrello">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>
                            Carrello
                        </div>
                        <span>
                            <?php 
                                if (isset($_SESSION['cart'])) {
                                    echo count($_SESSION['cart']);
                                } else {
                                    echo "<p>0</p>";
                                }
                            ?>
                        </span>
                    </div>
                    <div class="menu-category" data-category="Cronologia ordini">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-120q-138 0-240.5-91.5T122-440h82q14 104 92.5 172T480-200q117 0 198.5-81.5T760-480q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h110v80H120v-240h80v94q51-64 124.5-99T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z"/></svg>
                        Cronologia ordini
                    </div>
                </div>
                <div class="separator2"></div>
                <div class="container">
                    <div class="search-bar">
                        <form onsubmit="event.preventDefault();">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#33333"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                            <input type="search" class="search-input" placeholder="search">
                        </form>
                    </div>
                    
                    <div class="product-box">
                        <?php require('customer_user_includes/product_availability.php'); ?>
                    </div>

                    <div class="orders-history">
                        <?php require('customer_user_includes/orders_history.php'); ?>
                    </div>

                    <div class="cart-item-container">
                        <section class="success-message" id="successMessage-placeDelivery">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#1f1f1f"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                            <span>Luogo di consegna aggiornato con successo!</span>
                        </section>
                        <section class="error-message" id="errorMessage-placeDelivery">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#1f1f1f"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                            <span>È necessario specificare un luogo di consegna, si prega di riprovare.</span>
                        </section>
                        <?php require('customer_user_includes/user_cart.php'); ?>
                    </div>
                </div>
            </div>
        </div>

        <script src="customer_user.js?v=1.02"></script>

        <!-- Solo quando faccio il submit del bottone in "customer_place_delivery.php" imposto anche una variabile di sessione che al refresh della pagina mi permette  di mantenere selezionata la categoria "sicurezza". Al prossimo refresh la categoria selezionata torna ad essere la prima. 
        Lo script in questo punto, invece che nel file js dedicato, è necessario per riuscire a gestire php e javascript assieme -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
            const menuItems = document.querySelectorAll('.menu-category');
            
                // Controllo la variabile di sessione per capire su quale categoria fermarmi dopo il submit del bottone
                <?php if (isset($_SESSION['show_cart']) && $_SESSION['show_cart']): ?>
                    if (menuItems[6]) menuItems[6].click();
                    //Una volta settata la categoria, rimuovo la variabile di sessione
                    <?php unset($_SESSION['show_cart']); ?>
                <?php endif; ?>
            });
        </script>

        <!-- Qui gestisco i messaggi di errore o successo in base a quale tipo di sessione viene restituito da "change_place_delivery.php"-->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                //Mostro il messaggio di conferma in base a quale file mi manda la variabile di sessione:
                <?php if (isset($_SESSION['changed_place_delivery'])): ?>
                    const successBox = document.getElementById('successMessage-placeDelivery');
                    if (successBox) {
                        successBox.style.display = 'flex';
                    }
                    <?php unset($_SESSION['changed_place_delivery']); ?>

                <?php endif; ?>

                //Mostro il messaggio errore in base a quale file mi manda la variabile di sessione:
                <?php if (isset($_SESSION['input_not_filled_place_delivery'])): ?>
                    const errorBox0 = document.getElementById('errorMessage-placeDelivery');
                    if (errorBox0) {
                        errorBox0.style.display = 'flex';
                    }
                    <?php unset($_SESSION['input_not_filled_place_delivery']); ?>

                <?php endif; ?>
            });
        </script>
    </body>
</html>
