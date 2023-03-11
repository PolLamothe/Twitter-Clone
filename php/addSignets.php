<?php
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    require 'function.php';
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        addSignets($_SESSION['Pseudo'],$_POST['Author'],$_POST['ID']);
    }
    addSignets($_SESSION['Pseudo'],$_POST['Author'],$_POST['ID']);
?>