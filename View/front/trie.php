<?php
include '../../Controller/ReclamationC.php';

$sort_by = $_GET['sort'] ?? 'dateReclamation'; // Champ de tri par défaut
$ReclamationC = new ReclamationC();

// Appel de la méthode de tri
$reclamations = $ReclamationC->getReclamationsTries($sort_by);

// Rediriger vers la page front (tu peux afficher les réclamations triées là-bas)
header('Location: front.php');
?>
