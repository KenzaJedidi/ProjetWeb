<?php
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../Model/Comment.php';

class CommentController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    // Créer un nouveau commentaire
    public function createComment($commentData) {
        try {
            $comment = new Comment(
                $commentData['user_id'],
                $commentData['post_id'],
                $commentData['content']
            );

            $sql = "INSERT INTO comments (user_id, post_id, content, created_at) 
                    VALUES (:user_id, :post_id, :content, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_id' => $comment->getUserId(),
                'post_id' => $comment->getPostId(),
                'content' => $comment->getContent()
            ]);

            $comment->setId($this->db->lastInsertId());
            return $comment;

        } catch (Exception $e) {
            error_log("Error creating comment: " . $e->getMessage());
            throw $e;
        }
    }

    // Récupérer tous les commentaires d'un post
    public function getCommentsByPost($postId) {
        try {
            $sql = "SELECT c.*, u.username 
                    FROM comments c
                    LEFT JOIN users u ON c.user_id = u.id
                    WHERE c.post_id = :post_id
                    ORDER BY c.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['post_id' => $postId]);
            $commentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $comments = [];
            foreach ($commentsData as $commentData) {
                $comments[] = Comment::fromArray($commentData);
            }

            return $comments;

        } catch (Exception $e) {
            error_log("Error fetching comments: " . $e->getMessage());
            throw $e;
        }
    }

    // Récupérer un commentaire par son ID
    public function getCommentById($commentId) {
        try {
            $sql = "SELECT c.*, u.username 
                    FROM comments c
                    LEFT JOIN users u ON c.user_id = u.id
                    WHERE c.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $commentId]);
            $commentData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$commentData) {
                return null;
            }

            return Comment::fromArray($commentData);

        } catch (Exception $e) {
            error_log("Error fetching comment: " . $e->getMessage());
            throw $e;
        }
    }

    // Mettre à jour un commentaire
    public function updateComment($commentId, $content) {
        try {
            $sql = "UPDATE comments SET 
                    content = :content 
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                'content' => $content,
                'id' => $commentId
            ]);

            if ($success) {
                return $this->getCommentById($commentId);
            }

            return false;

        } catch (Exception $e) {
            error_log("Error updating comment: " . $e->getMessage());
            throw $e;
        }
    }

    // Supprimer un commentaire
    public function deleteComment($commentId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
            return $stmt->execute([$commentId]);

        } catch (Exception $e) {
            error_log("Error deleting comment: " . $e->getMessage());
            throw $e;
        }
    }

    // Vérifier si l'utilisateur est propriétaire du commentaire
    public function isCommentOwner($commentId, $userId) {
        try {
            $comment = $this->getCommentById($commentId);
            if (!$comment) {
                return false;
            }

            return $comment->getUserId() == $userId;

        } catch (Exception $e) {
            error_log("Error checking comment ownership: " . $e->getMessage());
            return false;
        }
    }

    // Récupérer le nombre de commentaires pour un post
    public function getCommentCountForPost($postId) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM comments WHERE post_id = ?");
            $stmt->execute([$postId]);
            $result = $stmt->fetch();
            return $result['count'];

        } catch (Exception $e) {
            error_log("Error counting comments: " . $e->getMessage());
            return 0;
        }
    }
}
?>