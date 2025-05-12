<?php
class Post {
    private $id;
    private $user_id;
    private $title;
    private $content;
    private $image;
    private $created_at;
    private $metier;
    private $reactions = [];

    public function __construct($user_id, $title, $content, $image, $metier = null) {
        $this->user_id = $user_id;
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->metier = $metier;
    }

    // Reaction-related properties
    private $reaction_id;
    private $reaction_user_id;
    private $reaction_post_id;
    private $reaction_type;
    private $reaction_created_at;

    // Reaction Getters
    public function getReactionId() {
        return $this->reaction_id;
    }

    public function getReactionUserId() {
        return $this->reaction_user_id;
    }

    public function getReactionPostId() {
        return $this->reaction_post_id;
    }

    public function getReactionType() {
        return $this->reaction_type;
    }

    public function getReactionCreatedAt() {
        return $this->reaction_created_at;
    }

    // Reaction Setters
    public function setReactionId($reaction_id) {
        $this->reaction_id = $reaction_id;
    }

    public function setReactionUserId($reaction_user_id) {
        $this->reaction_user_id = $reaction_user_id;
    }

    public function setReactionPostId($reaction_post_id) {
        $this->reaction_post_id = $reaction_post_id;
    }

    public function setReactionType($reaction_type) {
        $this->reaction_type = $reaction_type;
    }

    public function setReactionCreatedAt($reaction_created_at) {
        $this->reaction_created_at = $reaction_created_at;
    }

