<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';

function getAllBooks()
{
    $pdo = $GLOBALS['pdo'];	
    $sql = "SELECT l.*, GROUP_CONCAT(g.nom_genre SEPARATOR ',') as genres FROM `livre` l LEFT JOIN livre_genre lg ON lg.id_livre = l.id_livre LEFT JOIN genre g ON g.id_genre = lg.id_genre GROUP BY l.id_livre";
    return $pdo->query($sql)->fetchAll();
}

function getBookById(int $id)
{
    $pdo = $GLOBALS['pdo'];	
    $sql = "SELECT * FROM livre WHERE id_livre = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

// Trouver le nom du genre d'un livre 

function getBooksGenres(int $id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT g.* 
    FROM genre g 
    JOIN livre_genre lg ON lg.id_genre = g.id_genre 
    WHERE id_livre = :id_livre"; 
    // etiquette temporaire, un this
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        // le this correspond à $id 
        'id_livre' => $id
    ]);

    return $stmt->fetchAll();
}

function getBooksGenresIds(int $id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT g.id_genre 
    FROM genre g 
    JOIN livre_genre lg ON lg.id_genre = g.id_genre 
    WHERE id_livre = :id_livre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id_livre' => $id
    ]);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function insertBook(array $data, array $genres)
{
    $pdo = $GLOBALS['pdo'];

    // Traiter les données pour éviter les failles XSS afin que des scripts ne soient pas interpretre 
    // Car lorsqu'on injecte du script dans notre code " <script>alert("Hello")</script> " pour les champs string 
    // Il nous affiche le script 

    $data['auteur_livre'] = htmlspecialchars($data['auteur_livre']);
    $data['titre_livre'] = htmlspecialchars($data['titre_livre']);
    $data['editeur_livre'] = htmlspecialchars($data['editeur_livre']);

   

    $sql = "INSERT INTO livre (titre_livre, auteur_livre, editeur_livre) VALUES (:titre_livre, :auteur_livre, :editeur_livre)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    $id_livre = $pdo->lastInsertId();

    // On traite les genres
    if(count($genres) > 0)
    {
        foreach($genres as $id_genre)
        {
            $sql = "INSERT INTO livre_genre (id_livre, id_genre) VALUES (:id_livre, :id_genre)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_livre' => $id_livre,
                'id_genre' => $id_genre
            ]);
        }
    }

    return $id_livre;
}

function updateBook(array $data, array $genres = [])
{
    $pdo = $GLOBALS['pdo'];

    // Traiter les données pour éviter les failles XSS afin que des scripts ne soient pas interpretre 
    // Car lorsqu'on injecte du script dans notre code " <script>alert("Hello")</script> " pour les champs string
    // Il nous affiche le script 


    $sql = "UPDATE livre 
    SET titre_livre = :titre_livre, auteur_livre = :auteur_livre, editeur_livre = :editeur_livre 
    WHERE id_livre = :id_livre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    $rowCount = $stmt->rowCount();

    $id_livre = $data['id_livre'];

    // On traite les genres
    // On recupère les genres du livre
    $booksGenres = getBooksGenresIds($id_livre);
    
    if(count($genres) > 0)
    {
        foreach($genres as $id_genre)
        {
            // On vérifie si le genre n'est pas déjà relié au livre, si non on le relie
            if(!in_array($id_genre, $booksGenres))
            {
                $sql = "INSERT INTO livre_genre (id_livre, id_genre) VALUES (:id_livre, :id_genre)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'id_livre' => $id_livre,
                    'id_genre' => $id_genre
                ]);
            }
        }  
    }

    foreach($booksGenres as $bookGenre)
    {
        if(!in_array($bookGenre, $genres))
        {
            $sql = "DELETE FROM livre_genre WHERE id_livre = :id_livre AND id_genre = :id_genre";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_livre' => $id_livre,
                'id_genre' => $bookGenre
            ]);
        }
    }


    return $rowCount;
}

function deleteBook(int $id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "DELETE FROM livre WHERE id_livre = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->rowCount();
}