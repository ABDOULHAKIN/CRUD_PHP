<?php

require '../includes/inc-session-check.php';
require '../functions.php';

// Vérification du paramètre
if(empty($_GET['id']))
{
    header("Location: /admin/articles"); die;
}

// Traiter le formulaire si envoyé
if(!empty($_POST['submit']))
{

    if(updateArticle($_GET['id']))
    {
        echo "Article modifié";
    }
    else
    {
        echo "Un erreur est survenue...";
    }

}

// Récupération des informations de l'article à modifier
$article = getArticleById($_GET['id']);

// On vérifie si l'article est bien présent en BDD
if(!$article)
{
    header("Location: /admin/articles"); die;
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
            <form action="/admin/articles/edit.php?id=<?= $article['id_article'] ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titre">Titre de l'article</label>
                    <input type="text" class="form-control" name="titre" value="<?= $article['titre_article'] ?>"/>
                </div>
                <div class="form-group">
                    <label for="contenu">Contenu de l'article</label>
                    <textarea class="form-control" name="contenu"><?= $article['contenu_article'] ?></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="categorie">Catégorie</label>
                    <select name="categorie" class="form-control">
                        <option>Aucune</option>
                        <?php foreach($categories as $categorie): ?>
                        <option value="<?= $categorie['id_categorie'] ?>" 
                        <?php if($categorie['id_categorie'] == $article['id_categorie']): echo "selected"; endif; ?>>
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