    public function getReactions() {
        try {
            $pdo = Config::GetConnexion();
            $stmt = $pdo->prepare("SELECT reaction_type, COUNT(*) as count FROM reactions WHERE post_id = ? GROUP BY reaction_type");
            $stmt->execute([$this->id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get Reactions Error: ' . $e->getMessage());
            return [];
        }
    }

    public function removeReaction($userId, $reactionType) {
        try {
            $pdo = Config::GetConnexion();
            $stmt = $pdo->prepare("DELETE FROM reactions WHERE user_id = ? AND post_id = ? AND reaction_type = ?");
            return $stmt->execute([$userId, $this->id, $reactionType]);
        } catch (PDOException $e) {
            error_log('Remove Reaction Error: ' . $e->getMessage());
            return false;
        }
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getImage() {
        return $this->image;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getMetier() {
        return $this->metier;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    public function setMetier($metier) {
        $this->metier = $metier;
    }

    function AjouterReaction($postId, $userId, $reactionType) {
        try {
            // Validation des paramètres
            if (!$postId || !$userId || !$reactionType) {
                return json_encode([
                    'success' => false, 
                    'message' => 'Paramètres invalides'
                ]);
            }
    
            // Types de réactions valides
            $validReactions = ['like', 'love', 'wow', 'sad', 'angry'];
            if (!in_array($reactionType, $validReactions)) {
                return json_encode([
                    'success' => false, 
                    'message' => 'Type de réaction invalide'
                ]);
            }
    
            // Connexion à la base de données
            $pdo = Config::GetConnexion();
            $pdo->beginTransaction();
    
            try {
                // Vérifier si l'utilisateur a déjà réagi au post
                $stmt = $pdo->prepare("SELECT id, reaction_type FROM reactions WHERE user_id = ? AND post_id = ?");
                $stmt->execute([$userId, $postId]);
                $existingReaction = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($existingReaction) {
                    // Si la réaction est la même, la supprimer (toggle)
                    if ($existingReaction['reaction_type'] === $reactionType) {
                        $stmt = $pdo->prepare("DELETE FROM reactions WHERE id = ?");
                        $stmt->execute([$existingReaction['id']]);
                        $pdo->commit();
                        $response = [
                            'success' => true, 
                            'message' => 'Réaction supprimée',
                            'status' => 'removed'
                        ];
                    } else {
                        // Sinon, mettre à jour la réaction existante
                        $stmt = $pdo->prepare("UPDATE reactions SET reaction_type = ?, created_at = NOW() WHERE id = ?");
                        $stmt->execute([$reactionType, $existingReaction['id']]);
                        $pdo->commit();
                        $response = [
                            'success' => true, 
                            'message' => 'Réaction mise à jour',
                            'status' => 'updated'
                        ];
                    }
                } else {
                    // Insérer la nouvelle réaction
                    $stmt = $pdo->prepare("INSERT INTO reactions (user_id, post_id, reaction_type, created_at) VALUES (?, ?, ?, NOW())");
                    $result = $stmt->execute([$userId, $postId, $reactionType]);
    
                    if ($result) {
                        $pdo->commit();
                        $response = [
                            'success' => true, 
                            'message' => 'Réaction ajoutée',
                            'status' => 'added'
                        ];
                    } else {
                        $pdo->rollBack();
                        $response = [
                            'success' => false, 
                            'message' => 'Échec de l\'ajout de la réaction'
                        ];
                    }
                }
    
                // Compter le nombre total de réactions pour ce post
                $stmt = $pdo->prepare("SELECT COUNT(*) as total_reactions FROM reactions WHERE post_id = ?");
                $stmt->execute([$postId]);
                $reactionCount = $stmt->fetch(PDO::FETCH_ASSOC)['total_reactions'];
                $response['total_reactions'] = $reactionCount;
    
                // Compter les réactions par type
                $stmt = $pdo->prepare("SELECT reaction_type, COUNT(*) as count FROM reactions WHERE post_id = ? GROUP BY reaction_type");
                $stmt->execute([$postId]);
                $reactionTypeCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $response['reaction_counts'] = array_column($reactionTypeCounts, 'count', 'reaction_type');
    
                return json_encode($response);
    
            } catch (PDOException $e) {
                $pdo->rollBack();
                error_log('Reaction Transaction Error: ' . $e->getMessage());
                return json_encode([
                    'success' => false, 
                    'message' => $e->getMessage()
                ]);
            }
        } catch (Exception $e) {
            error_log('Erreur AjouterReaction: ' . $e->getMessage());
            return json_encode([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }
     public function getReactionsByType($reactionType = null, $userId = null) {
        try {
            $pdo = Config::GetConnexion();
            
            if ($reactionType) {
                // Si un type de réaction est spécifié
                $stmt = $pdo->prepare("SELECT * FROM reactions WHERE post_id = ? AND reaction_type = ?");
                $stmt->execute([$this->id, $reactionType]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif ($userId) {
                // Si un utilisateur est spécifié, retourner sa réaction
                $stmt = $pdo->prepare("SELECT reaction_type FROM reactions WHERE post_id = ? AND user_id = ?");
                $stmt->execute([$this->id, $userId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result ? $result['reaction_type'] : null;
            } else {
                // Sinon, compter les réactions par type
                $stmt = $pdo->prepare("SELECT reaction_type, COUNT(*) as count FROM reactions WHERE post_id = ? GROUP BY reaction_type");
                $stmt->execute([$this->id]);
                $reactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Initialiser tous les types de réactions à 0
                $allReactions = [
                    ['reaction_type' => 'like', 'count' => 0],
                    ['reaction_type' => 'love', 'count' => 0],
                    ['reaction_type' => 'haha', 'count' => 0],
                    ['reaction_type' => 'wow', 'count' => 0],
                    ['reaction_type' => 'sad', 'count' => 0],
                    ['reaction_type' => 'angry', 'count' => 0]
                ];
                
                // Mettre à jour les compteurs réels
                foreach ($reactions as $reaction) {
                    foreach ($allReactions as &$defaultReaction) {
                        if ($defaultReaction['reaction_type'] === $reaction['reaction_type']) {
                            $defaultReaction['count'] = $reaction['count'];
                            break;
                        }
                    }
                }
                
                return $allReactions;
            }
        } catch (PDOException $e) {
            error_log('Get Reactions Error: ' . $e->getMessage());
            return [];
        }
    }

    public function getUserReaction($pdo, $userId, $reactionType) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM reactions WHERE user_id = ? AND post_id = ? AND reaction_type = ?");
            $stmt->execute([$userId, $this->id, $reactionType]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error or handle as needed
            error_log('Get User Reaction Error: ' . $e->getMessage());
            return null;
        }
    }


}
?>