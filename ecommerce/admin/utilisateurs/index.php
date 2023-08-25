<?php
require '../includes/inc-session-check.php';
require '../includes/inc-check-role-super-admin.php';
require '../../includes/inc-db-connect.php';

// Récupération de tous les articles
$sql = "SELECT * FROM utilisateur ORDER BY id_utilisateur ASC";
$query = $dbh->query($sql);
$utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);

require '../includes/inc-top.php';
?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1>Gestion des utilisateurs</h1>
        </div>
        <div class="col-auto">
            <a href="/admin/utilisateurs/new.php" class="btn btn-primary">
                <i class="bi bi-plus"></i> Nouvel utilisateur
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
                        <th>Prénom</th>
                        <th>Email</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td><?= $utilisateur['id_utilisateur'] ?></td>
                        <td><?= $utilisateur['nom_utilisateur'] ?></td>
                        <td><?= $utilisateur['prenom_utilisateur'] ?></td>
                        <td><?= $utilisateur['email_utilisateur'] ?></td>
                        <td>
                            <a href="/admin/utilisateurs/edit.php?id=<?= $utilisateur['id_utilisateur'] ?>" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                        </td>
                        <td>

                            <form 
                            action="/admin/utilisateurs/delete.php" 
                            method="POST"
                            onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');"
                            >
                                <input type="hidden" name="id" value="<?= $utilisateur['id_utilisateur'] ?>">
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