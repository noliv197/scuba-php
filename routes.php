<?php
    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
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
        break;
        case "POST":
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


    }
?>