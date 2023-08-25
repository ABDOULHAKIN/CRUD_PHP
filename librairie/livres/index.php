<?php

require $_SERVER['DOCUMENT_ROOT'] . '/managers/book-manager.php';

$books = getAllBooks();

?>
<a href="/livres/new.php">Ajouter un livre</a>
<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Titre</th>
            <th>Auteur.e</th>
            <th>Editeur.e</th>
            <th>Genres</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($books as $book): ?>
        <tr>
            <td><?= $book['id_livre'] ?></td>
            <td><?= $book['titre_livre'] ?></td>
            <td><?= $book['auteur_livre'] ?></td>
            <td><?= $book['editeur_livre'] ?></td>
            <td><?= $book['genres'] ?></td>
            <td><a href="/livres/edit.php?id=<?= $book['id_livre'] ?>">Modifier</a></td>
            <td>
                <form action="/livres/delete.php" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer ce livre ?')">
                    <input type="hidden" name="id_livre" value="<?= $book['id_livre'] ?>">
                    <input type="submit" value="Supprimer" >
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
