<?php
    require './php/function.php';
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        $pseudo = $_GET['Pseudo'];
        $id = $_GET['ID'];
        $profilePicture = getProfilePicture($_GET['Pseudo']);
        if($profilePicture == ''){
            $profilePicture = "./data/image/profile_picture/default.webp";
        }
        require './template/post.html';
    }else{
        echo '<script> document.location.href="index.php"; </script>';
    }
?>