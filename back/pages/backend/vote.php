<?php
session_start();
header('Content-Type: application/json');
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non authentifié']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'] ?? null;
$vote_type = $data['vote_type'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$post_id || !in_array($vote_type, ['up', 'down'])) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

// Vérifier si l'utilisateur a déjà voté sur ce post
$stmt = $pdo->prepare("SELECT id FROM votes WHERE user_id = ? AND post_id = ?");
$stmt->execute([$user_id, $post_id]);
$existingVote = $stmt->fetch();

if ($existingVote) {
    // Mise à jour du vote existant
    $stmt = $pdo->prepare("UPDATE votes SET vote_type = ? WHERE id = ?");
    $stmt->execute([$vote_type, $existingVote['id']]);
} else {
    // Création d'un nouveau vote
    $stmt = $pdo->prepare("INSERT INTO votes (user_id, post_id, vote_type) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $post_id, $vote_type]);
}
echo json_encode(['success'=>true]);
?>
