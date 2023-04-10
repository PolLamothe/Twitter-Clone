<?php
    function deleteFromSignets($user, $Author, $id){
        require 'ID.php';
        if(isInSignet($user,$Author,$id)){
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $smtp = $pdo->prepare("DELETE from signet where User = :user and Author = :Author and ID = :ID");
            $smtp->bindParam(':user',$user);
            $smtp->bindParam(':Author',$Author);
            $smtp->bindParam(':ID',$id);
            $smtp->execute();
        }
    }
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    require './function.php';
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        deleteFromSignets($_SESSION['Pseudo'],$_POST['Author'],$_POST['ID']);
    }
?>