<?php
include '../../../Controller/BonPlanC.php';

$bonPlanC = new BonPlanC();
$listBonPlans = $bonPlanC->AfficherBonPlan();

// Définir les en-têtes pour le téléchargement CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=bonplans.csv');

// Ouvrir la sortie en mode écriture
$output = fopen('php://output', 'w');

// Écrire les en-têtes de colonnes
fputcsv($output, array('ID', 'Destination', 'Restaurant', 'Hotel', 'Date de création'));

// Écrire les lignes
foreach ($listBonPlans as $bonPlan) {
    fputcsv($output, array(
        $bonPlan['idBonplan'],
        $bonPlan['destination'],
        $bonPlan['restaurant'],
        $bonPlan['hotel'],
        $bonPlan['dateCreation']
    ));
}

fclose($output);
exit;
?>
