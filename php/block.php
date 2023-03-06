<?php
    error_reporting(1);
    require './function.php';
    if(!isset($_SESSION)){
        session_start();
    }
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        if($_SESSION['Pseudo'] != $_POST['Blocked']){
            block($_SESSION['Pseudo'],$_POST['Blocked']);
        }else{
            echo 'stop';
        }
        
    }else{
        echo 'mdp invalide';
    }
    
?>