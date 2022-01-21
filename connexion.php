<?php

try {
    $bdd = new PDO('mysql:host=localhost;dbname=user;charset=utf8', 'root', 'root');
    $bdd->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if(isset($_POST['connexion'])){
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $selectAccountReq = $bdd->prepare('SELECT * FROM user WHERE username = ?');
    $selectAccountReq->bindValue(1, $username);
    $selectAccountReq->execute();
    $account = $selectAccountReq->fetch();
    if (password_verify($_POST['password'], $account['password'])){
        echo 'ok';
    }else{
        echo 'nop';
    }
}

?>

<form action="connexion.php" method="post">
    <input type="text" name="username" id="">
    <br>
    <input type="password" name="password" id="">
    <br>
    <input type="submit" name="connexion" value="Connexion">
</form>
