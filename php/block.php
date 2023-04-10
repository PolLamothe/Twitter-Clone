<?php
    function block($blocker , $blocked){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('INSERT into block (blocker, blocked) values (:blocker , :blocked)');
        $smtp->bindParam(':blocker',$blocker);
        $smtp->bindParam(':blocked', $blocked);
        $smtp->execute();
    }
    error_reporting(1);
    require './function.php';
    if(!isset($_SESSION)){
        session_start();
    }
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        if($_SESSION['Pseudo'] != $_POST['Blocked']){
            block($_SESSION['Pseudo'],$_POST['Blocked']);
        }
        
    }
    
?>