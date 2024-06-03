<?php

    function render_view($template, $messages=[]){
        $content = file_get_contents(VIEW_FOLDER.$template);
        if (count($messages) > 0){
            foreach ($messages as $message) {
                if($message['error'] === true){
                    $content = replace_error_message($content, $message);
                } else {
                    $content = replace_success_message($content, $message);
                }
            }
        }

        $content = clear_error_message($content);
        $content = clear_success_message($content);
        echo $content;
    }

    function replace_error_message($content, $error){
        $old_msg = '<span id="'.$error["type"].'-message" class="mensagem-erro">Mensagem de Erro</span>';
        $new_msg = '<span id="'.$error["type"].'-message" class="mensagem-erro">'.$error['message'].'</span>'; 
        return str_replace(
            $old_msg,
            $new_msg,
            $content
        );
    }

    function replace_success_message($content, $info){
        $old_msg = '<p id="success-message">Mensagem de Sucesso</p>';
        $new_msg = '<p id="success-message">'.$info['message'].'</p>'; 
        return str_replace(
            $old_msg,
            $new_msg,
            $content
        );
    }

    function clear_error_message($content){
        return str_replace(
            'class="mensagem-erro">Mensagem de Erro</span>',
            'class="mensagem-erro"></span>',
            $content
        );
    }

    function clear_success_message($content){
        return str_replace(
            '<p id="success-message">Mensagem de Sucesso</p>',
            '<p></p>',
            $content
        );
    }
    
    function reload_form_data($content,$form){
        return str_replace(
            '<p id="success-message">Mensagem de Sucesso</p>',
            '<p></p>',
            $content
        );
    }
?>