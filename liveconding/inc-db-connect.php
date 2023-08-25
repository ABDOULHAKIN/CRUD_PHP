<?php

try
{
    // Si jamais la connexion échoue on envoie l'exception avec le message d'erreur
    $db = new PDO('mysql:host=localhost;dbname=liveconding', 'root', '');
    $db->exec('SET NAMES "UTF8"');
}

// L'exception on met dans la variable $e
catch(PDOException $e)
{
    // Au cas ou on aurait une exeption on aura un message d'erreur
    echo 'Erreur : '. $e->getMessage();
    // On fait un die pour arreter l'execution de la connnexion
    die();
}