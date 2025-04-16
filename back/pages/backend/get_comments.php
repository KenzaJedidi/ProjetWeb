<?php
// backend/get_comments.php
require 'config.php';

header('Content-Type: application/json');

$post_id = $_GET['post_id'] ?? null;
if (!$post_id) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
  SELECT c.*, u.username 
  FROM comments c
  LEFT JOIN users u ON c.user_id = u.id
  WHERE c.post_id = ?
  ORDER BY c.created_at ASC
");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll();
echo json_encode($comments);
?>
