<?php

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=user;charset=utf8', 'root', 'root');
        $bdd->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    function generateToken($len){
        $letter = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $token = "";
        $lenStr = strlen($letter);
        for ($i = 0; $i < $len; $i++){
            $random = rand(0,$lenStr);
            $token .= $letter[$random];
        }
        return $token;
    }

    if (isset($_POST['create_acount'])){
        $errors =[];
        if(isset($_POST['username']) && !empty($_POST['username'])){
            if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $_POST['username'])){
                array_push($errors, "Votre nom d'utilisateur n'est pas conforme");
            }
        }else{
            array_push($errors,"Vous n'avez pas entré de nom d'utilisateur");
        }


        if (isset($_POST['password']) && !empty($_POST['password'])){
            if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $_POST['password'])){
                array_push($errors, "Mot de passe pas conforme");
            }
        }else{
            array_push($errors, "Vous n'avez pas entré de mots de passe");
        }

        if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])){
            if ($_POST['confirm_password'] != $_POST['password']){
                array_push($errors, "Votre confirmation ne correspond pas au mot de passe");
            }
        }else{
            array_push($errors, "Vous n'avez pas confirmé votre mot de passe");
        }

        if (isset($_POST['email']) && !empty($_POST['email'])){
            if (!preg_match( "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i",$_POST['email'])){
                array_push($errors, "Votre mail n'est pas conforme");
            }
        }else{
            array_push($errors, "Vous n'avez pas entré de mails");
        }

        if ($errors){
            foreach ($errors as $error) {
                echo $error;
                echo "<br>";
            }
        }else{
            $token = generateToken(30);
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $mail = htmlspecialchars($_POST['email']);
            $insertBdd = $bdd->prepare('INSERT INTO user (username, password,mail,token) VALUES (?,?,?,?)');
            $insertBdd->bindValue(1,$username);
            $insertBdd->bindValue(2,$passwordHash);
            $insertBdd->bindValue(3,$mail);
            $insertBdd->bindValue(4,$token);
            $insertBdd->execute();
            echo "Mail envoyé à : " . $mail . "Pour confirmer cliquez sur <a href='confirmation.php?token=" . $token . "'";
            $to = $mail;
            $subject = "Votre inscription sur dendo";
            $from="Michel@fermier.fr";
            $msg="
            <p>Bonjour et merci pour votre inscription à notre site.</p>:q
            
            <p>Afin de finaliser votre inscription veuillez cliquer sur ce lien afin de confirmer votre inscription <a href='http://localhost:8889/tets/confirmation.php?token=" . $token . "'>inscription</a></p>
            ";
            $mailto = mail($to,$subject,$msg);
            if ($mailto){
                echo "Mail envoyé";
            }else{
                echo "Mail non délivré";
            }

        }
    }
?>

<form action="inscription.php" method="post">
    <label for="username">Identifiant : </label>
    <input type="text" name="username" id="username">
    <br>
    <label for="password">Mot de passe : </label>
    <input type="password" name="password" id="password">
    <br>
    <label for="confirmPassword">Confirmation de mot de passe : </label>
    <input type="password" name="confirm_password" id="confirmPassword">
    <br>
    <label for="email">Mail : </label>
    <input type="email" name="email" id="email">
    <br>
    <input type="submit" name="create_acount" value="Créer compte">
</form>
