<?php
include '../../Controller/ReclamationC.php';

	$message = "" ; 
	$ReclamationC=new ReclamationC();
	$ReclamationC->SupprimerReclamation($_GET["idReclamation"]);
	header('Location:front.php');
?>