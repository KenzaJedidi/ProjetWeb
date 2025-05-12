<?php
include '../../../Controller/PostC.php';

	$message = "" ; 
	$PostC=new PostC();
	$PostC->SupprimerPost($_GET["id"]);
	header('Location:AfficherPosts.php');
?>