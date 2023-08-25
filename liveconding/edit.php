<?php
session_start(); // Permet d'utilisateur les superglobaux
require $_SERVER['DOCUMENT_ROOT'] . '/inc-db-connect.php';
// L'ajout d'un produit 
// 1. Est qu"on a posté un produit 
if ($_POST) {
    // Verifie chacun des champs soit defini et pas vide
    if (isset($_POST['id']) && !empty($_POST['id'])
        && isset($_POST['produit']) && !empty($_POST['produit'])
        && isset($_POST['prix']) && !empty($_POST['prix'])
        && isset($_POST['nombre']) && !empty($_POST['nombre'])
    ) {

        // 2. Si le formulaire est postée, on va proteger les differents champs de formulaire
        // 2.1. On nettoie les données
        $id = strip_tags(($_GET['id']));
        $produit = strip_tags(($_POST['produit']));
        $prix = strip_tags(($_POST['prix']));
        $nombre = strip_tags(($_POST['nombre']));

        // 3. La requete d'ajout 
        // 3.1 On modifie les valeurs des produits, là ou l'ID est cohérent, l'ID qu'on aura ID
        $sql = 'UPDATE liste SET produit=:produit, prix=:prix, nombre=:nombre
                WHERE id=:id';
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':produit', $produit, PDO::PARAM_STR);
        $query->bindValue(':prix', $prix, PDO::PARAM_STR);
        $query->bindValue(':nombre', $nombre, PDO::PARAM_INT);
        $query->execute();

        // 4. message de confirmation

        $_SESSION['message'] = "Produit modifié";
        
        header('Location: index.php');
    } else
    // Si les deux traitements ne sont pas respectées
    // Alors, on redirige vers la page de l'index.php
    {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}
// Pour modifier est ce que j'ai un ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
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
    $result = $query->fetch();

    // 3. On verifie si le produit existe
    if (!$result) {
        $_SESSION['erreur'] = "Cet ID n'existe pas";
        header("Location: /index.php");
    }
}

require_once('close.php');

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit</title>
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
                        <!-- On met un value pour initialisé les valeurs-->
                        <input type="text" id="produit" name="produit" value="<?= $result['produit'] ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="number" id="prix" name="prix" value="<?= $result['prix'] ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="number" id="nombre" name="nombre" value="<?= $result['nombre'] ?>" class="form-control">
                    </div>
                    <!--Pour un input hidden, parce que il faut qu'on envoie l'id dans le POST pour pourvoir faire notre requete-->
                    <input type="hidden" value="<?= $result['id'] ?>" name="id">
                    <button class="btn btn-primary">Enregistrer</button>
                </form>
            </section>
        </div>
    </main>
</body>

</html>