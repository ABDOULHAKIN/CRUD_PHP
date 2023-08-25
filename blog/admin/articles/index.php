<?php
require '../includes/inc-session-check.php';
require '../../includes/inc-db-connect.php';

// Récupération de tous les articles
$sql = "SELECT * FROM article ORDER BY date_article DESC";
$query = $dbh->query($sql);
$articles = $query->fetchAll(PDO::FETCH_ASSOC);

require '../includes/inc-top.php';
?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1>Gestion des articles</h1>
        </div>
        <div class="col-auto">
            <a href="/admin/articles/new.php" class="btn btn-primary">
                <i class="bi bi-plus"></i> Rédiger un article
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Titre</th>
                        <th>Date</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($articles as $article): ?>
                    <tr>
                        <td><?= $article['id_article'] ?></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="<?= $article['id_article'] ?>">
                                <?= $article['titre_article'] ?>
                            </a>
                        </td>
                        <td><?= $article['date_article'] ?></td>
                        <td>
                            <a href="/admin/articles/edit.php?id=<?= $article['id_article'] ?>" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                        </td>
                        <td>

                            <form 
                            action="/admin/articles/delete.php" 
                            method="POST"
                            onsubmit="return confirm('Voulez-vous vraiment supprimer cet article ?');"
                            >
                                <input type="hidden" name="id" value="<?= $article['id_article'] ?>">
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


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalContent">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>

<script>
    const myModalEl = document.getElementById('staticBackdrop')
    myModalEl.addEventListener('show.bs.modal', event => {
        const target = event.relatedTarget;
        const id = target.dataset.id;

        // On recuoérer les infos de l'article
        fetch("/admin/articles/get-ajax.php?id=" + id)
        .then(response => response.json())
        .then(article => {
            
            const modalTitle = document.querySelector('#staticBackdropLabel');
            modalTitle.textContent = article.titre_article;

            const modalContent = document.querySelector('#modalContent');
            modalContent.textContent = article.contenu_article;

        })
    })

</script>

<?php include '../includes/inc-bottom.php' ?>