<?php
    require './function.php';
    if(!isset($_SESSION)){
        session_start();
    }
    displayTweet($_SESSION['Pseudo'], getCurrentId($_SESSION['Pseudo']));
?>