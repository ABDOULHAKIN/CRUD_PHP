<?php

require '../includes/inc-session-check.php';
require '../../includes/inc-db-connect.php';

if(!empty($_GET['id']))
{
    $sql = "SELECT * FROM article WHERE id_article = :id_article";
    $query = $dbh->prepare($sql);
    $res = $query->execute([
        'id_article' => $_GET['id']
    ]);
    $article = $query->fetch(PDO::FETCH_ASSOC);

    header("Content-type: application/json");
    echo json_encode($article);

}