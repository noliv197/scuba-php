<?php
    include 'view.php';

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
?>