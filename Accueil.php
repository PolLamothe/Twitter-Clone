<?php
    require './php/function.php';
    if(!isset($_SESSION)){
        session_start();
    }
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        $profilePicture = getProfilePicture($_SESSION['Pseudo']);
        if($profilePicture == ''){
            $profilePicture = "./data/image/profile_picture/default.webp";
        }
        if(isset($_POST['origin']) == false){
            $user = $_SESSION['Pseudo'];
            require('./template/emptyAccueil.html');
        }
    }else{
        echo '<script> document.location.href="index.php"; </script>';
    }
?>