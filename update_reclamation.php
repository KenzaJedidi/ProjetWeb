<?php
include '../../controller/ReclamationC.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id"],$_POST["Type"], $_POST["Message"])) {

        $id = $_POST["id"];
        $ReclamationC = new ReclamationC();

        $reclamation = new Reclamation(
            1,
            $_POST["Type"],
            $_POST["Message"],
            "En Cours"
        );

        $ReclamationC->ModifierReclamation($reclamation, $id);
        header("Location: front.php?updated=1");
        exit();
    } else {
        echo "Erreur : données incomplètes.";
    }
}
?>
