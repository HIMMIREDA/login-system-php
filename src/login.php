<?php
    if(is_user_logged_in()){
        redirect_to("index.php");
    }
    $errors=[];
    $inputs=[];

    $fields=["password"=>"string | required",
        "username" =>"string | required"];
    
    

    if(is_post_request()){
        [$inputs,$errors]=filter($_POST,$fields);

        if($errors){
            redirect_with("login.php",["inputs"=>$inputs,"errors"=>$errors]);
        }
        if(!login($inputs["username"],$inputs["password"])){

            $errors["login"]="Invalid username or password";
            redirect_with("login.php",["inputs"=>$inputs,"errors"=>$errors]);
        }

        redirect_to("index.php");
    }elseif(is_get_request()){
        [$inputs,$errors]=session_flash("inputs","errors");
    }

    
    

?>