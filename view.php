<?php

    function render_view($template, $messages=[], $values=[]){
        $content = file_get_contents(VIEW_FOLDER.$template);
        if (count($messages) > 0){
            foreach ($messages as $message) {

                if($message['error'] === true && key_exists('type',$message)){
                    $content = replace_form_message($content, $message);
                } else {
                    $content = replace_main_message($content, $message);
                }
            }
        }

        if(count($values) > 0){
            foreach ($values as $value) {
                if(key_exists('str', $value)){
                    $content = replace_str($content, $value);
                } else {
                    $content = replace_value($content, $value);
                }
            }
        }
        
        $content = clear_form_message($content);
        $content = clear_main_message($content);
        echo $content;
    }

    function replace_str($content, $str){
        return str_replace(
            $str['old_value'],
            $str['new_value'],
            $content
        );
    }

    function replace_form_message($content, $error){
        $old_msg = '<span id="'.$error["type"].'-message" class="mensagem-erro">Mensagem de Erro</span>';
        $new_msg = '<span id="'.$error["type"].'-message" class="mensagem-erro">'.$error['message'].'</span>'; 
        return str_replace(
            $old_msg,
            $new_msg,
            $content
        );
    }

    function replace_main_message($content, $info){
        if($info['error']){
            $content = str_replace(
                '<div class="mensagem-sucesso">',
                '<div class="mensagem-erro">',
                $content
            );
        }
        $old_msg = '<p id="success-message">Mensagem de Sucesso</p>';
        $new_msg = '<p id="'.($info['error'] ? 'error' : 'success').'-message">'.$info['message'].'</p>'; 
        return str_replace(
            $old_msg,
            $new_msg,
            $content
        );
    }

    function replace_value($content, $value){
        $old_input = '<input type="'.$value["type"].'" name="person['.$value["name"].']" value="" required>';
        $new_input = '<input type="'.$value["type"].'" name="person['.$value["name"].']" value="'.$value["value"].'" required>'; 
        return str_replace(
            $old_input,
            $new_input,
            $content
        );
    }

    function clear_form_message($content){
        return str_replace(
            'class="mensagem-erro">Mensagem de Erro</span>',
            'class="mensagem-erro"></span>',
            $content
        );
    }

    function clear_main_message($content){
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