<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/inc-session-check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/managers/produit-manager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/functions.php';

// Traiter le formulaire si envoyé
if(!empty($_POST['submit']))
{

    $res = addNewProduct($_POST['produit']);

    if($res)
    {
        header("Location: /admin/produits"); exit;
    }
    else
    {
        echo "Un erreur est survenue...";
    }

}


$produits = getAllProducts();

require_once '../includes/inc-top.php';
?>
<div class="container py-5">
    <h1>Créer un nouveau produit</h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/admin/produits" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Revenir aux produits
            </a>
        </div>
        
    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/admin/produits/new.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nom_produit">Nom produit</label>
                    <input type="text" class="form-control" name="produit[nom_produit]" />
                </div>
                <div class="form-group">
                    <label for="prix_ht_produit">Prix produit</label>
                    <input type="text" class="form-control" name="produit[prix_ht_produit]" />
                </div>
                <div class="form-group">
                    <label for="desc_produit">Description produit</label>
                    <textarea class="form-control" name="produit[desc_produit]"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="categorie">Catégorie</label>
                    <select name="produit[id_categorie]" class="form-control">
                        <?php foreach($categories as $categorie): ?>
                        <option value="<?= $categorie['id_categorie'] ?>">
                            <?= $categorie['nom_categorie'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

               
                <!-- Je veux pouvoir ajouter 5 images en plus -->
                <?php for($i = 1; $i < 6; $i++): ?>
                    <div class="form-group mb-3">
                        <label for="">Image N°<?= $i ?></label>
                        <input type="file" name="image_<?= $i ?>" class="form-control">
                    </div>
                <?php endfor; ?>
                


                <input type="submit" name="submit" value="Enregistrer" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php include '../includes/inc-bottom.php' ?>