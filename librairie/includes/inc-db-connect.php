<?php

$pdo = new PDO("mysql:host=localhost;dbname=librairie;charset=utf8mb4", "root", "", [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

