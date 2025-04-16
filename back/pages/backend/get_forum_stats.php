<?php
// backend/get_forum_stats.php
require 'config.php';
header('Content-Type: application/json');

// Nombre d'utilisateurs
$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$users = $stmt->fetch();

// Nombre de posts
$stmt2 = $pdo->query("SELECT COUNT(*) as total_posts FROM posts");
$posts = $stmt2->fetch();

// Nombre de commentaires
$stmt3 = $pdo->query("SELECT COUNT(*) as total_comments FROM comments");
$comments = $stmt3->fetch();

echo json_encode([
  'members' => $users['total_users'],
  'posts' => $posts['total_posts'],
  'comments' => $comments['total_comments']
]);
?>
