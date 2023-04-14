<?php 
    function deleteUser($pseudo){
        require "ID.php";
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('DELETE from users where Pseudo = "'.$pseudo.'";
         DELETE from users_data where Pseudo = "'.$pseudo.'"; 
         DELETE from signet where User = "'.$pseudo.'";
         DELETE from likepost where User = "'.$pseudo.'";     
         DELETE from follow where follower = "'.$pseudo.'" or followed = "'.$pseudo.'";
         DELETE from commentaire where Author = "'.$pseudo.'" or PostUser = "'.$pseudo.'";
         DELETE from block where Blocker = "'.$pseudo.'" or Blocked = "'.$pseudo.'";
         DELETE from all_post where Pseudo = "'.$pseudo.'";
         DELETE from users where Pseudo = "'.$pseudo.'"');
        $smtp->execute();
        setcookie('Pseudo', '', time()- 3600);
        setcookie('Password', '', time()- 3600);
        setcookie('cookieChoice', '', time()- 3600);
        session_destroy();
    }
    error_reporting(0);
    if(!isset($_SESSION)){
        session_start();
    }
    require "./function.php";
    if(isPasswordValid($_SESSION['Pseudo'], $_SESSION['Password'])){
        deleteUser($_SESSION['Pseudo']);
    }
?>