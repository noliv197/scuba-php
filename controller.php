<?php

    function do_not_found(){
        render_view('not_found.view');
        http_response_code(404);
    }

    function do_home(){
        $user = json_decode($_SESSION['auth_user']);
        render_view('home.view', [], [
            [
                'new_value' => $user ->name, 
                'old_value'=>'{{field_name}}',
                'str'=> true
            ],
            [
                'new_value' => $user ->email, 
                'old_value'=>'{{field_email}}',
                'str'=> true
            ]
        ]);
        http_response_code(200);
    }

    function do_register(){
        render_view('register.view');
        http_response_code(200);
    }
    
    function do_login($request){
        $messages = [];
        if(isset($request['from']) &&  isset($request['register'])){
            if($request['from'] === 'register' && $request['register'] === 'sent'){
                array_push($messages,[
                    "message" => "Register successfull. Please confirm your email",
                    "error" => false
                ]);
            } else if($request['from'] === 'validation' && $request['register'] === 'confirmed'){
                array_push($messages,[
                    "message" => "Email confirmed successfully",
                    "error" => false
                ]);
            } else if($request['from'] === 'validation' && $request['register'] === 'not-confirmed'){
                array_push($messages,[
                    "message" => "It was not possible to confirm your email",
                    "error" => true
                ]);
            }
        }
        render_view('login.view', $messages);
        http_response_code(200);
    }

    function do_validation($token){
        $email = substr(ssl_decrypt($token),0,15);
        if(check_email($email)){
            user_edit($email, 'mail_validation', true);
            http_response_code(201);
            header("Location: ".DOMAIN."?page=login&from=validation&register=confirmed",true,301);
        } else {
            http_response_code(403);
            header("Location: ".DOMAIN."?page=login&from=validation&register=not-confirmed",true,301);
        }
    }

    function do_logout(){
        auth_logout();
        http_response_code(201);
        header("Location: ".DOMAIN."?page=login&from=logout",true,301);
    }

    function do_delete_account(){
        $user = json_decode($_SESSION['auth_user']);
        user_delete($user);
        do_logout();
    }

    function register($user){
        $errors = [];
        $pass_error = verify_pass($user['password']);
        $confirm_error = confirm_pass($user['password'], $user['password-confirm']);
        $email_error = verify_email($user['email']);

        $pass_error !== false ? array_push($errors, $pass_error) : null;
        $confirm_error !== false ? array_push($errors, $confirm_error) : null;
        $email_error !== false ? array_push($errors, $email_error) : null;


        if (count($errors) > 0){
             render_view('register.view', $errors, [
                ["type" => "text","name" => 'name',"value" => $user['name'],],
                ["type" => "email","name" => 'email',"value" => $user['email'],]
            ]);
        } else {
            $user["mail_validation"] = false;
            $user["password"] = password_hash($user["password"],PASSWORD_BCRYPT,["cost"=>10]);
            unset($user['password-confirm']);
            
            user_create($user);
            send_email_validation($user['email'], $user['name']);
            http_response_code(201);
            header("Location: ".DOMAIN."?page=login&from=register&register=sent",true,301);
        }
    }

    function login($user){
        $auth = authentication($user['email'], $user['password']);

        if($auth === 200){
            http_response_code(200);
            header("Location: ".DOMAIN."?page=home&from=login",true,301);
        } else {
            render_view('login.view', [$auth]);
        }
    }
?>