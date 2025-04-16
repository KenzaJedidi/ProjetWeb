<?php
session_start();
require 'config.php';
header('Content-Type: application/json');

$post_id = $_POST['post_id'] ?? null;
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';

if (!$post_id || empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'error' => 'Champs manquants']);
    exit;
}

// Vérifier que l'utilisateur est le propriétaire du post
$stmt = $pdo->prepare("SELECT user_id, image FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post || $post['user_id'] != $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'error' => 'Accès refusé']);
    exit;
}

// Image : gérer le remplacement
$imageFileName = $post['image']; // ancienne image conservée par défaut

if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $imageName = time() . '_' . uniqid() . '_' . $_FILES['image']['name'];
    $imagePath = '../uploads/' . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        // Supprimer l’ancienne image si elle existe
        if (!empty($imageFileName) && file_exists('../uploads/' . $imageFileName)) {
            unlink('../uploads/' . $imageFileName);
        }
        $imageFileName = $imageName;
    }
}

$update = $pdo->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
$success = $update->execute([$title, $content, $imageFileName, $post_id]);

echo json_encode(['success' => $success]);
