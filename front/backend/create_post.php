<?php
// backend/create_post.php
session_start();
require 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non authentifié']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    $image   = null;

    // Gestion de l'upload d'image (si présente)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Extraction de l'extension du fichier
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        // Générer un nom unique pour éviter les collisions
        $filename = time() . '_' . uniqid() . '.' . $extension;
        
        // Chemins absolus
        $frontUploadDir = __DIR__ . '/../uploads/';
$backUploadDir  = __DIR__ . '/../../back/pages/uploads/';
       // Dossier côté back
        
        // Chemins complets du fichier
        $frontPath = $frontUploadDir . $filename;
        $backPath  = $backUploadDir  . $filename;
        
        // Déplacer le fichier vers le dossier front
        if (move_uploaded_file($_FILES['image']['tmp_name'], $frontPath)) {
            // Copier le fichier du dossier front vers le dossier back
            if (copy($frontPath, $backPath)) {
                $image = $filename;
            } else {
                echo json_encode(['error' => "Erreur lors de la copie de l'image vers le dossier backend."]);
                exit;
            }
        } else {
            echo json_encode(['error' => "Erreur lors du déplacement du fichier uploadé."]);
            exit;
        }
    }

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, image, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($stmt->execute([$user_id, $title, $content, $image])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Erreur lors de la création du post']);
    }
}
?>
