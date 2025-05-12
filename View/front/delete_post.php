<?php
include_once '../../Controller/PostC.php';
include_once '../../Controller/CommentC.php';

$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

header('Content-Type: application/json');

if ($postId <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de post invalide']);
    exit;
}

try {
    $postC = new PostC();
    $commentC = new CommentC();
    
    // D'abord supprimer les commentaires associés
    $commentC->SupprimerCommentairesParPost($postId);
    
    // Puis supprimer le post
    $result = $postC->SupprimerPost($postId);
    
    if ($result) {
        // Retourne une réponse JSON avec l'URL de redirection
        echo json_encode([
            'success' => true, 
            'message' => 'Post supprimé avec succès',
            'redirect' => 'front.php' // Ajout de l'URL de redirection
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Échec de la suppression du post']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}