<?php

    function guest_routes(){
        if (key_exists('page',$_GET)){
            switch($_GET['page']){
                case 'root':
                    do_login($_GET);
                    break;
                case 'login':
                    if($_SERVER["REQUEST_METHOD"] === 'POST'){
                        if (key_exists('person',$_POST)){
                            login($_POST['person']);
                        }
                    } else {
                        do_login($_GET);
                    }
                    break;
                case 'register':
                    if($_SERVER["REQUEST_METHOD"] === 'POST'){
                        if (key_exists('person',$_POST)){
                            register($_POST['person']);
                        } else {
                            http_response_code(400);
                        }
                    } else {
                        do_register();
                    }
                    break;
                case 'mail-validation':
                    if (key_exists('token',$_GET)){
                        do_validation($_GET['token']);
                    }
                    break;
                case 'forget-password':
                    if($_SERVER["REQUEST_METHOD"] === 'POST'){
                        if (key_exists('person',$_POST)){
                            forget_password($_POST['person']['email']);
                        } else {
                            http_response_code(400);
                        }
                    } else {
                        do_forget_password();
                    }
                    break;
                case 'change-password':
                    // if (key_exists('token',$_GET)){
                    //     change_password($_GET['token']);
                    // } else {
                    //     http_response_code(400);
                    // }

                    if($_SERVER["REQUEST_METHOD"] === 'POST'){
                        change_password($_POST['person']);
                    } else {
                        do_change_password($_GET['token']);
                    }
                    break;
                default:
                    http_response_code(404);
                    do_not_found();
                    break;
            }
        } else {
            http_response_code(404);
            do_not_found();
        }
    }

    function auth_routes(){
        if (key_exists('page',$_GET)){
            switch($_GET['page']){
                case 'root':
                    do_home();
                    break;
                case 'home':
                    do_home();
                    break;
                case 'logout':
                    do_logout();
                    break;
                case 'delete-account':
                    do_delete_account();
                    break;
                default:
                    do_not_found();
                    break;
            }
        } else {
            do_home();
        }
    }

?>