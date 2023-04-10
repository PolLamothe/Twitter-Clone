<?php
    function follow($follower, $followed){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("INSERT into follow (follower, followed) values ('".$follower."','".$followed."')");
        $smtp->execute();
    }
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    require './function.php';
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        if(!isFollowing($_SESSION['Pseudo'], $_POST['Followed'])){
            if( $_SESSION['Pseudo'] != $_POST['Followed']){
                follow($_SESSION['Pseudo'], $_POST['Followed']);
            }
        }
        
    }
?>