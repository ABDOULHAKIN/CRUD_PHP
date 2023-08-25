<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/managers/module_function.php';

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';

if (isset($_POST['submit'])) {

    // TODO: verifiez quels sont les elements qui checke et qu'il y a moins un 
    // verifier qu'il y a une valeur pour chaque element checke
    // Vérifier quels sont les éléments qui sont cochés et qu'il y en a au moins un
    $elementsCoches = [];

    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT  id_type_donnee, libelle_type_donnee FROM type_donnee";
    $result = $pdo->query($sql);
    $typesDonnees = $result->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($typesDonnees as $type) {
        $elementName = "modulee[{$type['libelle_type_donnee']}_check]";
        if (isset($_POST[$elementName])) {
            $elementsCoches[] = $type['libelle_type_donnee'];
        }
    }
  

    if (count($elementsCoches) === 0) {
        $_SESSION['erreurL'] = "Veuillez sélectionner au moins un élément.";
        // Rediriger vers la page d'ajout du module avec le message d'erreur
        header("Location: /iwatch/new1.php");
    }

    // Vérifier qu'il y a une valeur pour chaque élément coché
    $valeursManquantes = [];
    foreach ($elementsCoches as $element) {
        $valeurElementName = "modulee[{$element}_value]";
        if (empty($_POST[$valeurElementName])) {
            $valeursManquantes[] = $element;
        }
    }

    if (count($valeursManquantes) > 0) {
        $_SESSION['erreur'] = "Veuillez saisir une valeur pour chaque élément sélectionné : " . implode(', ', $valeursManquantes);
        // Rediriger vers la page d'ajout du module avec le message d'erreur
        header("Location: /iwatch/new1.php");
    }

    // Vérifier si le numéro de série existe déjà dans la base de données
    $numeroSerieModule = $_POST['modulee']['numero_serie_module'];
    $moduleExiste = verifModuleExiste($numeroSerieModule);
    if ($moduleExiste) {
        $_SESSION['erreurM'] = "Le numéro de série est déjà lié à une montre.";
    } else {
        // Vérification des champs lors de la soumission
        $moduleData = $_POST['modulee']; // Accéder aux données du formulaire
        $errors = checkFormData($moduleData, ['nom_module', 'details_module', 'temperature_module', 'vitesse_module', 'numero_serie_module', 'id_etat']);

        // Vérifier s'ils sont vides avant la soumission
        if (count($errors) == 0) {
            // Insertion du module uniquement si le numéro de série n'existe pas déjà
            // £moduleData => crée des valeurs pour ce qui sont selectionnés
            $inserer = insertModule($moduleData);
            if ($inserer) {
                $_SESSION['success'] = "Le module a été ajouté avec succès !";
                header("Location: /iwatch/index.php");
                exit();
            }
        } else {
            $_SESSION['erreur'] = "Le formulaire est incomplet";
        }
    }
}

// TODO:
$pdo = $GLOBALS['pdo'];
$sql = "SELECT id_type_donnee, libelle_type_donnee FROM type_donnee";
$result = $pdo->query($sql);
$typesDonnees = $result->fetchAll(PDO::FETCH_ASSOC);
// $typesDonnees = methodeGetTypes();

// Définir le message d'erreur global si des champs sont manquants
$erreurGlobale = isset($_SESSION['erreur']) ? $_SESSION['erreur'] : null;
unset($_SESSION['erreur']);


$etats = getAllEtat();
?>


<div class="global">

    <h1 class="title">Ajouter un nouveau module</h1>
    <?php if (isset($_SESSION['erreurL'])) : ?>
        <div class="error-message">
            <?= $_SESSION['erreurL'] ?>
        </div>
        <?php unset($_SESSION['erreurL']); ?>
    <?php endif; ?>
    
    <!-- Vérifier s'il y a un message d'erreur dans la session et l'afficher -->
    <?php if (isset($_SESSION['erreurM'])) : ?>
        <div class="error-message">
            <?= $_SESSION['erreurM'] ?>
        </div>
        <?php unset($_SESSION['erreurM']); ?>
    <?php endif; ?>

    <!-- Vérifier s'il y a un message d'erreur dans la session et l'afficher -->
    <?php if (isset($_SESSION['erreur'])) : ?>
        <div class="error-message">
            <?= $_SESSION['erreur'] ?>
        </div>
        <?php unset($_SESSION['erreur']); ?>
    <?php endif; ?>

    <div class="back">
        <div class="backbutton">
            <a href="/iwatch/index.php" class="boutton">
                <i class="bx bx-arrow-back"></i>
            </a>
        </div>

    </div>

    <div class="ajout">
        <div class="form">
            <form action="/iwatch/new1.php" id="form" method="POST" enctype="multipart/form-data">

                <div class="input-container">
                    <div class="divinput">
                        <label for="nom" class="titreinput">Nom du module : </label>
                        <input id="nom" type="text" class="form-control" name="modulee[nom_module]" />
                    </div>
                    <?php if (isset($errors['nom_module'])) : ?>
                        <p><small><?= $errors['nom_module'] ?></small></p>
                    <?php endif; ?>
                </div>

                <div class="input-container">
                    <div class="divinput">
                        <label for="details" class="titreinput">Détails du module : </label>
                        <textarea id="details" class="form-control" name="modulee[details_module]"></textarea>
                    </div>
                    <?php if (isset($errors['details_module'])) : ?>
                        <p><small><?= $errors['details_module'] ?></small></p>
                    <?php endif; ?>
                </div>

                <div class="input-container">
                    <div class="divinput">
                        <label for="details" class="titreinput">Etat du montre : </label>
                        <select name="modulee[id_etat]" class="form-control">
                            <?php foreach ($etats as $etat) : ?>
                                <option value="<?= $etat['id_etat'] ?>">
                                    <?= $etat['libelle_etat'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if (isset($errors['libelle_etat'])) : ?>
                        <p><small><?= $errors['libelle_etat'] ?></small></p>
                    <?php endif; ?>
                </div>

                <div class="input-container">
                    <span class="info-icon" data-tooltip="La température exacte pour que la montre fonctionne"><i class='bx bx-info-circle'></i></span>
                </div>

                <div class="input-container">
                    <div class="divinput">
                        <label for="details" class="titreinput">Numero de série : </label>
                        <input type="text" class="form-control" name="modulee[numero_serie_module]" id="details">
                    </div>
                    <?php if (isset($errors['numero_serie_module'])) : ?>
                        <p><small><?= $errors['numero_serie_module'] ?></small></p>
                    <?php endif; ?>
                </div>
                <?php
                $count = 0;
                foreach ($typesDonnees as $type) {
                ?>
                    <div class="divinput input-container">
                        <label for="modulee[<?= $type['libelle_type_donnee'] ?>_check]" class="titreinput"> <?= $type['libelle_type_donnee'] ?> :</label>
                        <input type="checkbox" name="modulee[<?= $type['libelle_type_donnee'] ?>_check]" id="<?= $type['libelle_type_donnee'] ?>_module" />
                        <span class="checkmark"></span>
                        <input type="number" class="form-control data-input" name="modulee[<?= $type['libelle_type_donnee'] ?>_value]" placeholder="Entrez des données" />
                    </div>
                    <?php if (isset($errors['valeur_voulu'][$count++])) : ?>
                        <p class="error-message"><small><?= $errors['valeur_voulu'][$count] ?></small></p>
                    <?php endif; ?>
                <?php
                    $count++;
                }
                ?>
                <input type="submit" name="submit" value="Ajouter" class="buttonsubmit">
            </form>
        </div>
    </div>
</div>
</div>