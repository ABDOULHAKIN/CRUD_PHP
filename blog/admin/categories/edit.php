<?php

require '../includes/inc-session-check.php';
require '../../includes/inc-db-connect.php';

// Vérification du paramètre
if(empty($_GET['id']))
{
    header("Location: /admin/categories"); die;
}

// Traiter le formulaire si envoyé
if(!empty($_POST['submit']))
{

    $sql = "UPDATE categorie SET nom_categorie = :nom_categorie WHERE id_categorie = :id_categorie";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'nom_categorie' => $_POST['nom_categorie'],
        'id_categorie' => $_GET['id']
    ]);

    if($res)
    {
        echo "Catégorie modifiée avec succès !";
    }
    else
    {
        echo "Un erreur est survenue...";
    }

}

// Récupération des informations de la categorie à modifier
$sql = "SELECT * FROM categorie WHERE id_categorie = :id_categorie";
$query = $dbh->prepare($sql);
$res = $query->execute(['id_categorie' => $_GET['id']]);
$categorie = $query->fetch(PDO::FETCH_ASSOC);

// On vérifie si l'article est bien présent en BDD
if(!$categorie)
{
    header("Location: /admin/categories"); die;
}

require '../includes/inc-top.php';
?>
<div class="container py-5">
    <h1>Rédiger un nouvel article</h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/admin/categories" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Revenir aux categories
            </a>
        </div>
        
    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/admin/categories/edit.php?id=<?= $categorie['id_categorie'] ?>" method="POST">
                <div class="form-group">
                    <label for="titre">Nom de la catégorie</label>
                    <input type="text" class="form-control" name="nom_categorie" value="<?= $categorie['nom_categorie'] ?>"/>
                </div>

                <input type="submit" name="submit" value="Enregistrer" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php include '../includes/inc-bottom.php' ?>