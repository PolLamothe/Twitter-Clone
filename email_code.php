<?php
    $alert = '';
    require './php/function.php';
    if($_POST){
        if(isCodeInDataBase($_GET['email'], $_POST['email_code'])){
            setUserAsRegistered($_GET['email']);
            ?>
            <script>window.alert('maintenant veuillez retourner sur la page d\' inscription et cliquer sur valider');
            window.close()</script>
            <?php
        }else{
            $alert = '<div class="sorry">
            <p>Désolé mais le code que vous avez rentré n\'est pas le bon</p>
            </div>';
        }
    }
?>

<!DOCTYPE html>
<head>
    <meta charset='UTF-8'>
    <title>Vérification de votre email</title>
    <link href='./css/register.css' rel="stylesheet">
    <link href='./css/mutual.css' rel='stylesheet'>
    <link href='./css/email_code.css' rel='stylesheet'>  
</head>
<body bgcolor='#404040'> 
    <div class='emailCode'>
        <div>
            <h1>Vérification de votre email</h1>
            <p>Veuillez entrer le code a 6 chiffres qui vous a été envoyé par mail (ne pas fermer cette page)</p>
            <form action='' method='post' name='mailVerifForm'>
                <input type='text' placeholder='Code' maxlength='6' pattern='[0-9]+' id='EmailCode' name='email_code'required>
            </form> 
        </div>
    </div>
    <?php echo $alert;?>
</body>
