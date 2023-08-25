<?php

$pdo = new PDO('mysql:host=localhost;dbname=correction', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);