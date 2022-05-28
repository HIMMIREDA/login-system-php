<?php
use PHPMailer\PHPMailer\PHPMailer;
require __DIR__."/libs/vendor/autoload.php";
    
function register_user(string $email,string $username,string $password,string $activation_code,int $expiry=60*60*24*1,bool $is_admin=false):bool{
    $pdo=db();
    $sql="INSERT INTO users(username,email,password,is_admin,activation_code,activation_expiry) VALUES(?,?,?,?,?,?)";
    $prpd_stmt=$pdo->prepare($sql);

    $prpd_stmt->bindValue(1,$username,PDO::PARAM_STR);
    $prpd_stmt->bindValue(2,$email,PDO::PARAM_STR);
    $prpd_stmt->bindValue(3,password_hash($password,PASSWORD_BCRYPT),PDO::PARAM_STR);
    $prpd_stmt->bindValue(4,(int)$is_admin,PDO::PARAM_BOOL);
    $prpd_stmt->bindValue(5,password_hash($activation_code,PASSWORD_DEFAULT),PDO::PARAM_STR);
    $prpd_stmt->bindValue(6,date("Y-m-d H:i:s",time()+$expiry),PDO::PARAM_STR);

    return $prpd_stmt->execute();

}


function find_user_by_username(string $username){
    $pdo=db();
    $sql="SELECT id,username,password,active,email FROM users WHERE username=:user";
    $prpd_stmt=$pdo->prepare($sql);
    $prpd_stmt->bindValue(":user",$username,PDO::PARAM_STR);
    $prpd_stmt->execute();
    
    return $prpd_stmt->fetch(PDO::FETCH_ASSOC);
}
function login(string $username,string $password,bool $remember=false):bool{
    $user=find_user_by_username($username);
    
    if($user && is_user_active($user) && password_verify($password,$user["password"])){
        
        session_regenerate_id(true);//prevent session fixation attack

        $_SESSION["username"]=$user["username"];
        $_SESSION["id"]=$user["id"];
        if($remember){
            remember_me($user["id"]);
        }
        return true;

    }
    
    return false;
    }

function is_user_logged_in():bool{
    
    if(isset($_SESSION["username"],$_SESSION["id"])){
        return true;
    }
    $token=filter_input(INPUT_COOKIE,"remember_me",FILTER_SANITIZE_STRING);
    
    if($token && token_is_valid($token)){
        
        $user=find_user_by_token($token);
        if($user){
            echo "ok ok";
            $_SESSION["username"]=$user["username"];
            $_SESSION["id"]=$user["id"];
            return true;
        }
    }
    return false;
}

function require_login():void{
    if(!is_user_logged_in()){
        redirect_to("login.php");
    }
}


function logout():void{
    if(is_user_logged_in()){
        
        delete_user_token($_SESSION["id"]);
        
        unset($_SESSION["username"]);
        unset($_SESSION["id"]);

        if(isset($_COOKIE["remember_me"])){
            unset($_COOKIE["remember_me"]);
            setcookie("remember_me",null,-1);
        }


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



function is_user_active(array $user):bool{
    
    return (int)$user["active"] === 1;
}

function generate_activation_code():string{
    return bin2hex(random_bytes(16));
}


function send_activation_email(string $email,string $activation_code):void{
    $activation_link=APP_URL."/activate.php?email=$email&activation_code=$activation_code";
    $mail=new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host=SMTPHOST;
    $mail->SMTPAuth = true;
    $mail->Username= SMTPUSER;
    $mail->Password=SMTPPASS;
    $mail->Port=465;
    $mail->SMTPSecure="ssl";
    $mail->setFrom(SENDER_EMAIL_ADDRESS,"support team");
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject="account activation";
    $mail->Body="<h2>Hi, Please click following link to activate your account : </h2> <br>
    $activation_link
    ";
    $mail->send();

}


function delete_user_by_id(int $id,int $active=0):bool{
    //delete inactive user by default using his id
    $sql="DELETE FROM users WHERE id=:id AND active=:active";
    $stmt=db()->prepare($sql);
    $stmt->bindValue(":id",$id,PDO::PARAM_INT);
    $stmt->bindValue(":active",$active,PDO::PARAM_INT);
    return $stmt->execute();
}

function find_unverified_user(string $email,string $activation_code){
    //find inactive user by email and activation code
    $sql="SELECT id,activation_code,activation_expiry < NOW() AS expired FROM users WHERE email=:email  AND active = 0";

    $stmt=db()->prepare($sql);
    $stmt->bindValue(":email",$email,PDO::PARAM_STR);
    $stmt->execute();
    $user=$stmt->fetch(PDO::FETCH_ASSOC);
    
    if($user){
        
        
        if((int)($user["expired"]) === 1 ){
        delete_user_by_id((int)($user["id"]));
        return null;
        }

        if(password_verify($activation_code,$user["activation_code"])){
            return $user;
        }

    }
    return null;
}



function activate_user(int $id):bool{
    $sql="UPDATE users SET active = 1,activated_at=CURRENT_TIMESTAMP WHERE id=:id";
    $stmt=db()->prepare($sql);
    $stmt->bindValue(":id",$id);

    return $stmt->execute();


}

function remember_me(int $user_id,int $day=30){
    [$selector,$validator,$token]=generate_tokens();
    $expiry=time()+60*60*24*$day; //30 days expiry time by default
    delete_user_token($user_id);//delete old tokens for current user

    if(insert_user_token($user_id,$selector,$validator,date("Y-m-d H:i:s",$expiry))){
        setcookie("remember_me",$token,$expiry);
    }
    
}

?>