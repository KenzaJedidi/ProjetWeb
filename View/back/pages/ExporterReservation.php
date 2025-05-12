<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure les contrôleurs
include '../../../Controller/ReservationC.php';
include '../../../Controller/BonPlanC.php';

try {
    // Instancier les contrôleurs
    $reservationC = new ReservationC();
    $bonPlanC = new BonPlanC();
    $listReservations = $reservationC->AfficherReservation();

    // Récupérer le format demandé via GET, par défaut 'csv'
    $format = $_GET['format'] ?? 'csv';

    if ($format === 'csv') {
        // En-têtes pour CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reservations-' . date('Y-m-d') . '.csv');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Ouvrir la sortie en mode écriture
        $output = fopen('php://output', 'w');

        // Écrire les en-têtes de colonnes
        fputcsv($output, [
            'ID', 'Destination', 'Date Depart', 'Date Retour',
            'Nombre de Personnes', 'Commentaire', 'Statut', 'Date de Création'
        ]);

        // Écrire les lignes
        foreach ($listReservations as $res) {
            // Récupérer la destination depuis BonPlan
            $destination = 'N/A';
            if (!empty($res['idBonPlan'])) {
                $bonPlan = $bonPlanC->RecupererBonPlan($res['idBonPlan']);
                if ($bonPlan && isset($bonPlan['destination'])) {
                    $destination = $bonPlan['destination'];
                }
            }

            fputcsv($output, [
                $res['idReservation'],
                $destination,
                $res['dateDepart'],
                $res['dateRetour'],
                $res['nbPersonne'], // Corrigé de nbPersonnes à nbPersonne
                $res['commentaire'],
                $res['statut'],
                $res['dateCreation']
            ]);
        }

        fclose($output);
        exit;

    } elseif ($format === 'excel') {
        // En-têtes pour Excel
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename=reservations-' . date('Y-m-d') . '.xls');
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
                <th>Date Depart</th>
                <th>Date Retour</th>
                <th>Nombre de Personnes</th>
                <th>Commentaire</th>
                <th>Statut</th>
                <th>Date de Création</th>
              </tr>';

        foreach ($listReservations as $res) {
            // Récupérer la destination depuis BonPlan
            $destination = 'N/A';
            if (!empty($res['idBonPlan'])) {
                $bonPlan = $bonPlanC->RecupererBonPlan($res['idBonPlan']);
                if ($bonPlan && isset($bonPlan['destination'])) {
                    $destination = $bonPlan['destination'];
                }
            }

            echo '<tr>';
            echo '<td>' . htmlspecialchars($res['idReservation'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($destination) . '</td>';
            echo '<td>' . htmlspecialchars($res['dateDepart'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($res['dateRetour'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($res['nbPersonne'] ?? '') . '</td>'; // Corrigé
            echo '<td>' . htmlspecialchars($res['commentaire'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($res['statut'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($res['dateCreation'] ?? '') . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</html>';
        exit;

    } elseif ($format === 'pdf') {
        // Activer le buffer de sortie pour éviter tout affichage parasite
        ob_start();

        // Inclure TCPDF
        require('C:/xampp/htdocs/localo/TCPDF-main/TCPDF-main/tcpdf.php');

        // Créer une nouvelle instance de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Localo');
        $pdf->SetTitle('Export des Réservations');
        $pdf->SetSubject('Réservations');
        $pdf->SetKeywords('Réservations, Export, PDF');
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // Contenu HTML pour le PDF
        $html = '<h2>Export des Réservations</h2>';
        $html .= '<table border="1" cellpadding="4">';
        $html .= '<tr style="background-color:#0ABAB5;color:white;">
                    <th>ID</th>
                    <th>Destination</th>
                    <th>Date Depart</th>
                    <th>Date Retour</th>
                    <th>Nombre de Personnes</th>
                    <th>Commentaire</th>
                    <th>Statut</th>
                    <th>Date de Création</th>
                  </tr>';

        foreach ($listReservations as $res) {
            // Récupérer la destination depuis BonPlan
            $destination = 'N/A';
            if (!empty($res['idBonPlan'])) {
                $bonPlan = $bonPlanC->RecupererBonPlan($res['idBonPlan']);
                if ($bonPlan && isset($bonPlan['destination'])) {
                    $destination = $bonPlan['destination'];
                }
            }

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($res['idReservation'] ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($destination) . '</td>';
            $html .= '<td>' . htmlspecialchars($res['dateDepart'] ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($res['dateRetour'] ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($res['nbPersonne'] ?? '') . '</td>'; // Corrigé
            $html .= '<td>' . htmlspecialchars($res['commentaire'] ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($res['statut'] ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($res['dateCreation'] ?? '') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        // Écrire le HTML dans le PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Vider le buffer de sortie
        ob_end_clean();

        // Envoyer le PDF
        $pdf->Output('reservations-' . date('Y-m-d') . '.pdf', 'D');
        exit;

    } else {
        // Format non supporté
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Format non supporté']);
        exit;
    }

} catch (Exception $e) {
    // Nettoyer le buffer en cas d'erreur
    if (ob_get_length()) {
        ob_end_clean();
    }
    // Gestion des erreurs
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Erreur d\'export : ' . $e->getMessage()]);
    exit;
}
?>