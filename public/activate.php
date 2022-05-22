<?php
    require __DIR__."/../src/bootstrap.php";

    $fields=["email"=>"email | required | email",
            "activation_code"=>"string | required"];

    [$inputs,$errors]=filter($_GET,$fields);

    if($errors){
        redirect_to("register.php");
    }
    $user=find_unverified_user($inputs["email"],$inputs["activation_code"]);

    if($user){
        if(activate_user((int)$user["id"])){
            redirect_with_message("login.php","Your account has been activated successfully.Please Login here.");
        }
    }
    redirect_with_message("register.php","The activation link is not valid.Please register again.",FLASH_ERROR);

?>