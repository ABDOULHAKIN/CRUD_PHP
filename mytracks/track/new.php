<?php
require $_SERVER['DOCUMENT_ROOT'] . '/managers/track-manager.php';
session_start();

if (isset($_POST['submit'])) 
{
    //--------------------------------------------------------
    // $errors = [];

    // if(empty($_POST['chaine']['nom_chaine']))
    // $errors['nom_chaine'] = "le champ ne doit pas etre vide";
    // if(count($errors) > 0){
    //     $_SESSION['errors'] = $errors;
    //     header("Location: /admin/parametre-chaines/new.php");
    //     die;
    // }
    //--------------------------------------------
    // Verification des champs lors de la soumission 

    $errors = checkFormData($_POST['track'], ['titre_track']);

    // Pour verifier s'ils sont vide avant la soumission 

    if (count($errors) == 0) 
    {
        $inserer = insertTrack($_POST['track']);
        
        if ($inserer) 
        {
            echo "La musique a été bien ajouté dans la BDD";
            header("Location: /track");
        }else {
            echo "Une erreur s'est produite";
        }
    } 
    else 
    {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

$artists = getArtist();

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';
?>


<div class="container py-5">
    <h1>Insérer un titre de musique</h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/track/index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Revenir aux titres précédente
            </a>
        </div>

    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/track/new.php" id="form" method="POST" enctype="multipart/form-data">

                <div class="form-control">
                    <label for="titre">Titre de la musique</label>
                    <input id="titre" type="text" class="form-control" name="track[titre_track]" />
                    <?php if (isset($errors['titre_track'])) : ?>
                        <p><small><?= $errors['titre_track'] ?></small></p>
                    <?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="artist">Les artistes</label>
                    <select name="track[id_artist]" class="form-control">
                        <?php foreach($artists as $artist): ?>
                        <option value="<?=$artist['id_artist']?>">
                            <?=$artist['nom']?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <input type="submit" name="submit" value="Enregistrer" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>