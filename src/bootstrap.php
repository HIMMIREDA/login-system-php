<?php
    session_start();
    date_default_timezone_set('Africa/Casablanca');
    
    require __DIR__."/../config/app.php";
    require __DIR__."/libs/connection.php";
    require __DIR__."/auth.php";
    require __DIR__."/libs/flash.php";
    require __DIR__."/libs/helpers.php";
    require __DIR__."/libs/sanitize.php";
    require __DIR__."/libs/validate.php";
    require __DIR__."/libs/filter.php";


?>