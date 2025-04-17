<?php
include '../../controller/ReservationC.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation de base
    if (
        isset($_POST["Type"], $_POST["Lieu"], $_POST["Date"])
    ) {
        $reservation = new Reservation(
            1, // idUser (à remplacer par l'ID réel de l'utilisateur)
            $_POST["Type"],
            $_POST["Lieu"],
            $_POST["Date"],
            $_POST["Details"] ?? null, // Champ optionnel
            "en attente" // Statut par défaut
        );
        
        $reservationC = new ReservationC();
        $reservationC->AjouterReservation($reservation);

        header("Location: afficherReservations.php?success=1");
        exit();
    } else {
        echo "Erreur : Données manquantes (Type, Lieu et Date sont obligatoires).";
    }
}
?>