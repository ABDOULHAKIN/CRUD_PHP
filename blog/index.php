<?php

require 'includes/inc-db-connect.php';

$nbArticleParPage = 10;
$sql = "SELECT COUNT(*) FROM article";
$result = $dbh->query($sql);
$nbArticles = $result->fetch()[0];
$nbPages = ceil($nbArticles / $nbArticleParPage);

if (!empty($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if ($page == 1) {
    $start = 0;
} else {
    $start = ($nbArticleParPage * $page) - $nbArticleParPage;
}

$sql = "SELECT * 
FROM article 
LEFT JOIN categorie ON article.id_categorie = categorie.id_categorie
ORDER BY date_article DESC
LIMIT " . $start . "," . $nbArticleParPage;
$result = $dbh->query($sql);
$articles = $result->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM categorie";
$result = $dbh->query($sql);
$categories = $result->fetchAll(PDO::FETCH_ASSOC);

require 'includes/inc-top.php'

?>
<div class="container py-4">

    <div class="row">
        <div class="col-8">
            <?php foreach ($articles as $article) : ?>
                <article>
                    <div class="row mb-4">
                       

                        <div class="col-9">
                            <h2><?= $article['titre_article'] ?></h2>
                            <small><a href="/categorie.php?id=<?= $article['id_categorie'] ?>"><?= $article['nom_categorie'] ?></a></small>
                            <p><?= substr($article['contenu_article'], 0, 100) ?></p>
                            <a href="/article.php?id=<?= $article['id_article'] ?>">Lire plus</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

            <div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php if($page != 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/?page=<?= $page - 1 ?>">Précédent</a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $nbPages; $i++) : ?>
                            <?php if(($i > $page - 10) && ($i < $page + 10)): ?>
                            <li class="page-item">
                                <a class="page-link" href="/?page=<?= $i ?>" <?php if ($i == $page) : echo "disabled"; endif; ?>><?= $i ?></a>
                            </li>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if($page != $nbPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/?page=<?= $page + 1 ?>">Suivant</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>

            </div>
        </div>

        <div class="col-4">
            <h2>Catégories</h2>
            <!-- Afficher la liste des catégories du blog -->
            <ul class="nav flex-column">
            <?php foreach($categories as $categorie): ?>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/categorie.php?id=<?= $categorie['id_categorie'] ?>"><?= $categorie['nom_categorie'] ?></a>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>

    </div>

</div>

<?php require 'includes/inc-bottom.php'; ?>