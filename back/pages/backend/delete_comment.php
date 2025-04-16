<?php
header('Content-Type: application/json');
require 'config.php';

// Lire les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que l'ID du commentaire est bien fourni
if (!isset($data['comment_id']) || empty($data['comment_id'])) {
    echo json_encode(['success' => false, 'error' => 'Comment ID manquant']);
    exit;
}

$comment_id = intval($data['comment_id']);

// Supprimer le commentaire
$stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
if ($stmt->execute([$comment_id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression']);
}
