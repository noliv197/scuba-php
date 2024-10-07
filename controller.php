<?php

    //GET functions 
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

    function do_register($messages=[], $values=[]){
        render_view('register.view', $messages, $values);
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
        } else if(isset($request['from']) &&  isset($request['status'])){
            if($request['from'] === 'change-password' && $request['status'] === 'confirmed'){
                array_push($messages,[
                    "message" => "Your password was change successfully",
                    "error" => false
                ]);
            }
        }
        render_view('login.view', $messages);
        http_response_code(200);
    }

    function do_validation($token){
        $email = substr(ssl_decrypt($token),0, -36);
        if(check_email($email)){
            user_edit($email, 'mail_validation', true);
            http_response_code(201);
            header("Location: ".DOMAIN."?page=login&from=validation&register=confirmed",true,301);
        } else {
            http_response_code(403);
            header("Location: ".DOMAIN."?page=login&from=validation&register=not-confirmed",true,301);
        }
    }

    function do_forget_password($message=[]){
        render_view('forget_password.view',$message);
        http_response_code(200);
    }

    function do_change_password($token, $errors=[]){
        render_view('change_password.view',$errors,[
            ["type" => "hidden","name" => 'token',"value" => $token],
        ]);
        http_response_code(200);
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

    // POST functions
    function register($user){
        $errors = [];
        $pass_error = verify_pass($user['password']);
        $confirm_error = confirm_pass($user['password'], $user['password-confirm']);
        $email_error = verify_email($user['email']);

        $pass_error !== false ? array_push($errors, $pass_error) : null;
        $confirm_error !== false ? array_push($errors, $confirm_error) : null;
        $email_error !== false ? array_push($errors, $email_error) : null;


        if (count($errors) > 0){
            do_register($errors,[
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

    function forget_password($email){
        if (check_email($email)){
            send_forget_password($email);
            do_forget_password([
                [
                    "message" => "Please check your inbox to proceed.",
                    "error" => false
                ]
            ]);
        } else {
            do_forget_password([
                [
                    "message" => "It was not possible to confirm your email.",
                    "error" => true
                ]
            ]);
        }
    }

    function change_password($form){
        $decrypt_token = ssl_decrypt($form['token']);
        $email = preg_split('/time=/', $decrypt_token)[0];
        $time = preg_split('/time=/', $decrypt_token)[1];

        $errors = [];
        $pass_error = verify_pass($form['password']);
        $confirm_error = confirm_pass($form['password'], $form['password-confirm']);
        $time_error = verify_time($time);

        $pass_error !== false ? array_push($errors, $pass_error) : null;
        $confirm_error !== false ? array_push($errors, $confirm_error) : null;
        $time_error !== false ? array_push($errors, $time_error) : null;
        
        if (count($errors) > 0){
            do_change_password($form['token'], $errors);
       } else {
           $new_password = password_hash($form["password"],PASSWORD_BCRYPT,["cost"=>10]);
           
           user_edit($email,'password', $new_password);
           http_response_code(201);
           header("Location: ".DOMAIN."?page=login&from=change-password&status=confirmed",true,301);
       }
    }
?>