<?php
    include './vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    include './vendor/phpmailer/phpmailer/src/Exception.php';
    include './vendor/phpmailer/phpmailer/src/PHPMailer.php';
    include './vendor/phpmailer/phpmailer/src/SMTP.php';
    function isFollowing($follower, $followed){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * FROM follow where follower = :follower and followed = :followed");
        $smtp->bindParam(":follower",$follower);
        $smtp->bindParam(':followed',$followed);
        $smtp->execute();
        return $smtp->fetch();
    }
    function isUserBlocked($blocker, $blocked){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * from block where blocker = :blocker and blocked = :blocked");
        $smtp->bindParam(':blocker',$blocker);
        $smtp->bindParam(':blocked',$blocked);
        $smtp->execute();
        return $smtp->fetch();
    }
    function isPassword($element){
        if(strlen($element) < 8){
             return '<div class="sorry">
            <p>Désolé mais votre mot de passe doit contenir au moins 9 caractères</p>
            </div>';
        }else{
            return true;
        }
    }
    function adddata($email, $passwordInput, $pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $add = $pdo->prepare("insert into users (email, password, pseudo) values (:email, :password, :pseudo)");
        $add->bindParam(':email', $email);
        $add->bindParam(':password', $passwordInput);
        $add->bindParam(':pseudo', $pseudo);
        $add->execute();

        $smtp = $pdo->prepare("INSERT into users_data (Pseudo, creationdate) values (:pseudo, :creationdate)");
        $smtp->bindParam(':pseudo',$pseudo);
        $date = date("Y-m-d H:i:s"); 
        $smtp->bindParam(':creationdate',$date);
        $smtp->execute();
    }
    function isDigits($element) {
        return !preg_match ("/[^0-9]/", $element);
    }
    function isEmail($element){
        if(strpos($element, '@') == true){
            return true;
        }else{
            return false;                               
        }
    }
    function isInDataBase($dataType, $data){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * FROM users WHERE ".$dataType."=?");
        $smtp->execute([$data]);  
        $id = $smtp->fetch();

        return $id;
    }
    function send_code_email($email, $code){
        require 'ID.php';
        $mail = new PHPMailer(true);

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        ); 

        try {
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $emailSender;                     //SMTP username
            $mail->Password   = $emailPassword;                               //SMTP password
            $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_STARTTLS';            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($emailSender, 'Pol Lamothe');
            $mail->addAddress($email);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Votre code pour vérifier votre Email';
            $mail->Body    = $code;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
        } catch (Exception $e) {
            echo "<script>Alert(Le mail n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo})</script>";
        }
    }
    function addEmailCode($code, $email){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

        $verify = $pdo->prepare("SELECT * FROM email_code WHERE email =?");
        $verify->execute([$email]);  
        $id = $verify->fetch();

        if($id){
            $delete = $pdo->prepare("DELETE FROM email_code where email = :email");
            $delete->bindParam(':email', $email);
            $delete->execute();
        }

        $add = $pdo->prepare("INSERT into email_code(email, code) values (:email, :code)");
        $add->bindParam(':email',$email);
        $add->bindParam(':code',$code);
        $add->execute();
    }
    function isCodeInDataBase($email, $code){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * FROM email_code WHERE email = :email and code = :code");
        $smtp->bindParam(':email', $email);
        $smtp->bindParam(':code', $code);
        $smtp->execute();  
        $id = $smtp->fetch();

        return $id;
    }
    function setUserAsRegistered($email){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("update email_code set registered = true where email = :email");
        $smtp->bindParam(':email', $email);
        $smtp->execute();
    }
    function isEmailCodeRegistered($email){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * from email_code where email = :email");
        $smtp->bindParam(':email', $email);
        $smtp->execute();
        $isCodeGenerated = $smtp->fetch();
        if($isCodeGenerated){
            $smtp  = $pdo->prepare("SELECT * from email_code where email = :email and registered = true");
            $smtp->bindParam(':email',$email);
            $smtp->execute();
            return $smtp->fetch();
        }else{
            return false;
        }
    }
    function removeEmailCode($email){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("DELETE from email_code where email = :email");
        $smtp->bindParam(':email',$email);
        $smtp->execute();
    }
    function getPseudoFromEmail($email){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT pseudo from users where email = :email");
        $smtp->bindParam(":email",$email);
        $smtp->execute();
        $array = $smtp->fetch();
        return $array[0];
    }
    function doesThisTableExist($pseudo){
        $pseudo = strtolower($pseudo);
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("Show tables");
        $smtp->execute();
        $rows = $smtp->fetchAll();
        $doesItExist = false;
        foreach($rows as $row){
            if($row[0] == ($pseudo."_post")){
                $doesItExist = true;
            }
        }
        if($doesItExist){return 'yes';}
        if(!$doesItExist){return 'no';} 
    }
    function isPasswordValid($pseudo, $password1){  
        $password1 = md5($password1); 
        if(isEmail($pseudo)){
            $pseudo = getPseudoFromEmail($pseudo);
        }   
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT password from users where pseudo = :pseudo");
        $smtp->bindParam(':pseudo',$pseudo);
        $smtp->execute();
        if($smtp->fetch()[0] == $password1){
            return true;
        }else{
            return false;
        }
    }
    function getProfilePicture($pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT profile_picture from users_data where pseudo = :pseudo");
        $smtp->bindParam(':pseudo',$pseudo);
        $smtp->execute();
        return $smtp->fetch()[0];
    }
    function getTweetData($pseudo, $id){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * from all_post where Pseudo = :pseudo and ID = :ID");
        $smtp->bindParam(':pseudo',$pseudo);
        $smtp->bindParam(':ID',$id);
        $smtp->execute();
        $smtp = $smtp->fetch();
        $data = array($smtp[0], $smtp[1], $smtp[2], $smtp[3],$smtp[4]);
        return $data;
    }
    function displayTweet($user, $id,$isFollowing, $source=""){
        $data = getTweetData($user, $id);
        $isFollowing = $isFollowing;
        $pseudo = $data[0];
        $message = $data[1];
        $object = $data[2];
        $creationDate = $data[3];
        $profilePicture = getProfilePicture($user);
        if($source == 'createTweet.php'){
            include("../template/tweet.html");
        }else{
            require("./template/tweet.html");
        }  
    }
    function getCurrentId($pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT ID from all_post where pseudo = :pseudo order by ID DESC");
        $smtp->bindParam(':pseudo',$pseudo);
        $smtp->execute();
        $array = $smtp->fetch();
        if(gettype($array) == 'array'){
            return $array[0]; 
        }else{
            return 0;
        }
               
    }
    function getPostTableData(){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * from `all_post` order by creationdate DESC , ID asc");
        $smtp->execute();
        $array = $smtp->fetchAll();
        $numberOfLine =  count($array);
        for($i = 0; $i < $numberOfLine;$i++){
            $pseudo = $array[$i][0];
            $id = $array[$i][4];
            if($pseudo != $_SESSION['Pseudo']){
                if(gettype(isUserBlocked($_SESSION['Pseudo'],$pseudo)) == 'boolean'){
                    if(isUserBlocked($_SESSION['Pseudo'],$pseudo) == false){
                        $isFollowing = isFollowing($_SESSION['Pseudo'],$pseudo);
                        displayTweet($pseudo,$id,$isFollowing,'creatTweet.php');
                    }
                }
            }
        }
    }
    function getTweetNumber($pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * from all_post where pseudo = :pseudo");
        $smtp->bindParam(':pseudo', $pseudo);
        $smtp->execute();
        return count($smtp->fetchAll());
    }
    function getBanner($pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT banner from users_data where pseudo = :pseudo");
        $smtp->bindParam(':pseudo',$pseudo);
        $smtp->execute();
        return $smtp->fetch()[0];
    }
    function getBiographie($pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('SELECT biographie from users_data where pseudo = :pseudo');
        $smtp->bindParam(':pseudo', $pseudo);
        $smtp->execute();
        return $smtp->fetch()[0];
        
    }
    function getFollowingNumber($pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('SELECT * from follow where follower = :follower');
        $smtp->bindParam(':follower',$pseudo);
        $smtp->execute();
        $result = $smtp->fetchAll();
        if(gettype($result) == 'boolean'){
            return 0;
        }elseif(gettype($result) == 'string'){
            return 1;
        }else{
            return count($result);
        }
    }
    function getFollowerNumber($followed){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp= $pdo->prepare('SELECT * from follow where followed = :followed');
        $smtp->bindParam(':followed',$followed);
        $smtp->execute();
        $result = $smtp->fetchAll();
        if(gettype($result) == 'boolean'){
            return 0;
        }elseif(gettype($result) == 'string'){
            return 1;
        }else{
            return count($result);
        }
    }
    function changeBanner($pseudo,$banner){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("UPDATE users_data set banner = :banner where pseudo = :pseudo");
        $smtp->bindParam(':banner',$banner);
        $smtp->bindParam(':pseudo',$pseudo);
        $smtp->execute();
    }
    function changeBiographie($pseudo, $biographie){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("UPDATE users_data set biographie = :biographie where pseudo = :pseudo");
        $smtp->bindParam(':biographie',$biographie);
        $smtp->bindParam(':pseudo',$pseudo);
        $smtp->execute();
    }
    function changeProfilePicture($pseudo, $profilePicture){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("UPDATE users_data set profile_picture = :profile_picture where pseudo = :pseudo");
        $smtp->bindParam(':pseudo',$pseudo);
        $smtp->bindParam(':profile_picture', $profilePicture);
        $smtp->execute();
    }
    function displayUserTweet($pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $numberOfTweet = getTweetNumber($pseudo);
        $smtp = $pdo->prepare("SELECT ID from all_post where pseudo = '".$pseudo."' order by CreationDate DESC");
        $smtp->execute();
        $array = $smtp->fetchAll();
        for($i = 1;$i <= $numberOfTweet; $i++){
            $isFollowing = isFollowing($_SESSION['Pseudo'], $pseudo);
            displayTweet($pseudo, $array[$i-1][0], $isFollowing);
        }
    }
    function isInSignet($user, $Author, $id){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * from signet where User = :user and Author = :Author and Id = :ID");
        $smtp->bindParam(':user',$user);
        $smtp->bindParam(':Author',$Author);
        $smtp->bindParam(':ID',$id);
        $smtp->execute();
        $final = $smtp->fetch();
        return $final;
    }
    function getAllSignetsTweet($pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('SELECT Author, ID from signet where User = :User');
        $smtp->bindParam(':User',$pseudo);
        $smtp->execute();
        $numberOfLine = $smtp->rowCount();
        $array = $smtp->fetchAll();
        for($x = 0;$x<$numberOfLine;$x++){
            displayTweet($array[$x]['Author'],$array[$x]['ID'],isFollowing($_SESSION['Pseudo'],$array[$x]['Author']));
        }
    }
    function isTweetLiked($user, $Author, $id){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('SELECT * from likepost where User = :user and Author = :author and ID = :ID');
        $smtp->bindParam(':user',$user);
        $smtp->bindParam(':author',$Author);
        $smtp->bindParam(':ID',$id);
        $smtp->execute();
        return $smtp->fetch();
    }
    function getNumberOfLike($Author, $id){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('SELECT * from likepost where Author = "'.$Author.'" and ID = "'.$id.'"');
        $smtp->execute();
        return $smtp->rowCount();
    }
    function getNumberOfComment($pseudo, $id){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * from commentaire where PostUser = '".$pseudo."' and ID_Post = '".$id."'");
        $smtp->execute();
        return $smtp->rowCount();
    }
    function getCommentID($PostUser, $ID_Post){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT ID_Comment from commentaire where PostUser = '".$PostUser."' and ID_Post = '".$ID_Post."' order by ID_Comment desc");
        $smtp->execute();
        if(getNumberOfComment($PostUser, $ID_Post) == 0){
            return 1;
        }
        return $smtp->fetchAll()[0][0] +1;
    }
    function isTweetExisting($PostUser,$ID_POST,$ID_Comment){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare('SELECT * from commentaire where PostUser = "'.$PostUser.'" and ID_Post = "'.$ID_POST.'"and ID_Comment = "'.$ID_Comment.'"');
        $smtp->execute();
        return $smtp->fetch();
    }
    function getCommentDataOnAPost($PostUser, $ID_Post, $ID_Comment){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * from commentaire where PostUser = :PostUser and ID_Post = :ID_Post and ID_Comment = '".$ID_Comment."' and IsComment = 0");
        $smtp->bindParam(':PostUser',$PostUser);
        $smtp->bindParam(':ID_Post',$ID_Post);
        $smtp->execute();
        $smtp = $smtp->fetch();
        $data = array($smtp[0], $smtp[1]);
        return $data;
    }
    function displayComment($user, $id,$ID_Comment,$isFollowing, $source=""){
        $data = getCommentDataOnAPost($user, $id,$ID_Comment);
        $isFollowing = $isFollowing;
        $pseudo = $data[1];
        $message = $data[0];
        $object = null;
        $creationDate = '';
        $profilePicture = getProfilePicture($pseudo);
        if($source == 'createTweet.php'){
            include("../template/comment.html");
        }else{
            require("./template/comment.html");
        }  
    }
    function getCommentaireTableData($PostUser, $ID_Post){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT * from commentaire where PostUser = '".$PostUser."' and ID_Post = '".$ID_Post."' and IsComment = 0");
        $smtp->execute();
        $array = $smtp->fetchAll();
        $numberOfLine =  count($array);
        for($i = 0; $i < $numberOfLine;$i++){
            if(gettype(isUserBlocked($_SESSION['Pseudo'],$pseudo)) == 'boolean'){
                if(isUserBlocked($_SESSION['Pseudo'],$pseudo) == false){
                    $ID_Comment = $array[$i][4];
                    displayComment($PostUser,$ID_Post,$ID_Comment, true);
                }
            }
        }
    }
    function getAllTweetIDofAUser($pseudo){
        require 'ID.php';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $smtp = $pdo->prepare("SELECT ID from all_post where Pseudo = '".$pseudo."'");
        $smtp->execute();
        return $smtp->fetchAll();
    }
?>