<?php
// backend/add_comment.php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non authentifié']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $post_id, $content])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Erreur lors de l\'ajout du commentaire']);
    }
}
?>
