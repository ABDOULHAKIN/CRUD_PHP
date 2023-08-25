<?php

// tableau d'erreur qui est vide 
// verifie chaque champs si c'est vide 

function checkFormData(array $data, array $requireds): array
{
    $errors = [];
    // $key = value 
    foreach ($data as $key => $value) {
        if ($requireds == [] || in_array($key, $requireds)) {
            if (empty($value)) {
                $errors[$key] = "Ce champs ne doit pas être vide";
            }
        }
    }

    return $errors;
}

//Nettoyage de champs 
//Verification de formulaire 