<?php
    function confirm_pass($pass, $confirm){
        if ($pass !== $confirm){
            return [
                "type" => "confirm",
                "message" => "The password should match",
                "error" => true
            ];
        }

        return false;
    }

    function verify_pass($pass){
        if (strlen($pass) < 10){
            return [
                "type" => "pass",
                "message" => "The password should have at least 10 characters",
                "error" => true
            ];
        }

        return false;
    }

    function verify_email($email){
        $email_exists = check_email($email, false);
        if ($email_exists){
            return [
                "type" => "email",
                "message" => "This email is already registered",
                "error" => true
            ];
        }

        return false;
    }
?>