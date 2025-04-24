<?php
include '../../../Controller/ReponseC.php';

	$message = "" ; 
	$ReponseC=new ReponseC();
	$ReponseC->SupprimerReponse($_GET["idReponse"]);
	header('Location:AfficherReponses.php');
?>