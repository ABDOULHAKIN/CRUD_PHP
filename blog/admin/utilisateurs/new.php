<?php

require '../includes/inc-session-check.php';
require '../includes/inc-check-role-super-admin.php';
require '../../includes/inc-db-connect.php';

// Traiter le formulaire si envoyé
if(!empty($_POST['submit']))
{

    $sql = "INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email_utilisateur, mdp_utilisateur) 
    VALUES (:nom_utilisateur, :prenom_utilisateur, :email_utilisateur, :mdp_utilisateur)";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'nom_utilisateur' => $_POST['nom_utilisateur'],
        'prenom_utilisateur' => $_POST['prenom_utilisateur'],
        'email_utilisateur' => $_POST['email_utilisateur'],
        'mdp_utilisateur' => password_hash($_POST['mdp_utilisateur'], PASSWORD_DEFAULT)
    ]);

    if($res)
    {
        header("Location: /admin/utilisateurs"); exit;
    }
    else
    {
        echo "Un erreur est survenue...";
    }

}

require '../includes/inc-top.php';
?>
<div class="container py-5">
    <h1>Création d'une nouvel utilisateur</h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/admin/utilisateurs" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Revenir aux utilisateurs
            </a>
        </div>
        
    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/admin/utilisateurs/new.php" method="POST">
                <div class="form-group">
                    <label for="titre">Nom de l'utilisateur</label>
                    <input type="text" class="form-control" name="nom_utilisateur" />
                </div>
                
                <div class="form-group">
                    <label for="titre">Prénom de l'utilisateur</label>
                    <input type="text" class="form-control" name="prenom_utilisateur" />
                </div>

                <div class="form-group">
                    <label for="titre">Email de l'utilisateur</label>
                    <input type="email" class="form-control" name="email_utilisateur" />
                </div>

                <div class="form-group mb-4">
                    <label for="titre">Mot de passe</label>
                    <input type="password" class="form-control" name="mdp_utilisateur" />
                </div>

                <input type="submit" name="submit" value="Enregistrer" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php include '../includes/inc-bottom.php' ?>