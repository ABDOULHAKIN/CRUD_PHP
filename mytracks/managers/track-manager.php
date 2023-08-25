<?php

require $_SERVER['DOCUMENT_ROOT'].'/includes/inc-db-connect.php'; 

// 1. fonction qui selectionne toutes les musiques dans la base des données


function getAllTrack()
{
    // Pour que la fontion puisse accéder à la BDD
    // Nous avons besoin de notifier que le $pdo = connexion à la BDD
    // Est une variable global avec le $GLOBALS
    $pdo = $GLOBALS['pdo'];
    $sql ="SELECT *, nom
           FROM track
           LEFT JOIN artist ON artist.id_artist=track.id_artist";
    // Pour pouvoir afficher la fonction, on la retouner
    //- query retourner en chaine de caractere la requete de $sql
    //- fetchAll transforme la chaine de caractere en tableau qu'on peut parcourir
    //-
    return $pdo->query($sql)->fetchAll();
}

//2. Fonction qui récupere la musique d'après son ID 

// Pourquoi ? 
// On passe un seul parametre, car on veut qu'à la sortie la fonction nous récupere l'argument juste id_article
// Pour qu'il puisse nous afficher seulement l'article dont son ID apparait à l'URL pas tous
function getTrackById(int $id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT * FROM track WHERE id_track = :id_track"; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_track'=> $id,
    ]);

    return $stmt->fetch();
}

// 3. Fonction qui affiche le titre de la musique grâce à son ID

function updateTrack(array $data)
{
    
    $data['titre_track'] = htmlspecialchars($data['titre_track']);
    
  
    $pdo = $GLOBALS['pdo'];
    $sql = "UPDATE track
    SET titre_track = :titre_track,
     id_artist = :id_artist
    WHERE id_track = :id_track";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  
    return $stmt->rowCount();

}

// 4. Fonction qui permet d'ajouter une musique 

function insertTrack(array $data)
{
    $data['titre_track'] = htmlspecialchars($data['titre_track']);

    $pdo = $GLOBALS['pdo'];
    $sql = "INSERT INTO track
    (titre_track, id_artist)
    VALUES(:titre_track, :id_artist)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute($data);
    // $stmt->debugDumpParms(); die;

    $id_article = $pdo->lastInsertId();
    
    return $id_article ;
}


// 5. Fonction qui permet de supprimer une musique grâce à son ID

function deleteTrack(int $int)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "DELETE FROM track
            WHERE id_track=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id'=>$int
    ]);

    return $stmt->rowCount(); // 
}


//--------------------------------------------------------------------------------------------------
//1. Fonction qui retoune tous les noms des artistes

function getArtist()
{
    $pdo=$GLOBALS['pdo'];
    $sql="SELECT *
        FROM artist
        ORDER BY nom ASC";

    return $pdo->query($sql)->fetchAll();
}

//-------------------------------------------------------------------------
// 1. Fonction qui verifie si les champs sont 

// tableau d'erreur qui est vide 
// verifie chaque champs si c'est vide 

function checkFormData(array $data, array $requireds=[]): array
{
    $errors = [];
    // $key = value 
    foreach ($data as $key => $value) 
    {
        if ($requireds == [] || in_array($key, $requireds)) 
        {
            if (empty($value)) 
            {
                $errors[$key] = "Ce champs ne doit pas être vide";
            }
        }
    }

    return $errors;
}


