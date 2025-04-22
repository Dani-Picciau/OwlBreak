<?php
    // Funzione per decidere dove mandare l'utente in base al tipo
    function redirect_users($user_type) {
        switch ($user_type) {
            case 'Studente':{
                header("location: ../loggedin_user/student_user.php");
                break;
            }
            case 'Personale-Docente':
            case 'Personale-Ata': {
                header("location: ../loggedin_user/professor_ata_user.php");
                break;
            }
            case 'Personale-Segreteria': {
                header("location: ../loggedin_user/school_secretery_user.php"); 
                break;
            }
            case 'Titolare': {
                header("location: ../loggedin_user/holder_operator_user.php"); 
                break;
            }
            case 'Addetto-Consegne': {
                header("location: ../loggedin_user/delivery_operator_user.php");
                break;
            }
            case 'Addetto-Vendite': {
                header("location: ../loggedin_user/sales_operator_user.php"); 
                break;
            }
            case 'Fornitore': {
                header("location: ../loggedin_user/supplier_user.php"); 
                break;
            }
            default: {
                header("location: ../login_files/index.php"); // fallback alla login
                break;
            }
        }
    }
?>

