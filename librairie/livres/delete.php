<?php

require $_SERVER['DOCUMENT_ROOT'] . "/managers/book-manager.php";

if(!empty($_POST['id_livre']))
{
    $count = deleteBook($_POST['id_livre']);

    if($count == 1)
    {
        header("Location: /livres"); exit;
    }
    else
    {
        echo "Une erreur s'est produite lors de la suppression...";
    }
}
else
{
    header("Location: /livres"); exit;
}