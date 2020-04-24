<?php

function debug($arr){
    echo '<pre>' . print_r($arr, true) . '</pre>';
}


function redirect($http = false){

    if($http){
        $redirect = $http;
    }
    else{
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
    }

    header("Location: $redirect");
    exit;
}


function object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}



function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
}