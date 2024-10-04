<?php
    function ssl_crypt($data){
        define("KEY", generate_token(32));
        
        $key = base64_decode(KEY);
        
        $cipher_algorithm = "aes-256-cbc";
        $vector_len = openssl_cipher_iv_length($cipher_algorithm);
        $vector = openssl_random_pseudo_bytes($vector_len);

        $enc = openssl_encrypt($data,$cipher_algorithm,$key,OPENSSL_RAW_DATA,$vector);

        // print_r([
        //     'key' => base64_encode($key),
        //     'vector' => base64_encode($vector),
        //     'enc' => base64_encode($enc)
        // ]);
        // echo '<br>';
        return base64_encode($key.$vector.$enc);
    }

    function ssl_decrypt($cipher_data){
        $cipher_algorithm = "aes-256-cbc";
        $vector_len = openssl_cipher_iv_length($cipher_algorithm);
        
        $mix = base64_decode($cipher_data);
        $key = substr($mix,0,32);
        $vector = substr($mix,32,$vector_len);
        $enc = substr($mix,32+$vector_len);

        return openssl_decrypt($enc,$cipher_algorithm,$key,OPENSSL_RAW_DATA,$vector);
    }

    function generate_token($size){
        return  base64_encode(openssl_random_pseudo_bytes($size));
    }
?>


<!-- 
[key] => qkxZWqwlcg2wdYLS63I+YsxpWoLtwwlN1vln5PjC01A= 
[vector] => mhhVTOkeJCThCL1OT4VEDg== 
[enc] => i6WZkvW9V2n9/sPYRv2s5ndrPy3amaDpPLIf6BRTbCG+V2pF6Xh0BYEsWnGPqdPo7dOjjJgzqJwc/GrsF93X3w== -->
