<?php
include '../../Controller/ReservationC.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $idBonPlan = $_POST['idBonPlan'];
    $dateDepart = $_POST['dateDepart'];
    $dateRetour = $_POST['dateRetour'];
    $nbPersonne = $_POST['nbPersonne'];
    $commentaire = $_POST['commentaire'] ?? '';
    
    // Create a new Reservation object
    // We pass null or empty string for destination since it's stored in the BonPlan
    $reservation = new Reservation(
        $idBonPlan,
        $dateDepart,
        $dateRetour,
        $nbPersonne,
        $commentaire,
        "En Attente"
    );
    
    // Create instance of ReservationC
    $reservationC = new ReservationC();
    
    // Add the reservation
    $reservationC->AjouterReservation($reservation);
    
    // Redirect back to the front page with a success message
    header('Location: front.php?section=reservations&status=success');
    exit();
} else {
    // Redirect back to the front page if accessed directly
    header('Location: front.php');
    exit();
}
?>
