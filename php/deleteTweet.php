<?php
    error_reporting(0);
    if(!isset($_SESSION)){
        session_start();
    }
    require "./function.php";
    if(isPasswordValid($_POST['pseudo'], $_SESSION['Password'])){
        deleteTweet($_POST['pseudo'], $_POST['id'], 'createTweet.php');
    }else{
        echo'cock';
    }
?>