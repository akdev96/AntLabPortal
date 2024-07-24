<?php

/* Retrieving authetication method */
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST[AuthMethod])){
    $AuthMethod = $_POST([AuthMethod]);

    switch($AuthMethod){
        case 'SMS Authentication':
            SMSAuthentication();
            break;
        case 'Member Login':
            // 
            break;
        case 'Day Visitor (4hr)':
            // 
            break;
        default:
            break;
    }
}

function SMSAuthentication(){
    header("Location: sms_auth.php");
    exit();
}

?>