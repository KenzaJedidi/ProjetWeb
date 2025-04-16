<?php
// backend/get_post_detail.php
session_start();
require 'config.php';

header('Content-Type: application/json');

$post_id = $_GET['post_id'] ?? null;

if (!$post_id) {
    echo json_encode(['error' => 'post_id requis']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, u.username,
           IFNULL((SELECT SUM(IF(v.vote_type = 'up', 1, -1)) FROM votes v WHERE v.post_id = p.id), 0) AS votes
    FROM posts p
    LEFT JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if ($post) {
    // Vous pouvez ajouter ici le calcul du nombre de commentaires si nécessaire, par exemple :
    $stmt2 = $pdo->prepare("SELECT COUNT(*) as comment_count FROM comments WHERE post_id = ?");
    $stmt2->execute([$post_id]);
    $commentData = $stmt2->fetch();
    $post['comment_count'] = $commentData['comment_count'] ?? 0;
    
    echo json_encode($post);
} else {
    echo json_encode(['error' => 'Post non trouvé']);
}
?>
