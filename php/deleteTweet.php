<?php
    function deleteTweet($pseudo, $id){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT object from all_post where pseudo = '".$pseudo."' and id = '".$id."'");
        $smtp->execute();
        $object = $smtp->fetch()[0];
        if($object != NULL){
            unlink($object);
        }
        $smtp = $pdo->prepare("DELETE from all_post where pseudo = '".$pseudo."' and id = ".$id."");
        $smtp->execute();
        $smtp = $pdo->prepare('DELETE from commentaire where ID_Post = "'.$id.'" and PostUser = "'.$pseudo.'"');
        $smtp->execute();
        $smtp =  $pdo->prepare("DELETE from likepost where ID = '".$id."' and Author = '".$pseudo."'");
        $smtp->execute();
        $smtp =  $pdo->prepare("DELETE from signet where Author = '".$pseudo."' and ID = '".$id."'");
        $smtp->execute();
    }
    error_reporting(0);
    if(!isset($_SESSION)){
        session_start();
    }
    require "./function.php";
    if(isPasswordValid($_POST['pseudo'], $_SESSION['Password'])){
        deleteTweet($_POST['pseudo'], $_POST['id'], 'createTweet.php');
    }
?>