<?php
    function get_users(){
        return json_decode(
            file_get_contents(DATA_LOCATION)
        );
    }

    function update_users($data){
        file_put_contents(
            DATA_LOCATION, 
            json_encode($data)
        );
    }

    function user_create($user){
        $data = get_users();
        array_push($data, $user);
        update_users($data);
    }

    function user_edit($email, $key, $new_val){
        $data = get_users();

        foreach($data as $dt){
            if($email == $dt->email) {
                $dt->$key = $new_val;
                break;
            };
        }

        update_users($data);
    }

    function check_email($email, $returnObj=false){
        $data = get_users();

        foreach(array_column($data, 'email') as $idx => $data_email){
            if($email == $data_email) {
                return ($returnObj ? $data[$idx] : true);
                break;
            };
        }

        return false;
    }
?>