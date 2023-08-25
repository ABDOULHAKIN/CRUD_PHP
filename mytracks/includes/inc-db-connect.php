<?php

$pdo = new PDO('mysql:host=localhost;dbname=musique', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

