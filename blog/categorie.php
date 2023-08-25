<?php

require 'includes/inc-db-connect.php';

if (empty($_GET['id'])) {
    header('Location: /');
    die;
}

$sql = "SELECT * 
FROM article 
JOIN categorie ON article.id_categorie = categorie.id_categorie 
WHERE article.id_categorie = " . $_GET['id'];

$result = $dbh->query($sql);

$articles = $result->fetchAll(PDO::FETCH_ASSOC);

require 'includes/inc-top.php';

?>
<div class="container py-4">

    <div class="row">
        <div class="col-8">
            <?php foreach ($articles as $article) : ?>
                <article>
                    <div class="row mb-4">
                        <div class="col-3">
                            <img src='https://dummyimage.com/200x200.png' alt='' class="w-100" />
                        </div>

                        <div class="col-9">
                            <h2><?= $article['titre_article'] ?></h2>
                            <small><a href="/categorie.php?id=<?= $article['id_categorie'] ?>"><?= $article['nom_categorie'] ?></a></small>
                            <p><?= substr($article['contenu_article'], 0, 100) ?></p>
                            <a href="/article.php?id=<?= $article['id_article'] ?>">Lire plus</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require 'includes/inc-bottom.php'; ?>