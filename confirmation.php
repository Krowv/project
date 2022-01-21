<?php
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=user;charset=utf8', 'root', 'root');
        $bdd->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if(isset($_GET['token'])){
        $searchIfExist = $bdd->prepare('SELECT * FROM user WHERE token=?');
        $searchIfExist->bindValue(1, $_GET['token']);
        $searchIfExist->execute();
        $test = $searchIfExist->fetch();
        if ($test['valide'] == 1){
            echo "Votre adresse à déjà été validée";
        }
        else{
            $updateUserReq = $bdd->prepare('UPDATE user SET valide = 1, validate_at = current_timestamp WHERE token = ?');
            $updateUserReq->bindValue(1,$_GET['token']);
            $updateUserReq->execute();

            echo "Votre compte à bien été validé.";
        }
    }