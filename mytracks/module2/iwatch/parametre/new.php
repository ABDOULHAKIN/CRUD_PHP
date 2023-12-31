<?php
require $_SERVER['DOCUMENT_ROOT'] . '/managers/module_function.php';
session_start();

if (isset($_POST['submit'])) 
{
    $errors = checkFormData($_POST['type_donnee'], ['libelle_type_donnee']);

    // Pour verifier s'ils sont vide avant la soumission 

    if (count($errors) == 0) 
    {
        $inserer = insertParam($_POST['type_donnee']);
        
        if ($inserer) 
        {
            echo "La musique a été bien ajouté dans la BDD";
            header("Location: /iwatch/parametre");
        }else {
            echo "Une erreur s'est produite";
        }
    } 
    else 
    {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}



require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
?>


<div class="container py-5">
    <h1>Insérer un paramétre</h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/iwatch/parametre/index.php" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i>
            </a>
        </div>

    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/iwatch/parametre/new.php" id="form" method="POST" enctype="multipart/form-data">

                <div class="form-control">
                    <label for="libelle">Libellé du paramétre</label>
                    <input id="libelle" type="text" class="form-control" name="type_donnee[libelle_type_donnee]" />
                    <?php if (isset($errors['libelle_type_donnee'])) : ?>
                        <p><small><?= $errors['libelle_type_donnee'] ?></small></p>
                    <?php endif; ?>
                </div>

                
                <input type="submit" name="submit" value="Enregistrer" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>