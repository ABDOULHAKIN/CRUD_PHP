<?php
session_start(); // Permet d'utilisateur les superglobaux
require $_SERVER['DOCUMENT_ROOT'] . '/inc-db-connect.php';
// L'ajout d'un produit 

// 1. Est qu"on a posté un produit 
if ($_POST) {
    // Verifie chacun des champs soit defini et pas vide
    if (
        isset($_POST['produit']) && !empty($_POST['produit'])
        && isset($_POST['prix']) && !empty($_POST['prix'])
        && isset($_POST['nombre']) && !empty($_POST['nombre']))
        {

        // 2. Si le formulaire est postée, on va proteger les differents champs de formulaire
        // 2.1. On nettoie les données
        $produit = strip_tags(($_POST['produit']));
        $prix = strip_tags(($_POST['prix']));
        $nombre = strip_tags(($_POST['nombre']));

        // 3. La requete d'ajout 

        $sql = 'INSERT INTO liste (produit, prix, nombre)
                VALUES (:produit, :prix, :nombre)';
        $query=$db->prepare($sql);
        $query->bindValue(':produit', $produit, PDO::PARAM_STR);
        $query->bindValue(':prix', $prix, PDO::PARAM_STR);
        $query->bindValue(':nombre', $nombre, PDO::PARAM_INT);
        $query->execute();

        // 4. message de confirmation

        $_SESSION['message'] = "Produit ajouté";
        require_once('close.php');
        header('Location: index.php');
     
    } else
    // Si les deux traitements ne sont pas respectées
    // Alors, on redirige vers la page de l'index.php
    {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php
                if (!empty($_SESSION['erreur'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['erreur'] . '</div>';
                    // Une fois que le message d'erreur s'affiche, on a plus besoin que l'erreur soit lisible lors des raffraichissement
                    $_SESSION['erreur'] = "";
                }
                ?>
                <h1>Ajouter un produit</h1>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="produit">Produit</label>
                        <input type="text" id="produit" name="produit" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="text" id="prix" name="prix" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="number" id="nombre" name="nombre" class="form-control">
                    </div>
                    <button class="btn btn-primary">Envoyer</button>
                </form>
            </section>
        </div>
    </main>
</body>

</html>