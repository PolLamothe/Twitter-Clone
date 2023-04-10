<?php
    function addLike($user, $Author, $id){
        require 'ID.php';
        $date = date('d-m-y h:i:s');
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('INSERT into likepost (User, Author, ID, date) values (:user,:author,:ID, :date)');
        $smtp->bindParam(':user',$user);
        $smtp->bindParam(':author',$Author);
        $smtp->bindParam(':ID',$id);
        $smtp->bindParam(':date', $date);
        $smtp->execute();
        return 'added';
    }
    function removeLike($user, $Author, $id){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('DELETE from likepost where User = "'.$user.'" and Author = "'.$Author.'" and ID = "'.$id.'"');
        $smtp->execute();
        return 'removed';
    }
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