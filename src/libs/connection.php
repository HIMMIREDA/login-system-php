<?php

use FTP\Connection as FTPConnection;

    require __DIR__."/../../config/database.php";

    function db($host=DB_HOST,$dbname=DB_NAME,$user=DB_USER,$pass=DB_PASS):PDO{
        static $pdo;   //we use static variable to prevent reconnecting to database each time we call the function
        if(!$pdo){
            $pdo=new PDO("mysql:host=$host;dbname=$dbname",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

        }
        return $pdo;
    }

