<?php
    if(!isset($_SESSION)){
        session_start();
    }
    require './function.php';
    error_reporting(1);

    if(isPasswordValid($_SESSION['Pseudo'], $_SESSION['Password'])){
        if(isset($_FILES['profilePictureImage'])){
            $target = '../data/image/profile_picture/';
            $fileTarget = $target.$_SESSION['Pseudo'];
            $tmp_name = $_FILES['profilePictureImage']['tmp_name'];
            move_uploaded_file($tmp_name, $fileTarget);
            changeProfilePicture($_SESSION['Pseudo'],$fileTarget);
        }
        if(isset($_FILES['bannerImage'])){
            $target = '../data/image/banner/';
            $fileTarget = $target.$_SESSION['Pseudo'];
            $tmp_name = $_FILES['bannerImage']['tmp_name'];
            move_uploaded_file($tmp_name, $fileTarget);
            changeBanner($_SESSION['Pseudo'],$fileTarget);
        }
    
        changeBiographie($_SESSION['Pseudo'], $_POST['biographie']);
    }
    
?>