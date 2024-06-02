<?php
    include 'controller.php';
    
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
                    "nome" => $person["name"],
                    "email" => $person["email"],
                    "senha" => $person["password"]
                ];
                register($user);
            }
        break;


    }
?>