<?php
// Au début du fichier, après la connexion
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$query = "SELECT * FROM reservation WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (nom LIKE ? OR prenom LIKE ? OR destination LIKE ?)";
    $searchTerm = "%$search%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
}

if (!empty($statusFilter)) {
    $query .= " AND statut = ?";
    $params[] = $statusFilter;
}

$query .= " ORDER BY dte DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>