<?php
// backend/create_post.php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non authentifié']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    $image   = null;

    // Gestion de l'upload d'image si présente
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';  // Assure-toi que le dossier uploads existe et est accessible
        $filename  = time() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $image = $filename;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $title, $content, $image])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Erreur lors de la création du post']);
    }
}
?>
