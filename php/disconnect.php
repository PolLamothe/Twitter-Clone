<?php
    function disconnect(){
        setcookie('cookieChoice', NULL, time()- 3600,'/');
        setcookie('Pseudo', NULL, time()- 3600,'/');
        setcookie('Password', NULL, time()- 3600,'/');
        session_destroy();
    }
    error_reporting(0);
    if(!isset($_SESSION)){
        session_start();
    }
    require "./function.php";
    //if(isPasswordValid($_SESSION['Pseudo'], $_SESSION['Password'])){
    disconnect();
    //}
?>