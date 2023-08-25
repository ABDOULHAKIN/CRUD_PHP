<?php

require '../includes/inc-session-check.php';
require '../../includes/inc-db-connect.php';


if(empty($_POST['id']))
{
    header("Location: /admin/articles"); die;
}

$sql = "DELETE FROM article WHERE id_article = :id_article";
$query = $dbh->prepare($sql);
$res = $query->execute([
    'id_article' => $_POST['id']
]);

header("Location: /admin/articles"); die;
