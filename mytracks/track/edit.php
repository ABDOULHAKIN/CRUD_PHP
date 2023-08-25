<?php

require $_SERVER['DOCUMENT_ROOT'] . '/managers/track-manager.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-top.php';


if (isset($_POST['submit'])) 
{
 
    $errors = checkFormData($_POST['track'], ['titre_track']);

    // Pour verifier s'ils sont vide avant la soumission 

    if (count($errors) == 0) 
    {
        $modif = updateTrack($_POST['track']);
        
   
        if ($modif == 1) 
        {
            echo "La musique a bien été modifier";
            header("Location: /track");
        }
        else
        {
            echo "Une erreur est survenue lors de la modification";
        }
    } 
    else 
    {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

// 1. Verification de l'URL 

// Verifiez le paramétre de la fonction "vu qu'on a passé en parametre juste l'ID" : si l'ID de l'article dans l'URL n'est pas vide
// Si c'est vide, on redirige vers la page
if (empty($_GET['id'])) 
{
    header("Location: /track");
    die;
}



// 3. Récuperez les contenus de l'article à modifier grâce à son ID
$music = getTrackById($_GET['id']);

// 4. Verifiez si l'article récuperer grâce à son ID est bien présent dans la BDD
if (!$music) 
{
    header("Location: /track");
    die;
}

$artists = getArtist();


?>

<div class="container py-5">
    <h1>Modifier cette musique : <?= $music['titre_track'] ?></h1>
    <div class="row mb-4">
        <div class="col-auto">
            <a href="/track" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>Révenir aux musiques précédentes 
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col col-md-6">
            <form action="/track/edit.php?id=<?= $_GET['id'] ?>" method="POST">
                
                <!--1. Afficher le titre de l'article avec le contenu à modifier-->
                <div class="form-group">
                    <label for="titre">Titre de la musique</label>
                    <input id="titre" type="text" class="form-control" name="track[titre_track]" value="<?= $music['titre_track'] ?>" />
                    <?php if (isset($errors['titre_track'])) : ?>
                        <p><small><?= $errors['titre_track'] ?></small></p>
                    <?php endif; ?>
                </div>              
                <div class="form-group mb-3">
                    <label for="artist">Les artistes</label>
                    <select name="track[id_artist]" class="form-control">
                        <?php foreach($artists as $artist): ?>
                        <option value="<?= $artist['id_artist'] ?>" 
                        <?php if($artist['id_artist'] == $music['id_artist']): echo "selected"; endif; ?>>
                            <?= $artist['nom'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!--Pour un input hidden, parce que il faut qu'on envoie l'id dans le POST pour pourvoir faire notre requete-->
                <input type="hidden" name="track[id_track]" value="<?= $music['id_track'] ?>">
                <input type="submit" name="submit" value="Envoyer" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-bottom.php';
?>