<?php
include '../../../Controller/ReservationC.php';

$reservationC = new ReservationC();

if (isset($_GET["idReservation"])) {
    // Update the reservation status to "Confirmée"
    if ($reservationC->UpdateReservationStatus($_GET["idReservation"], "Confirmée")) {
        // Status updated successfully
        $message = "Réservation confirmée avec succès.";
    } else {
        // Failed to update status
        $message = "Erreur lors de la confirmation de la réservation.";
    }
} else {
    $message = "ID de réservation non spécifié.";
}

// Redirect back to the reservations list
header('Location:AfficherReservations.php');
?>
