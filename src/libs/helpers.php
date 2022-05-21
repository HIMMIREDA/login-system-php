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

function redirect_with(string $url,array $items):void{
    foreach($items as $key=>$item){
        $_SESSION[$key]=$item;
    }
    redirect_to($url);
}


function redirect_with_message(string $url,string $message,string $type=FLASH_SUCCESS){
    flash("flash_".uniqid(),$message,$type);

    redirect_to($url);
}

//function to get variables from session and delete them
function session_flash(...$keys){
    $data=[];
    foreach($keys as $key){
        if(isset($_SESSION[$key])){
            $data[]=$_SESSION[$key];
            unset($_SESSION[$key]);
        }else{
            $data[]=[];
        }
    }
    return $data; 

}


?>