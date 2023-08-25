<?php
$dsn = 'mysql:dbname=e-commerce;host=127.0.0.1;charset=utf8mb4';
$user = 'root';
$password = '';

$dbh = new PDO($dsn, $user, $password, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // On définie la méthode de fetch par défaut 
]);