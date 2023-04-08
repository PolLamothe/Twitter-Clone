<?php
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