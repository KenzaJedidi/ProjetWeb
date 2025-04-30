<?php
include '../../../Controller/ReservationC.php';

	$message = "" ; 
	$ReservationC=new ReservationC();
	$ReservationC->SupprimerReservation($_GET["idReservation"]);
	header('Location:AfficherReservations.php');
?>