<?php
session_start();
require 'includes/inc-db-connect.php';

if(!empty($_POST['submit']))
{

    $errors = [];

    if(empty($_POST['email']))
        $errors['email'] = "Saississez votre email.";

    if(empty($_POST['password']))
        $errors['password'] = "Saississez votre mot de passe.";

    
    if(count($errors) > 0)
    {
        $_SESSION['errors'] = $errors;
        $_SESSION['values'] = $_POST;
        header("Location: /login.php"); die;
    }


    // 1. On cherche en base de donnée l'utilisateur avec son email
    $email = htmlspecialchars($_POST['email']); // Pour éviter : 1' OR '1' = '1'; //
    $password = htmlspecialchars($_POST['password']); // Pour éviter : 1' OR '1' = '1

    $sql = "SELECT * FROM utilisateur WHERE email_utilisateur = '" . $email . "'";
        
    $result = $dbh->query($sql);
    $user = $result->fetch(PDO::FETCH_ASSOC);

    // 2. On test si l'utilisateur existe
    if($user)
    {        
        // 4. S'il existe alors on compare les mots de passes
        if(password_verify($password, $user['mdp_utilisateur']))
        {
            // On recupère les roles de l'utilisateur
            $sql = "SELECT role.libelle FROM utilisateur_role 
            JOIN role ON role.id_role = utilisateur_role.id_role 
            WHERE id_utilisateur = :id_utilisateur";
            $query = $dbh->prepare($sql);
            $res = $query->execute([
                'id_utilisateur' => $user['id_utilisateur']
            ]);
            $roles = $query->fetchAll(PDO::FETCH_COLUMN);

            if(!in_array("ROLE_UTILISATEUR", $roles))
            {
                header("Location: /login.php"); die;
            }

            // 5. Si mdp OK alors on identifie l'utilisateur en SESSION et on redirige vers la page admin
            session_start();



            // On stock les infos de l'utilisateur en session
            $_SESSION['user'] = [
                'id' => $user['id_utilisateur'],
                'firstname' => $user['prenom_utilisateur'],
                'roles' => $roles
            ];


            // On redirige l'utilisateur en fonction de son statut
            if(in_array("ROLE_ADMIN", $roles)){
                header("Location: /admin"); die;
            }
            else
            {
                header("Location: /"); die;
            }


        }
        else // 6. Si mdp KO alors on redirige vers la page de login
        {
            $_SESSION['error'] = "Identifiants invalides.";
            header("Location: /login.php"); die;
        }

    }
    else  // 3. S'il n'existe pas, on redirige vers la page de login
    {
        $_SESSION['error'] = "Identifiants invalides.";
        header("Location: /login.php"); die;
    }


    

}
else
{
    header("Location: /login.php"); die;
}