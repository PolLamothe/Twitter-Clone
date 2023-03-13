<?php
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    require './function.php';
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        if(!isTweetLiked($_SESSION['Pseudo'],$_POST['Author'],$_POST['ID'])){
            echo addLike($_SESSION['Pseudo'],$_POST['Author'],$_POST['ID']);
        }else{
            echo removeLike($_SESSION['Pseudo'],$_POST['Author'],$_POST['ID']);
        }
    }
?>