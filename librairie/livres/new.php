<?php

require $_SERVER['DOCUMENT_ROOT'] . "/managers/book-manager.php";
require $_SERVER['DOCUMENT_ROOT'] . "/managers/genre-manager.php";
require $_SERVER['DOCUMENT_ROOT'] . "/managers/functions.php";
// On vérifie si le formulaire est envoyé
if (isset($_POST['submit'])) 
{
    // On vérifie les champs

    // On fait la verification de champs pour nous dire les champs qui sont vide 
    $errors = checkFormData($_POST['livre'],['titre_livre','auteur_livre']); 

        $id = insertBook($_POST['livre'], isset ($_POST['genres']) ? $_POST['genres'] : []);

    if ($id) {
        header("Location: /livres");
        exit;
    }
}

$genres = getAllGenres();

?>
<form action="/livres/new.php" method="post">
    <div>
        <label>Titre</label>
        <input type="text" name="livre[titre_livre]" >
        <?php if(isset($errors['titre_livre'])): ?>
            <p><small><?= $errors['titre_livre'] ?></small></p>
            <?php endif; ?>
    </div>
    <div>
        <label>Auteur.e</label>
        <input type="text" name="livre[auteur_livre]" >
        <?php if(isset($errors['auteur_livre'])): ?>
            <p><small><?= $errors['auteur_livre'] ?></small></p>
            <?php endif; ?>
    </div>
    <div>
        <label>Editeur.e</label>
        <input type="text" name="livre[editeur_livre]">
        <?php if(isset($errors['editeur_livre'])): ?>
            <p><small><?= $errors['editeur_livre'] ?></small></p>
            <?php endif; ?>
    </div>
    <div>
        <?php foreach ($genres as $genre) : ?>
            <p>
                <label>
                    <input type="checkbox" name="genres[]" value="<?= $genre["id_genre"] ?>">
                    <?= $genre['nom_genre'] ?>
                </label>
            </p>
        <?php endforeach; ?>
    </div>

    <input type="submit" name="submit">
</form>