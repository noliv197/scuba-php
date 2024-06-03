<?php

    function do_home(){
        render_view('home.view');
        http_response_code(200);
    }

    function do_register(){
        render_view('register.view');
        http_response_code(200);
    }
    
    function do_login(){
        render_view('login.view');
        http_response_code(200);
    }
    
    function do_not_found(){
        render_view('not_found.view');
        http_response_code(404);
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
            render_view('register.view', $errors);
        } else {
            create($user);
            http_response_code(201);
            header("Location: ".$_SERVER["HTTP_ORIGIN"]."?page=login",true,301);
        }
    }
?>