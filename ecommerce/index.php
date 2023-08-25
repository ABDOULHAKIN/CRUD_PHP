<?php

require $_SERVER['DOCUMENT_ROOT'] . '/managers/produit-manager.php';
$data = getAllProductsWithPagination();
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';

?>
<div class="container py-4">
    <div class="row">
        <?php foreach ($data['produits'] as $produit) :?>
            <div class="col-6 col-md-3">
                <div class="card">
                    <?php if($produit['url_image']): ?>
                    <img src='<?= $produit['url_image'] ?>' alt='' />
                    <?php else: ?>
                    <img src='http://fakeimg.pl/400x400' alt='' />
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title"><?= $produit['nom_produit'] ?></h5>
                        <strong><?= $produit['prix_ht_produit'] * 1.20 ?> € TTC</strong>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if ($data['current_page'] != 1) : ?>
                    <li class="page-item">
                        <a class="page-link" href="/?page=<?= $data['current_page'] - 1 ?>">Précédent</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $data['pages_count']; $i++) : ?>
                    <?php if (($i > $data['current_page'] - 10) && ($i < $data['current_page'] + 10)) : ?>
                        <li class="page-item">
                            <a class="page-link" href="/?page=<?= $i ?>" <?php if ($i == $data['current_page']) : echo "disabled";
                                                                            endif; ?>><?= $i ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($data['current_page'] != $data['pages_count']) : ?>
                    <li class="page-item">
                        <a class="page-link" href="/?page=<?= $data['current_page'] + 1 ?>">Suivant</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>
</div>
</div>
<?php require 'includes/inc-bottom.php'; ?>