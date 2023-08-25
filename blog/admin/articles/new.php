<?php

require '../includes/inc-session-check.php';
require '../../includes/inc-db-connect.php';
require '../functions.php';

// Traiter le formulaire si envoyé
if(!empty($_POST['submit']))
{

    $urlImageArticle = uploadImageFile('image');

    $sql = "INSERT INTO article (titre_article, contenu_article, date_article, url_img_article, id_utilisateur, id_categorie) VALUES (:titre_article,:contenu_article, NOW(), :url_img_article, :id_utilisateur, :id_categorie)";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'titre_article' => $_POST['titre'],
        'contenu_article' => $_POST['contenu'],
        'url_img_article' => isset($urlImageArticle) ? $urlImageArticle : NULL,
        'id_utilisateur' => $_SESSION['user']['id'],
        'id_categorie' => $_POST['categorie']
    ]);

    if($res)
    {
        header("Location: /admin/articles"); exit;
    }
    else
    {
        echo "Un erreur est survenue...";
    }

}

$categories = getCategories();

require '../includes/inc-top.php';
?>
<div class="container py-5">
    <h1>Rédiger un nouvel article</h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/admin/articles" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Revenir aux articles
            </a>
        </div>
        
    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/admin/articles/new.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titre">Titre de l'article</label>
                    <input type="text" class="form-control" name="titre" />
                </div>
                <div class="form-group">
                    <label for="contenu">Contenu de l'article</label>
                    <textarea class="form-control" name="contenu"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="categorie">Catégorie</label>
                    <select name="categorie" class="form-control">
                        <?php foreach($categories as $categorie): ?>
                        <option value="<?= $categorie['id_categorie'] ?>">
                            <?= $categorie['nom_categorie'] ?>
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