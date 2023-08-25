<?php

require $_SERVER['DOCUMENT_ROOT'] . '/managers/track-manager.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
// La page affichage de tous les articles present dans la base des données

$musics = getAllTrack();

?>
<div class="container  py-5">
    <div class="row mb-4">
        <div class="col">
            <h1>Gestion des musiques</h1>
        </div>
        <div class="col-auto">
            <a href="/track/new.php" class="btn btn-primary">
                <i class="bi bi-plus"></i>Insérer une musique
            </a>
        </div>

    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id musique</th>
                        <th>Titre de la musique</th>
                        <th>Nom de l'artiste</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($musics as $music) : ?>
                        <tr>

                            <td><?= $music['id_track'] ?></td>
                            <td> <?= $music['titre_track'] ?></td>
                            <td><?=$music['nom']?></td>

                            <td>
                                <a href="/track/edit.php?id=<?= $music['id_track'] ?>" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i>Modifier
                                </a>
                            </td>

                            <td>
                                <form action="/track/delete.php" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer la musique dont le titre est : <?= $music['titre_track'] ?> ?')">

                                    <input type="hidden" name="id_track" value="<?= $music['id_track'] ?>">
                                    <input type="submit" value="Supprimer" class="btn btn-danger">
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>