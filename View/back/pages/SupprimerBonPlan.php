<?php
include '../../../Controller/BonPlanC.php';

	$message = "" ; 
	$BonPlanC=new BonPlanC();
	$BonPlanC->SupprimerBonPlan($_GET["idBonplan"]);
	header('Location:AfficherBonPlans.php');
?>