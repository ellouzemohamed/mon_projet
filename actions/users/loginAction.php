<?php



require('actions/database.php');

// Validation du formlaire
if (isset($_POST['validate'])) {

    //vérifier si l'utilisateur à compléter tous les champs
    if(!empty($_POST['pseudo']) AND !empty($_POST['password'])){
        
        //Les données de l'utilisateur
        $users_pseudo=htmlspecialchars($_POST['pseudo']);
        $users_password=htmlspecialchars($_POST['password']);

        //vérifier si l'utilisateur existe
        $checkIfUserExist=$bdd->prepare('SELECT * FROM users where pseudo = ?');
        $checkIfUserExist->execute(array($users_pseudo));


        if($checkIfUserExist->rowCount() > 0){

            // récupérer les données de l'utilisateur
            $userInfo=$checkIfUserExist->fetch();
            //vérifier si le mot de passe est correct
            if(password_verify($users_password,$userInfo['mdp'])){

                //Authentifier l'utilisateur sur le site et récupéere ses données dans des variables globales
                $_SESSION['auth'] = true;
                $_SESSION['id'] = $userInfo['id'];
                $_SESSION['lastname'] = $userInfo['nom'];
                $_SESSION['firstname'] = $userInfo['prenom'];
                $_SESSION['pseudo'] = $userInfo['pseudo'];

                // Rediriger l'utilisateur vers la page d'accueil
                header('Location :index.php');


            }else{
                $errorMsg="Votre Mot de passe est incorrect..........";
            }



        }else{
        $errorMsg="Votre pseudo est incorrect..........";
    }




       
    }else{
        $errorMsg="veuillez complétez tous les champs............";
    }
}
?>

