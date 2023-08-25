<?php
require '../includes/inc-session-check.php';
require '../../includes/inc-db-connect.php';

// Récupération de tous les articles
$sql = "SELECT * FROM categorie ORDER BY nom_categorie ASC";
$query = $dbh->query($sql);
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

require '../includes/inc-top.php';
?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1>Gestion des categories</h1>
        </div>
        <div class="col-auto">
            <a href="/admin/categories/new.php" class="btn btn-primary">
                <i class="bi bi-plus"></i> Créer une catégorie
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nom</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($categories as $categorie): ?>
                    <tr>
                        <td><?= $categorie['id_categorie'] ?></td>
                        <td><?= $categorie['nom_categorie'] ?></td>
                        <td>
                            <a href="/admin/categories/edit.php?id=<?= $categorie['id_categorie'] ?>" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                        </td>
                        <td>

                            <form 
                            action="/admin/categories/delete.php" 
                            method="POST"
                            onsubmit="return confirm('Voulez-vous vraiment supprimer cet categorie ?');"
                            >
                                <input type="hidden" name="id" value="<?= $categorie['id_categorie'] ?>">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<?php include '../includes/inc-bottom.php' ?>