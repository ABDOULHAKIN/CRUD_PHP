<?php

require '../includes/inc-session-check.php';
require '../../includes/inc-db-connect.php';

// Traiter le formulaire si envoyé
if(!empty($_POST['submit']))
{

    $sql = "INSERT INTO categorie (nom_categorie) VALUES (:nom_categorie)";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'nom_categorie' => $_POST['nom_categorie']
    ]);

    if($res)
    {
        header("Location: /admin/categories"); exit;
    }
    else
    {
        echo "Un erreur est survenue...";
    }

}

require '../includes/inc-top.php';
?>
<div class="container py-5">
    <h1>Création d'une nouvelle catégorie</h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/admin/categories" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Revenir aux catégories
            </a>
        </div>
        
    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/admin/categories/new.php" method="POST">
                <div class="form-group">
                    <label for="titre">Nom de la catégorie</label>
                    <input type="text" class="form-control" name="nom_categorie" />
                </div>

                <input type="submit" name="submit" value="Enregistrer" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php include '../includes/inc-bottom.php' ?>