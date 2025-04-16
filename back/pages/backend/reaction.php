<?php
// backend/reaction.php
session_start();
require 'config.php';

header('Content-Type: application/json');

// Récupération des données JSON de la requête POST
$data = json_decode(file_get_contents('php://input'), true); // <- le true est crucial ici

file_put_contents('debug_reaction.log', print_r($data, true));

$post_id = $data['post_id'] ?? null;
$reaction_type = $data['reaction_type'] ?? '';
$user_id = $_SESSION['user_id'];

// Vérifier que toutes les données nécessaires sont présentes
if (!$post_id || !$reaction_type || !$user_id) {
    echo json_encode(['error' => 'Les données sont manquantes']);
    exit;
}

// Vérifier si une réaction existe déjà pour ce post et cet utilisateur
$stmt = $pdo->prepare("SELECT id, reaction_type FROM reactions WHERE post_id = ? AND user_id = ?");
$stmt->execute([$post_id, $user_id]);
$existing = $stmt->fetch();

if ($existing) {
    // Si la même réaction a été envoyée, la supprimer (désactiver)
    if ($existing['reaction_type'] === $reaction_type) {
        $stmt = $pdo->prepare("DELETE FROM reactions WHERE id = ?");
        $stmt->execute([$existing['id']]);
        echo json_encode(['success' => true, 'message' => 'Reaction removed']);
        exit;
    } else {
        // Si c'est une réaction différente, mettre à jour la réaction existante
        $stmt = $pdo->prepare("UPDATE reactions SET reaction_type = ?, created_at = NOW() WHERE id = ?");
        $stmt->execute([$reaction_type, $existing['id']]);
        echo json_encode(['success' => true, 'message' => 'Reaction updated']);
        exit;
    }
} else {
    // Insérer une nouvelle réaction
    $stmt = $pdo->prepare("INSERT INTO reactions (post_id, user_id, reaction_type, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$post_id, $user_id, $reaction_type]);
    echo json_encode(['success' => true, 'message' => 'Reaction added']);
    exit;
}
?>
