<?php

require '../includes/inc-session-check.php';
require '../includes/inc-check-role-super-admin.php';
require '../../includes/inc-db-connect.php';


if(empty($_POST['id']))
{
    header("Location: /admin/utilisateurs"); die;
}

$sql = "DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
$query = $dbh->prepare($sql);
$res = $query->execute([
    'id_utilisateur' => $_POST['id']
]);

header("Location: /admin/utilisateurs"); die;
