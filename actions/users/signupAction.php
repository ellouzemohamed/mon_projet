<?php


require('actions/database.php');

// Validation du formlaire
if (isset($_POST['validate'])) {

    //vérifier si l'utilisateur à compléter tous les champs
    if(!empty($_POST['pseudo']) AND !empty($_POST['lastname']) AND !empty($_POST['firstname']) AND !empty($_POST['password'])){
        
        //Les données de l'utilisateur
        $users_pseudo=htmlspecialchars($_POST['pseudo']);
        $users_lastname=htmlspecialchars($_POST['lastname']);
        $users_firstname=htmlspecialchars($_POST['firstname']);
        $users_password=password_hash($_POST['password'] , PASSWORD_DEFAULT);

        // Vérifier si l'utilisateur existe déja
        $checkIfUserAllReadyExists=$bdd -> prepare('SELECT pseudo FROM users WHERE pseudo=?');
        $checkIfUserAllReadyExists->execute(array($users_pseudo)); 


        if($checkIfUserAllReadyExists->rowCount() == 0){

            //Insérer l'utilisateur dans la base donnée
            $inserUserOnWebsite = $bdd -> prepare('INSERT INTO users (pseudo , nom , prenom , mdp) VALUES(?, ?, ?, ?) ');
            $inserUserOnWebsite->execute(array($users_pseudo , $users_lastname, $users_firstname, $users_password)); 

            // Récupérer les informations de l'utilisateur
            $infoOfThisUserReq=$bdd ->prepare('SELECT id, pseudo , nom , prenom FROM users WHERE nom=? AND prenom=? AND pseudo=?' );
            $infoOfThisUserReq ->execute(array( $users_lastnpseudoame, $users_firstname ,$users_pseudo ));


            //Authentifier l'utilisateur sur le site et récupéere ses données dans des variables globales
            $userInfo =$infoOfThisUserReq->fetch() ;
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $userInfo['id'];
            $_SESSION['lastname'] = $userInfo['nom'];
            $_SESSION['firstname'] = $userInfo['prenom'];
            $_SESSION['pseudo'] = $userInfo['pseudo'];
            

            // Reediriger l'utilisateur vers la page d'accueil
            header('Location :index.php');




        }else {
            $errorMsg="L'utlisateur existe déja";

        }
    }else{
        $errorMsg="veuillez complétez tous les champs";
    }
}
?>