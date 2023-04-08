<?php
    require './php/function.php';
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    $pseudo = $_GET['user'];
    $profilePicture = getProfilePicture($pseudo);
    $banner = getBanner($pseudo);
    $biographie = getBiographie($pseudo);
    $numberOfFollowing = getFollowingNumber($pseudo);
    $numberOfFollower = getFollowerNumber($pseudo);

    if($banner == ""){
        $banner = "./data/image/banner/default.webp";
    }
    if($profilePicture == ""){
        $profilePicture = "./data/image/profile_picture/default.webp";
    }

    require './template/profile.html';
    
?>