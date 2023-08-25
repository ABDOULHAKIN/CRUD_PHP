<?php
 
require $_SERVER['DOCUMENT_ROOT'].'/managers/module_function.php'; 

// Cette fonction permettra de generer toutes les modules enregistre dans la BDD

// $modules = getAllModule();

require $_SERVER['DOCUMENT_ROOT'].'/includes/inc-top.php';

// session_start();

if (isset($_POST['submit'])) {
    // Verification des champs lors de la soumission 

    $errors = checkFormData($_POST['modulee'], ['nom_module', 'details_module', 'temperature_module', 'vitesse_module','id_Etat']);

    // Pour verifier s'ils sont vide avant la soumission 

    if (count($errors) == 0) {
        $inserer = insertModule($_POST['modulee']);
        if ($inserer) {
            echo "Ce module bien ajouté dans la BDD";
            header("Location: /iwatch/index.php");
            exit();
        }
    } else {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

$etats = getEtat();



?>


<div class="global">
    <h1 class="title">Ajouter un nouveau module</h1>
    <div class="back">
        <div class="backbutton">
            <a href="/iwatch/index.php" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> 
            </a>
        </div>

    </div>

    <div class="ajout">
        <div class="form">
            <form action="/iwatch/new.php" id="form" method="POST" enctype="multipart/form-data">

                <div class="divinput">
                    <label for="nom" class="titreinput">Nom du module : </label>
                    <input id="nom" type="text" class="form-control" name="modulee[nom_module]" />
                    
                    <?php if (isset($errors['nom_module'])) : ?>
                        <p><small><?= $errors['nom_module'] ?></small></p>
                    <?php endif; ?>
                </div>

                <div class="divinput">
                    <label for="details" class="titreinput">Détails du module : </label>
                    <textarea id="details" class="form-control" name="modulee[details_module]"></textarea>
                    
                    <?php if (isset($errors['details_module'])) : ?>
                        <p><small><?= $errors['details_module'] ?></small></p>
                    <?php endif; ?>
                </div>

                <div class="divinput">
                    <label for="details" class="titreinput">Température : </label>
                    <!-- <textarea id="details" class="form-control" name="notice[temperature_notice]"></textarea> -->
                    <input type="number" class="form-control" name="modulee[temperature_module]" id="details">
                    
                    <?php if (isset($errors['temperature_notice'])) : ?>
                        <p><small><?= $errors['temperature_notice'] ?></small></p>
                    <?php endif; ?>
                </div>

                <div class="divinput">
                    <label for="details" class="titreinput">Vitesse : </label>
                    <input type="number" class="form-control" name="notice[vitesse_notice]" id="details">
                    
                    <?php if (isset($errors['vitesse_notice'])) : ?>
                        <p><small><?= $errors['vitesse_notice'] ?></small></p>
                    <?php endif; ?>
                </div>

                

                <div class="divinput">
                    <label for="categorie" class="titreinput">Etat du module : </label>
                    <select name="modulee[id_Etat]" class="form-control">
                        <?php foreach($etats as $etat): ?>
                        <option value="<?= $etat['id_Etat'] ?>">
                            <?= $etat['nom_etat'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="submit" name="submit" value="Ajouter" class="buttonsubmit">
            </form>
        </div>
    </div>
</div>

