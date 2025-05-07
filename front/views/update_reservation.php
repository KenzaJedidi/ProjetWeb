<?php
include '../../controller/ReservationC.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["idReservation"], $_POST["Type"], $_POST["Lieu"], $_POST["Date"])) {

        $idReservation = $_POST["idReservation"];
        $reservationC = new ReservationC();

        $reservation = new Reservation(
            1, // idUser (à remplacer par l'ID réel utilisateur)
            $_POST["Type"],
            $_POST["Lieu"],
            $_POST["Date"],
            $_POST["Details"] ?? null, // Champ optionnel
            $_POST["Statut"] ?? "en attente" // Valeur par défaut
        );

        $reservationC->ModifierReservation($reservation, $idReservation);
        header("Location: afficherReservations.php?updated=1");
        exit();
    } else {
        echo "Erreur : Données incomplètes (ID, Type, Lieu et Date sont obligatoires).";
    }
}
?>