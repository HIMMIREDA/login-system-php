<?php

function register_user(string $email,string $username,string $password,bool $is_admin=false):bool{
    $pdo=db();
    $sql="INSERT INTO users(username,email,password,is_admin) VALUES(?,?,?,?)";
    $prpd_stmt=$pdo->prepare($sql);

    $prpd_stmt->bindValue(1,$username,PDO::PARAM_STR);
    $prpd_stmt->bindValue(2,$email,PDO::PARAM_STR);
    $prpd_stmt->bindValue(3,password_hash($password,PASSWORD_BCRYPT),PDO::PARAM_STR);
    $prpd_stmt->bindValue(4,(int)$is_admin,PDO::PARAM_BOOL);

    return $prpd_stmt->execute();

}


function find_user_by_username(string $username){
    $pdo=db();
    $sql="SELECT id,username,password FROM users WHERE username= :user";
    $prpd_stmt=$pdo->prepare($sql);
    $prpd_stmt->bindValue(":user",$username,PDO::PARAM_STR);
    $prpd_stmt->execute();
    return $prpd_stmt->fetchAll(PDO::FETCH_ASSOC);
}
function login(string $username,string $password):bool{
    $user=find_user_by_username($username);
    if($user && password_verify($password,$user["password"])){
        session_regenerate_id(true);//prevent session fixation attack
        $_SESSION["username"]=$user["username"];
        $_SESSION["id"]=$user["id"];

        return true;

    }
        
    return false;
    }

function is_user_logged_in():bool{

    return isset($_SESSION["username"],$_SESSION["id"]);
}

function require_login():void{
    if(!is_user_logged_in()){
        redirect_to("login.php");
    }
}


function logout():void{
    if(is_user_logged_in()){
        unset($_SESSION["username"]);
        unset($_SESSION["id"]);
        session_destroy();
        redirect_to("login.php");
    }
}

function current_user(){
    if(is_user_logged_in()){
        return $_SESSION["username"];
    }
    return null;
}
?>