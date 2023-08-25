<?php


/**
 * Permet d'uploader une image
 *
 * @param string $inputFileName
 * @return void
 */
function uploadImageFile(string $inputFileName)
{
    $urlImage = NULL;

    if(isset($_FILES[$inputFileName]) && $_FILES[$inputFileName]['error'] == 0)
    {
        // On vérifie le poid de l'image
        if($_FILES[$inputFileName]['size'] < 1000000)
        {

            // On vérifie que le fichier est bien une image
            $authorizedTypes = ["image/png","image/jpg","image/gif","image/jpeg"];
            $fileType = mime_content_type($_FILES[$inputFileName]['tmp_name']);

            if(in_array($fileType, $authorizedTypes))
            {

                $fileName = str_replace(' ','-', basename($_FILES[$inputFileName]['name']));
                $tmpFile = $_FILES[$inputFileName]['tmp_name'];

                if(move_uploaded_file($tmpFile, "../../uploads/" . $fileName))
                {
                    $urlImage = "/uploads/" . $fileName;
                }
                // else
                // {
                //     echo "KO"; die;
                // }

            }
            // else
            // {
            //     echo "Fichier non autorisé !"; die;
            // }

        }
    }

    return $urlImage;
}

function getArticleById(int $id)
{
    require '../../includes/inc-db-connect.php';

    $sql = "SELECT * FROM article WHERE id_article = :id_article";
    $query = $dbh->prepare($sql);
    $res = $query->execute(['id_article' => $id]);
    $article = $query->fetch(PDO::FETCH_ASSOC);

    return $article;
}

function updateArticle(int $id)
{
    require '../../includes/inc-db-connect.php';

    $urlImageArticle = uploadImageFile('image');
    if($urlImageArticle)
    {
        $sql = "UPDATE article SET titre_article = :titre_article, contenu_article = :contenu_article, url_img_article = :url_img_article,  id_categorie = :id_categorie WHERE id_article = :id_article";
        $query = $dbh->prepare($sql);
        $res = $query->execute([
            'titre_article' => $_POST['titre'],
            'contenu_article' => $_POST['contenu'],
            'url_img_article' => isset($urlImageArticle) ? $urlImageArticle : NULL,
            'id_categorie' => $_POST['categorie'],
            'id_article' => $id
        ]);
    
    }
    else
    {
        $sql = "UPDATE article SET titre_article = :titre_article, contenu_article = :contenu_article, id_categorie = :id_categorie WHERE id_article = :id_article";
        $query = $dbh->prepare($sql);
        $res = $query->execute([
            'titre_article' => $_POST['titre'],
            'contenu_article' => $_POST['contenu'],
            'id_categorie' => $_POST['categorie'],
            'id_article' => $id
        ]);
    
    }

    return $res;
}

function getCategories()
{

    require '../../includes/inc-db-connect.php';

    $sql = "SELECT * FROM categorie ORDER BY nom_categorie ASC";
    $query = $dbh->query($sql);
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);

    return $categories;
}