<?php
include '../../../Controller/CommentC.php';

	$message = "" ; 
	$CommentC=new CommentC();
	$CommentC->SupprimerComment($_GET["id"]);
	header('Location:AfficherCommentaires.php');
?>