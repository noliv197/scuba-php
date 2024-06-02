<?php
    function create($user){
        $fileAddress = "./data/users.json";
        $data = json_decode(
            file_get_contents($fileAddress)
        );

        array_push($data, $user);
        file_put_contents($fileAddress, json_encode($data));
    }
?>