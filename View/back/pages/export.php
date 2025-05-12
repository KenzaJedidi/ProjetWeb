<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'oussema';
$username = 'root';
$password = '';

try {
    // Connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL
    $stmt = $pdo->query("
        SELECT e.*, c.nom AS categorie_name 
        FROM evenements e 
        LEFT JOIN categorie c ON e.categorie_id = c.id
    ");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gestion du format
    $format = $_GET['format'] ?? 'excel';

    if($format === 'excel') {
        // En-têtes Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=export-evenements-".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // Début du contenu
        echo '<html>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        echo '<table border="1">';
        echo '<tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Ville</th>
                <th>Dates</th>
                <th>Catégorie</th>
                <th>Participants</th>
                <th>Statut</th>
              </tr>';

        foreach($data as $row) {
            echo '<tr>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.htmlspecialchars($row['titre']).'</td>';
            echo '<td>'.htmlspecialchars($row['ville']).'</td>';
            echo '<td>'.$row['date_debut'].' - '.$row['date_fin'].'</td>';
            echo '<td>'.htmlspecialchars($row['categorie_name']).'</td>';
            echo '<td>'.$row['participants'].'</td>';
            echo '<td>'.$row['statut'].'</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        echo '</html>';
        exit;

    } elseif($format === 'pdf') {
        require('C:/xampp/htdocs/web/TCPDF-main/TCPDF-main/tcpdf.php');
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $html = '<h2>Export des Événements</h2>';
        $html .= '<table border="1" cellpadding="4">';
        $html .= '<tr style="background-color:#0ABAB5;color:white;">
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Ville</th>
                    <th>Dates</th>
                    <th>Catégorie</th>
                    <th>Participants</th>
                    <th>Statut</th>
                  </tr>';

        foreach($data as $row) {
            $html .= '<tr>';
            $html .= '<td>'.$row['id'].'</td>';
            $html .= '<td>'.htmlspecialchars($row['titre']).'</td>';
            $html .= '<td>'.htmlspecialchars($row['ville']).'</td>';
            $html .= '<td>'.$row['date_debut'].' - '.$row['date_fin'].'</td>';
            $html .= '<td>'.htmlspecialchars($row['categorie_name']).'</td>';
            $html .= '<td>'.$row['participants'].'</td>';
            $html .= '<td>'.$row['statut'].'</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('export-evenements.pdf', 'D');
        exit;
    }

} catch(PDOException $e) {
    die("Erreur d'export : " . $e->getMessage());
}