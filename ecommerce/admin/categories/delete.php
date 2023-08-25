<?php

require '../includes/inc-session-check.php';
require '../../includes/inc-db-connect.php';


if(empty($_POST['id']))
{
    header("Location: /admin/categories"); die;
}

// On vérifie si des articles sont liés à cette catégorie
$sql = "SELECT id_article FROM article WHERE id_categorie = :id_categorie";
$query = $dbh->prepare($sql);
$res = $query->execute([
    'id_categorie' => $_POST['id']
]);

$articles = $query->fetchAll(PDO::FETCH_COLUMN);

if(count($articles) > 0)
{
    // On supprime le lien entre les articles et la catégorie

    // Méthode facile
    // $sql = "UPDATE article SET id_categorie = NULL WHERE id_article = :id_article";
    // foreach($articles as $article)
    // {
    //     $query = $dbh->prepare($sql);
    //     $res = $query->execute([
    //         'id_article' => $article['id_article'];
    //     ]);
    // }
    // À éviter autant que possible car l'exécution de requêtes dans une boucle risque d'impacter sérieusement le temps d'exécution du programme

    // Méthode avancée
    // Au lieu d'effectuer plusieurs requêtes, on n'en effectue qu'une seule ici afin d'optimiser les performances
    $ids = implode(",", $articles);
    $sql =  "UPDATE article SET id_categorie = NULL WHERE id_article IN (".$ids.")";
    $query = $dbh->query($sql);

}

$sql = "DELETE FROM categorie WHERE id_categorie = :id_categorie";
$query = $dbh->prepare($sql);
$res = $query->execute([
    'id_categorie' => $_POST['id']
]);

header("Location: /admin/categories"); die;
