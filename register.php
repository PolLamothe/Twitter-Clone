<?php
    require './php/function.php';
    $alert = '';
    $pass = false;
    $password_correct = false;

    if($_POST){
        $id = isInDataBase('email', $_POST['email']);

        $email = isEmail($_POST['email']);

        if($email == true){
            if($id){
                $alert = '<div class="sorry">
            <p>Désolé mais un compte est déja enregistré avec cet Email</p>
            </div>';
            }else{
                $alert = isPassword($_POST['password']);
                if($alert == 1){
                    if($_POST['password'] == $_POST['passwordVerif']){
                        if(isemail($_POST['pseudo'])){
                            $alert = '<div class="sorry">
                            <p>Désolé mais votre pseudo ne peut pas contenir de "@"</p>
                            </div>';
                        }else{
                            if(isEmailCodeRegistered($_POST['email'])){
                                adddata($_POST['email'],md5($_POST['password']),$_POST['pseudo']);
                                removeEmailCode($_POST['email']);
                                echo "<script>document.location.href='index.php'</script>";
                            }else{
                                $code = '';
                                for($i = 0;$i<6;$i++){
                                    $new_number = rand(0,9);
                                    $code = substr_replace($code,$new_number,$i);
                                }
                                addEmailCode($code, $_POST['email']);
                                send_code_email($_POST['email'],$code);
                                ?>
                                    <div class='emailCode' id='emailCode'>
                                        <div>
                                            <h1>Vérification de votre email</h1>
                                            <p>Veuillez cliquer ci dessous pour aller sur la page de Vérification d'email</p>
                                            <script>
                                            function email_code(){
                                                window.open("email_code.php?email=<?php echo $_POST["email"];?>", "_blank")
                                                document.getElementById('emailCode').style.display= 'none'
                                                <?php $password_correct = true;?>
                                            }
                                            </script>
                                            <button onclick='email_code()'><text>Page de Vérification</text></button> 
                                        </div>
                                    </div>'
                                <?php
                            }
                        }
                    }else{
                        $alert = '<div class="sorry">
                        <p>Désolé mais les deux mot de passes que vous avez rentré ne sont pas identiques</p>
                        </div>';
                    }
                }else{
                    echo $alert;
                }
            }
        }else{
            $alert = '<div class="sorry">
            <p>Désolé mais l\'Email que vous avez rentré n\'est pas valide</p>
            </div>';
        }    
    }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href='./css/register.css' rel="stylesheet">
    <link href="./css/mutual.css" rel="stylesheet">
    <link href="./css/mobile.css" rel="stylesheet">
    <title>Rejoignez Twitter</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <script src="https://kit.fontawesome.com/ecca3bec75.js" crossorigin="anonymous"></script>
</head>

<body bgcolor="#404040">
    <div class="block">
        <i class="fa-solid fa-x fa-xl close" style="color: white;"></i>
        <i class="fa-brands fa-twitter fa-4x bird" style="color: white;" id='twitterBird'></i>
        <p class="textConnect">Rejoignez Twitter</p>
        <form action='' method='post' name='form' style='text-align:center;'>
            <input type="text" placeholder="Email" name="email" class="field"
                value='<?php if($_POST){echo $_POST['email'];}?>' required>
            <input type='text' name='pseudo' placeholder='pseudo' class='field'
                value='<?php if($_POST){echo $_POST['pseudo'];}?>' required>
            <input type="password" placeholder="Mot de Passe" name="password" class="field" value='<?php if($password_correct){echo $_POST['password'];}?>'required>
            <input type='password' name='passwordVerif' placeholder='Vérification du mot de passe' class='field' value='<?php if($password_correct){echo $_POST['password'];}?>'required>
            <input type="submit" value="Valider" class="validate">
        </form>
        <p class="login">Vous avez dejà un compte ? <a style="color: rgb(120, 120, 216);"
                href="../index.php">Connectez-vous</a></p>
    </div>
    <?php echo $alert;?>
    <script src='./js/position.js'></script>
</body>

</html>