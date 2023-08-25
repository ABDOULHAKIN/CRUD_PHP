<?php

require '../includes/inc-session-check.php';
require '../includes/inc-check-role-super-admin.php';
require '../../includes/inc-db-connect.php';

// Vérification du paramètre
if(empty($_GET['id']))
{
    header("Location: /admin/utilisateurs"); die;
}

// Traiter le formulaire si envoyé
if(!empty($_POST['submit']))
{
    // On met à jour tous les champs de l'utilisateur
    $sql = "UPDATE utilisateur 
    SET nom_utilisateur = :nom_utilisateur, 
    prenom_utilisateur = :prenom_utilisateur, 
    email_utilisateur = :email_utilisateur
    WHERE id_utilisateur = :id_utilisateur";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'nom_utilisateur' => $_POST['nom_utilisateur'],
        'prenom_utilisateur' => $_POST['prenom_utilisateur'],
        'email_utilisateur' => $_POST['email_utilisateur'],
        'id_utilisateur' => $_GET['id'],
    ]);

    // On met à jour les roles de l'utilisateur

    // On recupère tous les ids des rôles de l'utilisateur
    // pour comparer avec les rôles envoyés dans le POST
    $sql = "SELECT role.id_role FROM utilisateur_role 
    JOIN role ON role.id_role = utilisateur_role.id_role 
    WHERE id_utilisateur = :id_utilisateur";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'id_utilisateur' => $_GET['id']
    ]);
    $usersRoles = $query->fetchAll(PDO::FETCH_COLUMN);

    $roles = !empty($_POST['roles']) ? $_POST['roles'] : [];
    foreach($roles as $role)
    {
        if(!in_array($role, $usersRoles)) // On vérifie que l'utilisateur ne possède pas déjà le role
        {
            $sql = "INSERT INTO utilisateur_role (id_utilisateur, id_role) VALUES (:id_utilisateur, :id_role)";
            $query = $dbh->prepare($sql);
            $res = $query->execute([
                'id_utilisateur' => $_GET['id'],
                'id_role' => $role
            ]);
        }
    }

    // On parcours les roles de l'utilisateur 
    // pour supprimer ceux qui sont décochés 
    // (donc non présent dans le $_POST['roles'])
    foreach($usersRoles as $role)
    {
        if(!in_array($role, $roles))
        {
            $sql = "DELETE FROM utilisateur_role WHERE id_role = :id_role AND id_utilisateur = :id_utilisateur";
            $query = $dbh->prepare($sql);
            $res = $query->execute([
                'id_utilisateur' => $_GET['id'],
                'id_role' => $role
            ]);
        }
    }

    if($res)
    {
        echo "Utilisateur modifiée avec succès !";
    }
    else
    {
        echo "Un erreur est survenue...";
    }

}

// Récupération des informations de la utilisateur à modifier
$sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
$query = $dbh->prepare($sql);
$res = $query->execute(['id_utilisateur' => $_GET['id']]);
$utilisateur = $query->fetch(PDO::FETCH_ASSOC);

// On vérifie si l'article est bien présent en BDD
if(!$utilisateur)
{
    header("Location: /admin/utilisateurs"); die;
}

// On recupère les roles de l'utilisateur
$sql = "SELECT role.libelle FROM utilisateur_role 
JOIN role ON role.id_role = utilisateur_role.id_role 
WHERE id_utilisateur = :id_utilisateur";
$query = $dbh->prepare($sql);
$res = $query->execute([
    'id_utilisateur' => $utilisateur['id_utilisateur']
]);
$usersRoles = $query->fetchAll(PDO::FETCH_COLUMN);

// On recupère les rôles disponibles en BDD
$sql = "SELECT * FROM role";
$query = $dbh->query($sql);
$roles = $query->fetchAll(PDO::FETCH_ASSOC);

require '../includes/inc-top.php';
?>
<div class="container py-5">
    <h1>Rédiger un nouvel article</h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/admin/utilisateurs" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Revenir aux utilisateurs
            </a>
        </div>
        
    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/admin/utilisateurs/edit.php?id=<?= $utilisateur['id_utilisateur'] ?>" method="POST">
            <div class="form-group">
                    <label for="titre">Nom de l'utilisateur</label>
                    <input type="text" class="form-control" name="nom_utilisateur" value="<?= $utilisateur['nom_utilisateur'] ?>" />
                </div>
                
                <div class="form-group">
                    <label for="titre">Prénom de l'utilisateur</label>
                    <input type="text" class="form-control" name="prenom_utilisateur" value="<?= $utilisateur['prenom_utilisateur'] ?>"/>
                </div>

                <div class="form-group mb-4">
                    <label for="titre">Email de l'utilisateur</label>
                    <input type="email" class="form-control" name="email_utilisateur" value="<?= $utilisateur['email_utilisateur'] ?>"/>
                </div>

                <div class="form-group mb-4">

                    <?php foreach($roles as $role): ?>
                    <div class="form-check form-switch">
                        <input 
                            class="form-check-input" 
                            name="roles[]"
                            type="checkbox"
                            id="switchAdmin"
                            <?= in_array($role['libelle'], $usersRoles) ? 'checked' : null ?>
                            value="<?= $role['id_role'] ?>"
                        >
                        <label class="form-check-label" for="switchAdmin"><?= $role['libelle'] ?></label>
                    </div>
                    <?php endforeach; ?>

                </div>

                <input type="submit" name="submit" value="Enregistrer" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php include '../includes/inc-bottom.php' ?>