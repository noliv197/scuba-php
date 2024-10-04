<?php
    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            if (key_exists('page',$_GET)){
                switch($_GET['page']){
                    case 'login':
                        do_login($_GET);
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
        break;
        case "POST":
            if (key_exists('page',$_GET)){
                switch($_GET['page']){
                    case 'login':
                        break;
                    case 'register':
                        if (key_exists('person',$_POST)){
                            $person = $_POST['person'];
                            $user = [
                                "name" => $person["name"],
                                "email" => $person["email"],
                                "password" => $person["password"],
                                "confirm" => $person["password-confirm"],
                            ];
                            register($user);
                        } else {
                            http_response_code(400);
                        }
                        break;
                    case 'mail-validation':
                        if (key_exists('token',$_GET)){
                            do_validation($_GET['token']);
                        }
                        break;
                    default:
                        http_response_code(404);
                        break;
                }
            }
        break;


    }
?>