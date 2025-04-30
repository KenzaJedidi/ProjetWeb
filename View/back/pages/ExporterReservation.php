<?php
// Aucune sortie HTML ou espace avant ce code !
include '../../../Controller/ReservationC.php'; // Chemin correct vers le contrôleur

$reservationC = new ReservationC();
$listReservations = $reservationC->AfficherReservation(); // Vérifie que cette méthode existe

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reservations.csv');

$output = fopen('php://output', 'w');

fputcsv($output, array(
    'ID', 'DESTINATION', 'DATE DEPART', 'DATE RETOUR',
    'NOMBRE DE PERSONNES', 'COMMENTAIRE', 'STATUT', 'DATE CREATION'
));

foreach ($listReservations as $res) {
    fputcsv($output, array(
        $res['idReservation'],
        $res['destination'],
        $res['dateDepart'],
        $res['dateRetour'],
        $res['nbPersonnes'],
        $res['commentaire'],
        $res['statut'],
        $res['dateCreation']
    ));
}

fclose($output);
exit;
?>
