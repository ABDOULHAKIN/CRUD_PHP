<?php
require $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/inc-session-check.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';

// Récupération de tous les produits
$sql = "SELECT produit.id_produit, produit.nom_produit, produit.prix_ht_produit, produit.desc_produit, image.url_image 
FROM produit 
LEFT JOIN image on image.id_produit = produit.id_produit 
GROUP BY produit.id_produit
ORDER BY produit.id_produit DESC;
";
$query = $dbh->query($sql);
$produits = $query->fetchAll(PDO::FETCH_ASSOC);

require '../includes/inc-top.php';
?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1>Gestion des produits</h1>
        </div>
        <div class="col-auto">
            <a href="/admin/produits/new.php" class="btn btn-primary">
                <i class="bi bi-plus"></i> Créer un produit
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
                        <th>Description</th>
                        <th>Prix</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($produits as $produit): ?>
                    <tr>
                        <td><?= $produit['id_produit'] ?></td>
                        <td>
                            <?php if($produit['url_image']): ?>
                            <img src="<?= $produit['url_image'] ?>" style="width: 40px; height: 40px" alt="" />
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="<?= $produit['id_produit'] ?>">
                                <?= $produit['nom_produit'] ?>
                            </a>
                        </td>
                        <td><?= substr($produit['desc_produit'], 200) ?></td>
                        <td><?= $produit['prix_ht_produit'] ?> €</td>
                        <td>
                            <a href="/admin/produits/edit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                        </td>
                        <td>

                            <form 
                            action="/admin/produits/delete.php" 
                            method="POST"
                            onsubmit="return confirm('Voulez-vous vraiment supprimer cet produit ?');"
                            >
                                <input type="hidden" name="id" value="<?= $produit['id_produit'] ?>">
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

        // On recuoérer les infos de l'produit
        fetch("/admin/produits/get-ajax.php?id=" + id)
        .then(response => response.json())
        .then(produit => {
            
            const modalTitle = document.querySelector('#staticBackdropLabel');
            modalTitle.textContent = produit.titre_produit;

            const modalContent = document.querySelector('#modalContent');
            modalContent.textContent = produit.contenu_produit;

        })
    })

</script>

<?php include '../includes/inc-bottom.php' ?>