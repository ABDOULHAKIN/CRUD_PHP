
<?php
 session_start();
 require $_SERVER['DOCUMENT_ROOT'].'/managers/module_function.php'; 
 
 
 require $_SERVER['DOCUMENT_ROOT'].'/includes/inc-top.php';

 // recuperer les types des données associer à un module
 //

if (isset($_POST['generate'])) {
    // Vérification si un module a été sélectionné
    if (isset($_POST['module']) && !empty($_POST['module'])) {
        $selectedModuleId = $_POST['module'];

        // Génération aléatoire des valeurs pour la température, la vitesse et l'humidité
        $temperature = rand(0, 100);
        $vitesse = rand(0, 100);
        $humidite = rand(0, 100);

//1. requete pour recuperer la valeur_actuelle depuis la table historique

$valeur_actuelle = getAllValeur_actuelle();
// $valeurs_voulues = getAllValeur_voulu();
//2. Le traitement algo pour voir si la valeur_voulu et la valeur_actuelle generer automatiquement ont un dysfonctionnnement
// Étape 1: Traitements des algorithmes pour chaque module en fonction de leurs paramètres (température, vitesse et humidité)

$notification_table = array(); // Tableau pour stocker les informations de notification

foreach ($valeurs_actuelles as $actuelle) {
    $valeur_actuelle_humidite = null; // Déclaration de la variable pour l'humidité

    foreach ($valeurs_voulues as $voulu) {
        if ($actuelle['id_modulee'] == $voulu['id_modulee'] && $actuelle['id_type_donnee'] == $voulu['id_type_donnee']) {
            $type_donnee = $actuelle['libelle_type_donnee'];

            // Comparaison pour la température
            if ($type_donnee === 'Température') {
                $attendu_temperature = $voulu['valeur_voulu'];
                $valeur_actuelle_temperature = $actuelle['valeur_actuelle'];

                // Vérifier si la température actuelle est au moins 10% supérieure à la température désirée
                if ($valeur_actuelle_temperature >= ($attendu_temperature * 1.1)) {
                    // Ajouter les informations dans le tableau
                    $notification_table[] = array(
                        'module' => $actuelle['nom_module'],
                        'type_donnee' => $type_donnee,
                        'valeur_actuelle' => $valeur_actuelle_temperature,
                        'valeur_voulu' => $attendu_temperature,
                        'message' => 'La température de la montre a augmenté et a besoin d\'être éteinte.'
                    );
                }
                // Vérifier si la température actuelle est au moins 10% inférieure à la température désirée
                elseif ($valeur_actuelle_temperature <= ($attendu_temperature * 0.9)) {
                    // Vérifier si l'humidité a diminué de 10%
                    if ($valeur_actuelle_humidite !== null && $valeur_actuelle_humidite < ($actuelle['valeur_actuelle'] * 0.9)) {
                        // Ajouter les informations dans le tableau
                        $notification_table[] = array(
                            'module' => $actuelle['nom_module'],
                            'type_donnee' => 'Humidité',
                            'valeur_actuelle' => $actuelle['valeur_actuelle'],
                            'valeur_voulu' => $attendu_humidite,
                            'message' => 'Avec la diminution de la température, risque d\'apparition d\'une buée qui peut endommager l\'appareil.'
                        );
                    }
                }
            }

            // Comparaison pour la vitesse
            elseif ($type_donnee === 'Vitesse') {
                $attendu_vitesse = $voulu['valeur_voulu'];
                $valeur_actuelle_vitesse = $actuelle['valeur_actuelle'];

                // Vérifier si la vitesse actuelle est au moins 10% supérieure à la vitesse désirée
                if ($valeur_actuelle_vitesse >= ($attendu_vitesse * 1.1)) {
                    // Ajouter les informations dans le tableau
                    $notification_table[] = array(
                        'module' => $actuelle['nom_module'],
                        'type_donnee' => $type_donnee,
                        'valeur_actuelle' => $valeur_actuelle_vitesse,
                        'valeur_voulu' => $attendu_vitesse,
                        'message' => 'La vitesse de la montre a augmenté et n\'indique peut-être pas l\'heure exacte.'
                    );
                }
                // Vérifier si la vitesse actuelle est au moins 10% inférieure à la vitesse désirée
                elseif ($valeur_actuelle_vitesse <= ($attendu_vitesse * 0.9)) {
                    // Vérifier si l'humidité a augmenté
                    if ($valeur_actuelle_humidite !== null && $valeur_actuelle_humidite > $actuelle['valeur_actuelle']) {
                        // Ajouter les informations dans le tableau
                        $notification_table[] = array(
                            'module' => $actuelle['nom_module'],
                            'type_donnee' => 'Humidité',
                            'valeur_actuelle' => $actuelle['valeur_actuelle'],
                            'valeur_voulu' => $attendu_humidite,
                            'message' => 'La vitesse de la montre a diminué et peut endommager l\'appareil vu que l\'humidité a augmenté.'
                        );
                    }
                }
            }

            // Comparaison pour l'humidité
            elseif ($type_donnee === 'Humidité') {
                $attendu_humidite = $voulu['valeur_voulu'];
                $valeur_actuelle_humidite = $actuelle['valeur_actuelle'];

                // Vérifier si l'humidité actuelle est au moins 10% supérieure à l'humidité désirée
                if ($valeur_actuelle_humidite >= ($attendu_humidite * 1.1)) {
                    // Ajouter les informations dans le tableau
                    $notification_table[] = array(
                        'module' => $actuelle['nom_module'],
                        'type_donnee' => $type_donnee,
                        'valeur_actuelle' => $valeur_actuelle_humidite,
                        'valeur_voulu' => $attendu_humidite,
                        'message' => 'Risque d\'apparition de buées.'
                    );
                }
            }
        }
    }
}
// 3. Ajoute lors de l'insertion des données dans la table notification le booleen pour savoir s'il doit etre afficher ou pas 
// Étape 2: Insérer les données en dysfonctionnement dans la table "notification"

// Insérer les données dans la table "notification"
if (!empty($notification_table)) {
    foreach ($notification_table as $notification) {
        // Vérifier si les clés existent dans le tableau $notification avant de les utiliser
        $id_type_donnee = isset($notification['id_type_donnee']) ? $notification['id_type_donnee'] : null;
        $id_modulee = isset($notification['id_modulee']) ? $notification['id_modulee'] : null;

        // Vérifier si les clés sont nulles, dans ce cas, récupérer les valeurs depuis les données de la notification
        if ($id_type_donnee === null) {
            $id_type_donnee = getId_type_donnee($notification['type_donnee']);
        }

        if ($id_modulee === null) {
            $id_modulee = getId_modulee($notification['module']);
        }

        $data = array(
            'valeur_actuelle_notification' => $notification['valeur_actuelle'],
            'valeur_voulu_notification' => $notification['valeur_voulu'],
            'message' => $notification['message'],
            'checked' => isset($notification['checked']) ? $notification['checked'] : 0,
            'id_type_donnee' => $id_type_donnee,
            'id_modulee' => $id_modulee
        );

        // Supprimer
        $delete = deleteModule($id_modulee);

        // Appel de la fonction insertValeur pour insérer les données dans la table "notification".
        $insertedId = insertValeur($data);
        // Vérifier les erreurs lors de l'insertion
        if ($insertedId === false) {
            echo "Erreur lors de l'insertion des données dans la table notification.";
        } 
    }
} else {
    $_SESSION['erreur'] = "Aucune notification, le module fonctionne correctement";
}

//4. Faire la requete d'insertion ces donnnées comparer dans la table historique pour voir si le notify est 1 pour afficher des notifications en dysfonctionnnement

        // Insertion ou mise à jour des données de température dans la table historique
        $stmt = $pdo->prepare("INSERT INTO historique (id_modulee, id_type_donnee, valeur_actuelle, date_historique) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$selectedModuleId, 1, $temperature]);

        // Insertion  des données de vitesse dans la table historique
        $stmt = $pdo->prepare("INSERT INTO historique (id_modulee, id_type_donnee, valeur_actuelle, date_historique) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$selectedModuleId, 2, $vitesse]);

        // Insertion ou mise à jour des données d'humidité dans la table historique
        $stmt = $pdo->prepare("INSERT INTO historique (id_modulee, id_type_donnee, valeur_actuelle, date_historique) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$selectedModuleId, 3, $humidite]);

        echo "<p>Données générées pour le module avec l'ID : $selectedModuleId</p>";
    } else {
        echo "<p>Veuillez sélectionner un module avant de générer les données.</p>";
    }
}



?>

<style>
       .content{
        display: flex;
        margin-left: 300px;
        align-content: center;
       }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            width: 500px;
        }

        h1 {
            margin-bottom: 20px;
        }

        label {
            margin-bottom: 10px;
        }

        select {
            margin-bottom: 20px;
            padding: 10px;
            width: 200px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
            animation: pulse 0.5s infinite alternate;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.05);
            }
        }
    </style>
    <h1>Génération Automatique de Données pour les Modules</h1>
<div class="content">
    <form action="" method="post">
    <label for="module">Choisissez un module :</label>
    <select name="module" id="module">
        <?php
        $nomModules = getNameModule();
        if ($nomModules) {
            foreach ($nomModules as $nomModule) {
                $moduleId = $nomModule['id_modulee'];
                $moduleName = $nomModule['nom_module'];
                echo "<option value=\"$moduleId\">$moduleName</option>";
            }
        }
        ?>
    </select>
    <input type="submit" name="generate" value="Générer Automatiquement">
</form>
</div>


<?php
 require $_SERVER['DOCUMENT_ROOT'].'/includes/inc-bottom.php';
?>
