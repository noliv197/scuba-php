<?php
    function confirm_pass($pass, $confirm){
        if ($pass === $confirm){
            return false;
        } else {
            return [
                "type" => "confirm",
                "message" => "The password should match",
                "error" => true
            ];
        }
    }

    function verify_pass($pass){
        if (strlen($pass) >= 4){
            return false;
        } else{
            return [
                "type" => "pass",
                "message" => "The password should have at least 10 characters",
                "error" => true
            ];
        }
    }

    function verify_email($email){

        $email_exists = get_by_email($email);
        if (!$email_exists){
            return false;
        } else {
            return [
                "type" => "email",
                "message" => "This email is already registered",
                "error" => true
            ];
        }
    }
?>