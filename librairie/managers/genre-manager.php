<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/inc-db-connect.php';

function getAllGenres()
{
    $pdo = $GLOBALS['pdo'];	
    $sql = "SELECT * FROM genre";
    return $pdo->query($sql)->fetchAll();
}

function getGenreById(int $id)
{
    $pdo = $GLOBALS['pdo'];	
    $sql = "SELECT * FROM genre WHERE id_genre = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function insertGenre(array $data)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "INSERT INTO genre (nom_genre) VALUES (:nom_genre)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    return $pdo->lastInsertId();
}

function updateGenre(array $data)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "UPDATE genre 
    SET nom_genre = :nom_genre 
    WHERE id_genre = :id_genre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    return $stmt->rowCount();
}

function deleteGenre(int $id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "DELETE FROM genre WHERE id_genre = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->rowCount();
}