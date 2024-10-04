<?php

    function do_not_found(){
        render_view('not_found.view');
        http_response_code(404);
    }

    function do_home(){
        render_view('home.view');
        http_response_code(200);
    }

    function do_register(){
        render_view('register.view');
        http_response_code(200);
    }
    
    function do_login($request){
        $messages = [];
        if(
            $request['from'] && $request['from'] === 'register' && 
            $request['register'] && $request['register'] === 'confirm'
        ){
            array_push($messages,[
                "message" => "Register successfull. Please confirm your email",
                "error" => false
            ]);
        }
        render_view('login.view', $messages);
        http_response_code(200);
    }

    function do_validation($token){
        // send_email_validation('test@gmail.com', 'test');
        $email = substr(ssl_decrypt($token),0,15);
        user_edit($email, 'mail_validation', true);
    }

    function register($user){
        $errors = [];
        $pass_error = verify_pass($user['password']);
        $confirm_error = confirm_pass($user['password'], $user['confirm']);
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
            unset($user['confirm']);
            
            user_create($user);
            send_email_validation($user['email'], $user['name']);
            http_response_code(201);
            header("Location: ".DOMAIN."?page=login&from=register&register=confirm",true,301);
        }
    }

    function login($user){
        // check if email exists
        // check if mail_validation = true
        // password_verify($pass,$array['pass'])
    }
?>