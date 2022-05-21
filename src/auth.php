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
?>