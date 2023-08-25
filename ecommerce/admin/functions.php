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

    if(!empty($_FILES[$inputFileName]) && $_FILES[$inputFileName]['error'] == 0)
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

function getCategories()
{

    require '../../includes/inc-db-connect.php';

    $sql = "SELECT * FROM categorie ORDER BY nom_categorie ASC";
    $query = $dbh->query($sql);
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);

    return $categories;
}