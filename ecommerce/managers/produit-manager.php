<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';

/**
 * Permet de récupérer tous les produits avec le système de pagination
 * @return array
 */
function getAllProductsWithPagination()
{
    // On récupère la connexion à la base de données
    $dbh = $GLOBALS['dbh'];

    // On récupère le nombre de produits
    $countPerPage = 10;
    $sql = "SELECT COUNT(*) as count FROM produit";
    $result = $dbh->query($sql);
    $count = $result->fetch()["count"];

    // On calcule le nombre de pages
    $pagesCount = ceil($count / $countPerPage);

    // On récupère la page courante
    if (!empty($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    // On vérifie que la page courante est bien comprise entre 1 et le nombre de pages
    if ($page == 1) {
        $start = 0;
    } else {
        $start = ($countPerPage * $page) - $countPerPage;
    }

    // On récupère les produits
    $sql = "SELECT * 
    FROM produit 
    LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie
    LEFT JOIN image on image.id_produit = produit.id_produit 
    GROUP BY produit.id_produit
    ORDER BY produit.id_produit DESC
    LIMIT " . $start . "," . $countPerPage;
    $result = $dbh->query($sql);
    
    // On retourne les données
    return [
        'produits' => $result->fetchAll(PDO::FETCH_ASSOC),
        'pages_count' => $pagesCount,
        'current_page' => $page
    ];
}

/**
 * Fonction qui permet d'ajouter un produit en BDD
 * @param array $data
 * @return int
 */
function addNewProduct(array $data)
{
    // On récupère la connexion à la base de données
    $dbh = $GLOBALS['dbh'];

    // On remplace la virgule par un point dans le prix_ht envoyé
    $data['prix_ht_produit'] = str_replace(',','.', $data['prix_ht_produit']);

    // On se charge déjà d'insérer le produit dans la base de données
    $sql = "INSERT INTO produit (nom_produit, desc_produit, prix_ht_produit, id_categorie) VALUES (:nom_produit, :desc_produit, :prix_ht_produit, :id_categorie)";
    $query = $dbh->prepare($sql);
    $res = $query->execute($data);

    // On recupère l'id du produit
    $idProduit = $dbh->lastInsertId();

    if($idProduit){
        // On se charge de l'upload des images
        $imageInputs = ['image_1','image_2','image_3','image_4','image_5'];
        $images = [];
        foreach($imageInputs as $image)
        {
            $images[] = uploadImageFile($image);
        }

        // On insère les images dans la base de données
        foreach($images as $urlImage)
        {
            if($urlImage)
            {
                $sql = "INSERT INTO image (url_image, id_produit) VALUES (:url_image, :id_produit)";
                $query = $dbh->prepare($sql);
                $res = $query->execute([
                    'url_image' => $urlImage,
                    'id_produit' => $idProduit,
                ]);
            }
        }
    }

    return $idProduit;
}

function getAllProducts()
{
    $dbh = $GLOBALS['dbh'];

    $sql = "SELECT produit.id_produit, produit.nom_produit, produit.prix_ht_produit, produit.desc_produit, image.url_image 
    FROM produit 
    LEFT JOIN image on image.id_produit = produit.id_produit 
    GROUP BY produit.id_produit
    ORDER BY produit.id_produit DESC;
    ";
    $query = $dbh->query($sql);
    return $query->fetchAll();
}

function getProductWithImage(int $id)
{
    $dbh = $GLOBALS['dbh'];

    $sql = "SELECT produit.id_produit, produit.nom_produit, produit.prix_ht_produit, produit.desc_produit, image.url_image 
    FROM produit 
    LEFT JOIN image on image.id_produit = produit.id_produit 
    WHERE produit.id_produit = :id_produit
    GROUP BY produit.id_produit
    ORDER BY produit.id_produit DESC;
    ";
    $query = $dbh->prepare($sql);
    $query->execute([
        'id_produit' => $id
    ]);
    return $query->fetch();
}