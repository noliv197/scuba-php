<?php
    function authentication($email, $password){
        $user = check_email($email, true);
        
        if($user){
            if(!$user->mail_validation) return [
                "message" => "Please confirm your email before login.",
                "error" => true
            ];
            else if(!password_verify($password, $user->password)) return [
                "message" => "Email or password wrong",
                "error" => true
            ];
            
            unset($user->password);
            $_SESSION['auth_user'] = json_encode($user);
            return 200;
        } 

        return [
            "message" => "Email or password wrong",
            "error" => true
        ];
    }

    function auth_user(){
        return (key_exists('auth_user', $_SESSION) ? json_decode($_SESSION['auth_user']) : false);
    }
?>