<?php
session_start();

// On vérifie que l'utilisateur est bien connecté
if(empty($_SESSION['user']))
{
    header("Location: /login.php"); die;
}

// On vérifie que l'utilisateur est bien admin
if(!in_array("ROLE_ADMIN", $_SESSION['user']['roles']))
{
    header("Location: /"); die;
}