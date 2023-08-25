<?php

require 'includes/inc-db-connect.php';

if(empty($_GET['id']))
{
    header('Location: /'); die;
}

$sql = "SELECT * FROM article WHERE id_article = " . $_GET['id'];
$result = $dbh->query($sql);

$article = $result->fetch(PDO::FETCH_ASSOC);

if(!$article) // $article == false
{
    header('Location: 404.php');die;
} 

$sql = "SELECT * FROM avis WHERE id_article = " . $_GET['id'];
$result = $dbh->query($sql);

$avis = $result->fetchAll(PDO::FETCH_ASSOC);

require 'includes/inc-top.php'

?>

    <article>
        <h1><?= $article['titre_article'] ?></h1>
        <p><?= $article['contenu_article'] ?></p>
        <ul>
            <?php foreach($avis as $a): ?>
                <li>
                    <strong><?= $a['auteur_avis'] ?></strong>
                    <p><?= $a['contenu_avis'] ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </article>

<?php require 'includes/inc-bottom.php'; ?>
