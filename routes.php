<?php
    include 'controller.php';
    
    if (key_exists('page',$_GET)){
        switch($_GET['page']){
            case 'login':
                do_login();
                break;
            case 'register':
                do_register();
                break;
            default:
                do_not_found();
                break;
        }
    }
    else {
        do_home();
    }
?>