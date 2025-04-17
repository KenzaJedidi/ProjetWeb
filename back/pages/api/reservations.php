<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config.php';

try {
    // Récupérer les réservations
    $stmt = config::getConnexion()->query("
        SELECT r.*, d.nom AS destination_nom 
        FROM reservations r
        JOIN destinations d ON r.destination_id = d.id
        ORDER BY r.date_depart DESC
    ");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculer les statistiques
    $stats = [
        'actives' => config::getConnexion()->query("SELECT COUNT(*) FROM reservations WHERE statut = 'active'")->fetchColumn(),
        'en_attente' => config::getConnexion()->query("SELECT COUNT(*) FROM reservations WHERE statut = 'en_attente'")->fetchColumn(),
        'destinations' => config::getConnexion()->query("SELECT COUNT(DISTINCT destination_id) FROM reservations")->fetchColumn(),
        'total' => config::getConnexion()->query("SELECT COUNT(*) FROM reservations")->fetchColumn()
    ];
    
    echo json_encode([
        'success' => true,
        'reservations' => $reservations,
        'stats' => $stats
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}