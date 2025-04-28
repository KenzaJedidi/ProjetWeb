<?php
	include '../Controller/userC.php';
	$userC=new userC();
	$userC->deleteUser($_GET["id"]);
	header('Location:logout.php');
?>