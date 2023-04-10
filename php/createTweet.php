<?php
    function createANewTweet($pseudo, $message, $object, $creationDate, $ID){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("INSERT into all_post (pseudo, message, object, creationDate, ID) values ('".$pseudo."','".$message."','".$object."','".$creationDate."','".$ID."')");
        $smtp->execute();
    }
    error_reporting(1);
    require './function.php';
    if(!isset($_SESSION)){
        session_start();
    }
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password'])){
        $date = date("Y-m-d H:i:s");
        $id = getCurrentId($_SESSION['Pseudo']) + 1;
        $target = '../data/image/post_object/';
        if(isset($_FILES['file'])){
            $fileTarget = $target.$_SESSION['Pseudo'].$id;
            $tmp_name = $_FILES['file']['tmp_name'];	
            move_uploaded_file($tmp_name, $fileTarget);
        }else{
            $fileTarget = "";
        }
        createANewTweet($_SESSION['Pseudo'], $_POST['message'],$fileTarget,$date,$id);
        return displayTweet($_SESSION['Pseudo'],getCurrentId($_SESSION['Pseudo']),'','createTweet.php');
    }
?>