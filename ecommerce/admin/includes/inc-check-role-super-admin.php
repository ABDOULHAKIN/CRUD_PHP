<?php

// On vérifie que l'utilisateur est bien admin
if(!in_array("ROLE_SUPER_ADMIN", $_SESSION['user']['roles']))
{
    header("Location: /"); die;
}