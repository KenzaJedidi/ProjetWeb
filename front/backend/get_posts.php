<?php
// backend/get_posts.php
require 'config.php';
header('Content-Type: application/json');

// Récupérer le paramètre de recherche
$search = $_GET['search'] ?? '';
$search = "%$search%";

// Requête pour récupérer les posts contenant le mot-clé dans le titre ou le contenu
$stmt = $pdo->prepare("
  SELECT p.*,
         IFNULL((SELECT SUM(IF(v.vote_type = 'up', 1, -1)) FROM votes v WHERE v.post_id = p.id), 0) AS votes,
         (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) as comment_count,
         u.username
  FROM posts p
  LEFT JOIN users u ON p.user_id = u.id
  WHERE p.title LIKE :search
     OR p.content LIKE :search
  ORDER BY p.created_at DESC
");
$stmt->execute(['search' => $search]);
$posts = $stmt->fetchAll();

echo json_encode($posts);
?>
