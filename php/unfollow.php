<?php
    function unfollow($follower, $followed){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("DELETE from follow where follower = :follower and followed = :followed");
        $smtp->bindParam(":follower",$follower);
        $smtp->bindParam(":followed",$followed);
        $smtp->execute();
    }
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    require './function.php';
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        if($_SESSION['Pseudo'] != $_POST['Followed']){
            if(isFollowing($_SESSION['Pseudo'],$_POST['Followed'])){
                unfollow($_SESSION['Pseudo'], $_POST['Followed']);
            }
        }else{
            echo'erreur';
        }
    }else{echo $_SESSION['Pseudo'].$_SESSION['Password'];}
?>