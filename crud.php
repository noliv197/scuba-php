<?php
    function user_create($user){
        $fileAddress = "./data/users.json";
        $data = json_decode(
            file_get_contents($fileAddress)
        );

        array_push($data, $user);
        file_put_contents($fileAddress, json_encode($data));
    }

    function user_edit($email, $key, $new_val){
        $fileAddress = "./data/users.json";
        $data = json_decode(
            file_get_contents($fileAddress)
        );

        file_put_contents($fileAddress, json_encode($data));
    }

    function get_by_email($email){
        $fileAddress = "./data/users.json";
        $data = json_decode(
            file_get_contents($fileAddress)
        );

        foreach(array_column($data, 'email') as $data_email){
            if($email == $data_email) {
                return true;
                break;
            };
        }

        return false;
    }
?>