<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure le contrôleur BonPlanC
include '../../../Controller/BonPlanC.php';

try {
    // Instancier le contrôleur et récupérer la liste des Bon Plans
    $bonPlanC = new BonPlanC();
    $listBonPlans = $bonPlanC->AfficherBonPlan();

    // Récupérer le format demandé via GET, par défaut 'csv'
    $format = $_GET['format'] ?? 'csv';

    if ($format === 'csv') {
        // En-têtes pour CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=bonplans-' . date('Y-m-d') . '.csv');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Ouvrir la sortie en mode écriture
        $output = fopen('php://output', 'w');

        // Écrire les en-têtes de colonnes
        fputcsv($output, ['ID', 'Destination', 'Restaurant', 'Hotel', 'Date de création']);

        // Écrire les lignes
        foreach ($listBonPlans as $bonPlan) {
            fputcsv($output, [
                $bonPlan['idBonplan'],
                $bonPlan['destination'],
                $bonPlan['restaurant'],
                $bonPlan['hotel'],
                $bonPlan['dateCreation']
            ]);
        }

        fclose($output);
        exit;

    } elseif ($format === 'excel') {
        // En-têtes pour Excel
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename=bonplans-' . date('Y-m-d') . '.xls');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Début du contenu HTML pour Excel
        echo '<html>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        echo '<table border="1">';
        echo '<tr>
                <th>ID</th>
                <th>Destination</th>
                <th>Restaurant</th>
                <th>Hotel</th>
                <th>Date de création</th>
              </tr>';

        foreach ($listBonPlans as $bonPlan) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($bonPlan['idBonplan']) . '</td>';
            echo '<td>' . htmlspecialchars($bonPlan['destination']) . '</td>';
            echo '<td>' . htmlspecialchars($bonPlan['restaurant']) . '</td>';
            echo '<td>' . htmlspecialchars($bonPlan['hotel']) . '</td>';
            echo '<td>' . htmlspecialchars($bonPlan['dateCreation']) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</html>';
        exit;

    } elseif ($format === 'pdf') {
        // Inclure TCPDF
        require('C:/xampp/htdocs/localo/TCPDF-main/TCPDF-main/tcpdf.php');

        // Créer une nouvelle instance de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Localo');
        $pdf->SetTitle('Export des Bon Plans');
        $pdf->SetSubject('Bon Plans');
        $pdf->SetKeywords('Bon Plans, Export, PDF');
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // Contenu HTML pour le PDF
        $html = '<h2>Export des Bon Plans</h2>';
        $html .= '<table border="1" cellpadding="4">';
        $html .= '<tr style="background-color:#0ABAB5;color:white;">
                    <th>ID</th>
                    <th>Destination</th>
                    <th>Restaurant</th>
                    <th>Hotel</th>
                    <th>Date de création</th>
                  </tr>';

        foreach ($listBonPlans as $bonPlan) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($bonPlan['idBonplan']) . '</td>';
            $html .= '<td>' . htmlspecialchars($bonPlan['destination']) . '</td>';
            $html .= '<td>' . htmlspecialchars($bonPlan['restaurant']) . '</td>';
            $html .= '<td>' . htmlspecialchars($bonPlan['hotel']) . '</td>';
            $html .= '<td>' . htmlspecialchars($bonPlan['dateCreation']) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        // Écrire le HTML dans le PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('bonplans-' . date('Y-m-d') . '.pdf', 'D');
        exit;

    } else {
        // Format non supporté
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Format non supporté']);
        exit;
    }

} catch (Exception $e) {
    // Gestion des erreurs
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Erreur d\'export : ' . $e->getMessage()]);
    exit;
}
?>