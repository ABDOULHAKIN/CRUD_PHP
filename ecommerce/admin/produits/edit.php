<?php

require $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/admin/functions.php';

// Vérification du paramètre
if(empty($_GET['id']))
{
    header("Location: /admin/produits"); die;
}

// Traiter le formulaire si envoyé
if(!empty($_POST['submit']))
{

    if(updateProduit($_GET['id']))
    {
        echo "Produit modifié";
    }
    else
    {
        echo "Un erreur est survenue...";
    }

}

// Récupération des informations de l'article à modifier
$article = getProduitById($_GET['id']);

// On vérifie si l'article est bien présent en BDD
if(!$article)
{
    header("Location: /admin/produits"); die;
}

$categories = getCategories();

require '../includes/inc-top.php';
?>

<div class="container py-5">
    <h1>Ajouter un nouvel produit</h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/admin/produits" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Revenir aux produits
            </a>
        </div>
        
    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/admin/produits/edit.php?id=<?= $produit['id_produit'] ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titre">Nom du produit</label>
                    <input type="text" class="form-control" name="nom_produit" value="<?= $produit['nom_produit'] ?>"/>
                </div>
                <div class="form-group">
                    <label for="titre">Prix hors taxe du produit</label>
                    <input type="text" class="form-control" name="prix_produit" value="<?= $produit['prix_produit'] ?>"/>
                </div>
                <div class="form-group">
                    <label for="contenu">Description produit</label>
                    <textarea class="form-control" name="contenu"><?= $produit['desc_produit'] ?></textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label for="produits">Produits</label>
                    <select name="produits" class="form-control">
                        <option>Aucune</option>
                        <?php foreach($produits as $produit): ?>
                        <option value="<?= $produit['id_produit'] ?>" 
                        <?php if($produit['id_produit'] == $sous_produit['id_produit']): echo "selected"; endif; ?>>
                            <?= $produit['nom_produit'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="">Image de l'article</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <input type="submit" name="submit" value="Enregistrer" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php include '../includes/inc-bottom.php' ?>