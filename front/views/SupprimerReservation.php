<?php
include '../../controller/ReservationC.php';

$message = "";
$reservationC = new ReservationC();
$reservationC->SupprimerReservation($_GET["idReservation"]);
header('Location: afficherReservations.php');
?>