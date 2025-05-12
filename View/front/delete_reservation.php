<?php
include '../../Controller/ReservationC.php';

if (isset($_GET["id"])) {
    $ReservationC = new ReservationC();
    $ReservationC->SupprimerReservation($_GET["id"]);
    // Redirect back with success message
    header('Location: front.php?section=reservations&status=deleted');
} else {
    // Redirect back with error message
    header('Location: front.php?section=reservations&status=error');
}
?>
