<?php
    require './php/function.php';
    error_reporting(0);
    $alert = '';
    if($_POST){
        if(isset($_POST['cookieChoice']) || isset($_COOKIE['Pseudo'])){
            if($_POST['cookieChoice'] == 'Yes' || isset($_COOKIE['Pseudo'])){
                if(!isset($_SESSION)){
                    session_start();
                }
                setcookie('cookieChoice','accepted',time() + (10 * 365 * 24 * 60 * 60));
                setcookie('Pseudo',$_SESSION['Pseudo'],time() + (10 * 365 * 24 * 60 * 60));
                setcookie('Password',$_SESSION['Password'],time() + (10 * 365 * 24 * 60 * 60));
                echo '<script> document.location.href="Accueil.php"; </script>';
            }else{
                echo '<script> document.location.href="Accueil.php"; </script>';
            }
        }else{
            if(isEmail($_POST['email'])){
                if(isInDataBase('email',$_POST['email'])){
                    if(isPasswordValid($_POST['email'],$_POST['password'])){
                        $alert = 'valid';
                    }else{
                        $alert = '<div class="sorry">
                    <p>Désole mais le mot passe que vous avez rentré est incorrecte</p>
                    </div>';
                    }
                }else{
                    $alert = '<div class="sorry">
                    <p>Désole mais le mail que vous avez rentré n\'est pas enregistré</p>
                    </div>';
                }
            }else{
                if(isInDataBase('pseudo',$_POST['email'])){
                    if(isPasswordValid($_POST['email'],$_POST['password'])){
                        $alert = 'valid';
                    }else{
                        $alert = '<div class="sorry">
                    <p>Désole mais le mot passe que vous avez rentré est incorrecte</p>
                    </div>';
                    }
                }else{
                    $alert = '<div class="sorry">
                    <p>Désole mais le Pseudo que vous avez rentré n\'est pas enregistré</p>
                    </div>';
                }
            }
            if($alert == "valid"){
                session_start();
                if(isemail($_POST['email'])){
                    $Pseudo =getPseudoFromEmail($_POST['email']);
                }else{
                    $Pseudo = $_POST['email'];
                }
                if(isInDataBase('Pseudo',$Pseudo)){
                    if(isset($_COOKIE['Pseudo']) & isset($_COOKIE['Password'])){
                        echo 'cookie';
                    }else{
                        if(!isset($_SESSION)){
                            session_start();
                        }
                        $_SESSION['Pseudo'] = $Pseudo;
                        $_SESSION['Password'] = $_POST['password'];
                        include './template/cookieAccept.html';      
                    }
                }
            }
        }
        
    }else{
        if(isset($_COOKIE['cookieChoice'])){
            if(isPasswordValid($_COOKIE['Pseudo'],$_COOKIE['Password'])){
                if(!isset($_SESSION)){
                    session_start();
                }
                $_SESSION['Pseudo'] = $_COOKIE['Pseudo'];
                $_SESSION['Password'] = $_COOKIE['Password'];
                echo '<script> document.location.href="Accueil.php"; </script>';
            }
        }
    }     
    require './template/index.html';                                              
?>

