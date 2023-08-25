<?php


session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/managers/module_function.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';



$nbArticleParPage = 3;
if (!empty($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}

if(isset($_POST['verified']) && isset($_POST['id_modulee']) && isset($_POST['id_type_donnee'])) {
    $verified = updateNotif($_POST['id_modulee'], $_POST['id_type_donnee']);
}
// Récupérer tous les identifiants de module
$moduleIDs = getAllModuleIDs();
$notifications = array();
foreach ($moduleIDs as $id_modulee) {
    $moduleNotifications = getAllNotification($id_modulee);
    $notifications = array_merge($notifications, $moduleNotifications);
}

?>

<h1 class="titre">Les notifications</h1>
<div class="erreur erreur-centre">
    <?php if (isset($_SESSION['erreur'])) : ?>
        <div class="erreur-message">
            <?php echo $_SESSION['erreur']; ?>
        </div>
        <?php unset($_SESSION['erreur']); ?>
    <?php endif; ?>
</div>
<div class="container">
    <div class="revenir">
        <div class="buttonretour">
            <a href="/iwatch/index.php?page=<?= ($currentPage - 1); ?>" class="">
                <i class='bx bx-arrow-back'></i>
            </a>

        </div>
        <div class="tableau">
            <table class="tableau-style">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Type de donnée</th>
                        <th>Valeur actuelle</th>
                        <th>Valeur voulue</th>
                        <th>Message</th>
                        <th>Cheked</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($notifications as $notification) : ?>
                        <tr>
                            <td><?= $notification['nom_module'] ?></td>
                            <td><?= $notification['libelle_type_donnee'] ?></td>
                            <td><?= $notification['valeur_actuelle'] ?></td>
                            <td><?= $notification['valeur_voulu'] ?></td>
                            <td><?= $notification['message'] ?></td>
                            <td>  
                                <form method="post">
                                    <input name="id_modulee" type="hidden" value="<?= $notification['id_modulee'] ?>"/>
                                    <input name="id_type_donnee" type="hidden" value="<?= $notification['id_type_donnee'] ?>"/>
                                <input type="submit" name="verified"
                                        value="vu"/>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

        </div>

        </tbody>

        </table>
        <tfoot>
            <td>
            <div class="pagination-container">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">

            <?php
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

            // Nombre total de pages
            $totalPages = ceil(count($notifications) / 3);

            // Détermine la première et la dernière page à afficher
            $firstPage = max($currentPage - 2, 1);
            $lastPage = min($currentPage + 2, $totalPages);

            // Affiche les liens vers les pages
            for ($i = $firstPage; $i <= $lastPage; $i++) :
            ?>
                <li class="page-item <?php if ($i === $currentPage) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>

        </ul>
    </nav>
            </td>
        </tfoot>

    </div>
</div>
<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>