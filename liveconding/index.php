<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/inc-db-connect.php';
// Lire et afficher d'une base des données 

$sql = "SELECT*
        FROM liste";
// Requete préparer
$query = $db->prepare($sql);
// On execute la requete
$query->execute();
// On stocke le résulat dans un tableau associatif
// fetchAll, pour lui dire de va chercher tous les résultats
$result = $query->fetchAll(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC : Une constante de PDO qui permet de lire les informations des titres des differentes colonnes

require $_SERVER['DOCUMENT_ROOT'] . '/close.php';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <!-- on affiche le message d'erreur ici s'il y'a une erreur-->
               <?php
               if(!empty($_SESSION['erreur']))
               {
                echo '<div class="alert alert-danger" role="alert">' .$_SESSION['erreur'].'</div>';
                // Une fois que le message d'erreur s'affiche, on a plus besoin que l'erreur soit lisible lors des raffraichissement
               $_SESSION['erreur']="";
               }
               ?>
               <?php
               if(!empty($_SESSION['message']))
               {
                echo '<div class="alert alert-success" role="alert">' .$_SESSION['erreur'].'</div>';
                // Une fois que le message d'erreur s'affiche, on a plus besoin que l'erreur soit lisible lors des raffraichissement
               $_SESSION['message']="";
               }
               ?>
                <h1>Liste des produits</h1>
                <table class="table">
                    <thead>
                        <th>ID</th>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Nombre</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        // $result = le tableau dans la BDD
                        // $roduit = les lignes de BDD
                        foreach ($result as $produit) {
                        ?>

                            <tr>
                                <td><?= $produit['id'] ?></td>
                                <td><?= $produit['produit'] ?></td>
                                <td><?= $produit['prix'] ?></td>
                                <td><?= $produit['nombre'] ?></td>
                                <td><a href="details.php?id=<?= $produit['id'] ?>">Voir</a></td>
                                <td><a href="edit.php?id=<?= $produit['id'] ?>" class="btn btn-primary">Modifier</a></td>
                            </tr>

                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add.php" class="btn btn-primary">Ajouter un produit</a>
            </section>
        </div>
    </main>
</body>

</html>