<?php
    require_once('loggedin.php');
    
    switch ($_SESSION['user_type']) {
        case 'Personale-Segreteria': {
            header("location:  /owlbreak/website/loggedin_users/customer_profiles/school_secretery_profile.php");
            break;
        }
        default:{
            header("location: /owlbreak/website/loggedin_users/customer_profiles/other_customers_profile.php");
            break;
        }
    }
?>