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
        $fileAddress = "./data/users.json";
        $data = json_decode(
            file_get_contents($fileAddress)
        );

        $email_exists = false;
        foreach(array_column($data, 'email') as $data_email){
            if($email == $data_email) {
                $email_exists = true;
                break;
            };
        }

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