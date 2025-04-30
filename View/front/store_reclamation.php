<?php
include '../../controller/ReclamationC.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Basic validation
    if (
        isset($_POST["Type"], $_POST["Message"])
    ) {

        $reclamation = new Reclamation(
            1,
            $_POST["Type"],
            $_POST["Message"],
            "En Cours"
        );
        $ReclamationC = new ReclamationC();
        $ReclamationC->AjouterReclamation($reclamation);

        header("Location: front.php?success=1");
        exit();
    } else {
        echo "Erreur : Données manquantes ou mot de passe non confirmé.";
    }
}
?>
