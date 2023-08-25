<?php 
require $_SERVER['DOCUMENT_ROOT']. '/managers/track-manager.php';

if(!empty($_POST['id_track']))
{
    // pourquoi dans le POST on renseigne l'ID ? 
    $delete = deleteTrack($_POST['id_track']);


    if($delete == 1 )
    {
        header("Location: /track"); exit; 
    }
    else{
        echo "Une erreur s'est produite lors de la suppression"; 
    }
}
else
{
    header("Location: /mytracks/track"); exit; 
}

require $_SERVER['DOCUMENT_ROOT']. '/includes/inc-top.php';

require $_SERVER['DOCUMENT_ROOT']. '/includes/inc-bottom.php';
?>