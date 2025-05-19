<?php
    // Funzione per decidere dove mandare l'utente in base al tipo
    function redirect_users($user_type) {
        switch ($user_type) {
            case 'Studente':
            case 'Personale-Docente':
            case 'Personale-Ata': 
            case 'Personale-Segreteria': {
                header("location: /owlbreak/website/loggedin_users/customers/customer_user.php"); 
                break;
            }
            case 'Titolare': {
                header("location: /owlbreak/website/loggedin_users/operators/holder_operator_user.php"); 
                break;
            }
            case 'Addetto-Consegne': {
                header("location: /owlbreak/website/loggedin_users/operators/delivery_operator_user.php");
                break;
            }
            case 'Addetto-Vendite': {
                header("location: /owlbreak/website/loggedin_users/operators/sales_operator_user.php"); 
                break;
            }
            case 'Fornitore': {
                header("location: /owlbreak/website/loggedin_users/operators/supplier_user.php"); 
                break;
            }
            default: {
                header("location: /owlbreak/website/login_files/index.php"); // fallback alla login
                break;
            }
        }
    }
?>

