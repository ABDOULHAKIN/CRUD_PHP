<?php
session_start(); // Permet d'utilisateur les superglobaux
require $_SERVER['DOCUMENT_ROOT'].'/inc-db-connect.php';
// Recuperer qui nous a ete envoyé dans l'URL
// Verifiez que cet ID existe
// Et s'il exite affiche la page
// S'il n'existe pas dans la BDD, dans ce cas
// On redirige la personne vers la page d'accueil

// L'ID exite ---------et--n'est pas vide-------
if(isset ($_GET['id']) && !empty($_GET['id']))
{
    // 1. On nettoie l'ID envoyé
    $id = strip_tags($_GET['id']);
    // 2. requete pour afficher le produit 
    $sql = 'SELECT *
            FROM liste
            WHERE id=:id';
    // on prépare la requete 
    $query = $db->prepare($sql);
    // On accroche les parametres de l'ID
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    // on execute la requete 
    $query->execute();
    //on récupere le produit 
    $produit = $query->fetch();

    // 3. On verifie si le produit existe
    if(!$produit)
    {
        $_SESSION['erreur']="Cet ID n'existe pas"; 
        header("Location: /index.php");
    }
}
else
{
    $_SESSION['erreur']="URL invalide"; 
    // Si jamais l'ID est vide(si on efface après ?id=) et ou que l'ID n'existe pas, on redirige vers la page index.php
    header("Location: /index.php");
}

require_once('close.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <h1>Détails du produit : <strong><em><?= $produit['produit']?></em></strong></h1>
                <p><a href="index.php">Retour</a></p>
                <p>ID : <?= $produit['id']?></p>
                <p>Produit : <?= $produit['produit']?></p>
                <p>Prix : <?= $produit['prix']?></p>
                <p>Nombre : <?= $produit['nombre']?></p>
                <p><a href="edit.php?id=<?$produit['id']?>">Modifier</a></p>
            </section>
        </div>
    </main>
</body>
</html>