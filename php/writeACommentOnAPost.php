<?php
    function writeACommentonAPost($message, $Author, $PostUser, $ID_POST, $ID_Comment){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("INSERT into commentaire (Message, Author, PostUser, ID_Post, ID_Comment, IsComment, ID_OfTheComment) values ('".$message."','".$Author."','".$PostUser."',".$ID_POST.",".$ID_Comment.",false, null)");
        $smtp->execute();
    }
    error_reporting(1);
    require './function.php';
    if(!isset($_SESSION)){
        session_start();
    }
    $ID_Comment = getCommentID($_POST['PostUser'],$_POST['ID_Post']); 
    if(isPasswordValid($_SESSION['Pseudo'],$_SESSION['Password']) && !isTweetExisting($_POST['PostUser'], $_POST['ID_Post'], $ID_Comment)){
        writeACommentonAPost($_POST['Message'], $_POST['Author'],$_POST['PostUser'],$_POST['ID_Post'],$ID_Comment);
    }
?>