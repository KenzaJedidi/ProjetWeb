<?php
// Activation du rapport d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Inclusions nécessaires
require_once __DIR__.'/../../Controller/CommentC.php';
require_once __DIR__.'/../../Controller/PostC.php';
require_once __DIR__.'/../../Model/Comment.php';

// Définition du header JSON
header('Content-Type: application/json');

// Vérification de la méthode HTTP
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée. Seules les requêtes POST sont acceptées.'
    ]);
    exit;
}

try {
    // Vérification de l'action demandée
    if (!isset($_POST['action'])) {
        throw new Exception('Action non spécifiée');
    }

    $action = $_POST['action'];
    $commentC = new CommentC();
    $postC = new PostC();

    switch ($action) {
        case 'add':
            // Validation des champs pour l'ajout
            if (!isset($_POST['post_id']) || !isset($_POST['content'])) {
                throw new Exception('Tous les champs sont requis pour l\'ajout');
            }

            $post_id = (int)$_POST['post_id'];
            $content = trim($_POST['content']);

            if (empty($content)) {
                throw new Exception('Le commentaire ne peut pas être vide');
            }

            // Vérification de l'existence du post
            $post = $postC->RecupererPost($post_id);
            if (!$post) {
                throw new Exception('Le post spécifié n\'existe pas');
            }

            // Création et ajout du commentaire
            $comment = new Comment(1, $post_id, $content); // 1 = user_id temporaire
            $result = $commentC->AjouterComment($comment);

            if ($result) {
                // Récupérer le dernier commentaire ajouté
                $lastComment = $commentC->AfficherCommentairesParPost($post_id)[0] ?? null;

                http_response_code(200); // ✅ Statut HTTP OK
                echo json_encode([
                    'success' => true,
                    'message' => 'Commentaire ajouté avec succès',
                    'action' => 'add',
                    'comment' => [
                        'id' => $lastComment ? $lastComment['id'] : null,
                        'content' => htmlspecialchars($content),
                        'created_at' => $lastComment ? $lastComment['created_at'] : date('d/m/Y H:i')
                    ]
                ]);
            } else {
                throw new Exception('Échec de l\'ajout du commentaire');
            }
            break;

        case 'edit':
            // Validation des champs pour la modification
            if (!isset($_POST['comment_id']) || !isset($_POST['content'])) {
                throw new Exception('Tous les champs sont requis pour la modification');
            }

            $comment_id = (int)$_POST['comment_id'];
            $content = trim($_POST['content']);

            if (empty($content)) {
                throw new Exception('Le commentaire ne peut pas être vide');
            }

            // Récupération et mise à jour du commentaire
            $comment = $commentC->RecupererComment($comment_id);
            if (!$comment) {
                throw new Exception('Commentaire introuvable');
            }

            $comment['content'] = $content;
            $result = $commentC->ModifierComment(new Comment(
                $comment['user_id'],
                $comment['post_id'],
                $comment['content']
            ), $comment_id);

            if ($result) {
                http_response_code(200); // ✅ Statut HTTP OK
                echo json_encode([
                    'success' => true,
                    'message' => 'Commentaire modifié avec succès',
                    'action' => 'edit',
                    'comment_id' => $comment_id,
                    'content' => htmlspecialchars($content)
                ]);
            } else {
                throw new Exception('Échec de la modification du commentaire');
            }
            break;

        case 'delete':
            // Validation des champs pour la suppression
            if (!isset($_POST['comment_id'])) {
                throw new Exception('ID du commentaire manquant');
            }

            $comment_id = (int)$_POST['comment_id'];
            $result = $commentC->SupprimerComment($comment_id);

            if ($result) {
                http_response_code(200); // ✅ Statut HTTP OK
                echo json_encode([
                    'success' => true,
                    'message' => 'Commentaire supprimé avec succès',
                    'action' => 'delete',
                    'comment_id' => $comment_id
                ]);
            } else {
                throw new Exception('Échec de la suppression du commentaire');
            }
            break;

        default:
            throw new Exception('Action non reconnue');
    }

} catch (PDOException $e) {
    // Erreur de base de données
    error_log("Database error in store_comment.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données. Veuillez réessayer plus tard.'
    ]);
} catch (Exception $e) {
    // Erreur de validation ou autre
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
