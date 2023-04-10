<?php
    function addSignets($user, $Author, $id){
        require 'ID.php';
        if(isInSignet($user,$Author,$id) == false){
            $date = date('d-m-y h:i:s');
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $smtp = $pdo->prepare("INSERT into signet (User,Author,ID,Date) values (:user, :author, :ID, :date)");
            $smtp->bindParam(':user', $user);
            $smtp->bindParam(':author',$Author);
            $smtp->bindParam(':ID',$id);
            $smtp->bindParam(':date',$date);
            $smtp->execute();
        }
    }
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    require 'function.php';
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        addSignets($_SESSION['Pseudo'],$_POST['Author'],$_POST['ID']);
    }
?>