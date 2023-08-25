<?php
require $_SERVER['DOCUMENT_ROOT'] . "/managers/book-manager.php";
require $_SERVER['DOCUMENT_ROOT'] . "/managers/genre-manager.php";

// On vérifie si le formulaire est envoyé
if(isset($_POST['submit']))
{
    // On vérifie les champs
    $count = updateBook($_POST['livre'], isset($_POST['genres']) ? $_POST['genres'] : []);

    if($count == 1)
    {
        header("Location: /livres"); exit;
    }
}

// On vérifie le paramètre id dans l'url
if(empty($_GET['id']))
{
    header("Location: /livres"); exit;
}

$book = getBookById($_GET['id']);
$booksGenres = getBooksGenres($_GET['id']);

if(!$book) // $book == null
{
    header("Location: /livres"); exit;
} 

$genres = getAllGenres();


?>
<form action="/livres/edit.php?id=<?= $_GET['id'] ?>" method="post">
    <input type="hidden" name="livre[id_livre]" value="<?= $book['id_livre'] ?>">

    <label>Titre</label>
    <input type="text" name="livre[titre_livre]" value="<?= $book['titre_livre'] ?>">

    <label>Auteur.e</label>
    <input type="text" name="livre[auteur_livre]" value="<?= $book['auteur_livre'] ?>">

    <label>Editeur.e</label>
    <input type="text" name="livre[editeur_livre]" value="<?= $book['editeur_livre'] ?>">

    <div>
    <?php foreach($genres as $genre): ?>
    <p>
        <label>
            <input type="checkbox" name="genres[]" value="<?= $genre["id_genre"] ?>"
            <?= in_array($genre, $booksGenres) ? "checked" : "" ?>>
            <?= $genre['nom_genre'] ?>
        </label>
    </p>
    <?php endforeach; ?>
    </div>

    <input type="submit" name="submit">
</form>