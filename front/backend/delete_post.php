<?php
session_start();
require 'config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['post_id'] ?? null;

if (!$post_id) {
  echo json_encode(['error' => 'Post ID manquant']);
  exit;
}

// Vérifier que le post appartient à l'utilisateur connecté
$stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post || $post['user_id'] !== $_SESSION['user_id']) {
  echo json_encode(['error' => 'Accès refusé']);
  exit;
}

// Supprimer les commentaires liés
$pdo->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$post_id]);

// Supprimer les votes liés
$pdo->prepare("DELETE FROM votes WHERE post_id = ?")->execute([$post_id]);

// Supprimer le post
$pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$post_id]);

echo json_encode(['success' => true]);
?>
