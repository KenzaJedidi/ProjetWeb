<?php
session_start();
require_once '../../Controller/PostC.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté.', 'redirect' => 'login.php']);
    exit;
}

// Vérifier si les données du formulaire sont présentes
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';
$metier = isset($_POST['metier']) ? trim($_POST['metier']) : '';

// Validation de base
if ($postId <= 0 || empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Titre et contenu sont obligatoires.']);
    exit;
}

$postC = new PostC();

// Gestion de l'image
$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../back/pages/uploads/';
    $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
    $targetPath = $uploadDir . $fileName;

    // Vérifier le type et la taille du fichier
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5 Mo

    if (!in_array($_FILES['image']['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Type de fichier non autorisé.']);
        exit;
    }

    if ($_FILES['image']['size'] > $maxFileSize) {
        echo json_encode(['success' => false, 'message' => 'Fichier trop volumineux.']);
        exit;
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $imagePath = $fileName;
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors du téléchargement de l\'image.']);
        exit;
    }
}

try {
    // Mise à jour du post
    $result = $postC->ModifierPost([
        'id' => $postId,
        'title' => $title,
        'content' => $content,
        'metier' => $metier,
        'image' => $imagePath // Peut être null si aucune nouvelle image n'est téléchargée
    ]);

    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'Post modifié avec succès.', 
            'redirect' => "post_info.php?id={$postId}"
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification du post.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue: ' . $e->getMessage()]);
}
?>
