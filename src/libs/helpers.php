<?php
function view(string $filename,array $data=[]):void{
    
    foreach($data as $key=>$value){

        $$key=$value; //create a variable with a $key variable value as a name
    }
    require_once __DIR__."/../inc/".$filename.".php";
}


function is_post_request():bool{
    return strtoupper($_SERVER["REQUEST_METHOD"]) === "POST";
}

function is_get_request():bool{
    return strtoupper($_SERVER["REQUEST_METHOD"]) === "GET";
}


function error_class(array $errors,string $field):string{
    return (isset($errors[$field]))?"error":"";
}

function redirect_to(string $url){

    header("Location: ".$url);
    exit;
}

?>