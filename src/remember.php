<?php
//we use selector:validator hash to prevent timing attacks 
function generate_tokens():array{
    $selector=bin2hex(random_bytes(16));
    $validator=bin2hex(random_bytes(32));
    return [$selector,$validator,$selector.":".$validator];
}


function parse_token($token){
    $parts=explode(":",$token);

    if($parts && count($parts) == 2){
        return [$parts[0],$parts[1]];
    }

    return null;
}


function insert_user_token(int $user_id,string $selector,string $validator,string $expiry):bool{
    $sql="INSERT INTO user_tokens(user_id,selector,hashed_validator,expiry) VALUES(?,?,?,?)";
    $prpd_stmt=db()->prepare($sql);
    $prpd_stmt->bindValue(1,$user_id);
    $prpd_stmt->bindValue(2,$selector);
    $prpd_stmt->bindValue(3,hash("sha256",$validator));
    $prpd_stmt->bindValue(4,$expiry);

    return $prpd_stmt->execute();
}

function find_user_token_by_selector(string $selector){
    $sql="SELECT * FROM user_tokens WHERE selector= :selector";
    $prpd_stmt=db()->prepare($sql);
    $prpd_stmt->bindValue(":selector",$selector);
    $prpd_stmt->execute();
    return $prpd_stmt->fetch(PDO::FETCH_ASSOC);
}

function delete_user_token(int $user_id):bool{
    $sql="DELETE FROM user_tokens WHERE user_id=:user_id";

    $prpd_stmt=db()->prepare($sql);
    $prpd_stmt->bindValue(":user_id",$user_id);

    return $prpd_stmt->execute();

}


function find_user_by_token(string $token){
    $token=parse_token($token);

    if(!$token){
        return null;
    }

    $sql="SELECT users.id,users.username FROM users JOIN user_tokens ON users.id = user_tokens.id WHERE selector=:selector AND expiry > NOW() LIMIT 1";
    $prpd_stmt=db()->prepare($sql);
    $prpd_stmt->bindValue(":selector",$token[0]);
    $prpd_stmt->execute();

    return $prpd_stmt->fetch(PDO::FETCH_ASSOC);

}

function token_is_valid(string $token):bool{
    [$selector,$validator]=parse_token($token);
    $tokens=find_user_by_token($selector);
    if(!$tokens){
        return null;
    }
    

    return hash_equals($tokens["hashed_validator"],hash("sha256",$validator));
}
?>