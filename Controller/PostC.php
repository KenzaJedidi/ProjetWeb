<?php
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../Model/Post.php';

class PostController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    // Créer un nouveau post
    public function createPost($postData, $imageFile = null) {
        try {
            $imagePath = null;
            
            // Gestion de l'image
            if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
                $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $uploadPath = __DIR__ . '/../uploads/' . $filename;

                if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                    $imagePath = $filename;
                } else {
                    throw new Exception("Failed to upload image");
                }
            }

            // Création du post
            $post = new Post(
                $postData['user_id'],
                $postData['title'],
                $postData['content'],
                $imagePath
            );

            // Insertion en base
            $sql = "INSERT INTO posts (user_id, title, content, image, created_at) 
                    VALUES (:user_id, :title, :content, :image, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_id' => $post->getUserId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'image' => $post->getImage()
            ]);

            $post->setId($this->db->lastInsertId());
            return $post;

        } catch (Exception $e) {
            error_log("Error creating post: " . $e->getMessage());
            throw $e;
        }
    }

    // Récupérer tous les posts
    public function getAllPosts($limit = null, $offset = null, $search = '') {
        try {
            $sql = "SELECT p.*, u.username, 
                   (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count,
                   (SELECT COUNT(*) FROM votes WHERE post_id = p.id AND vote_type = 'up') - 
                   (SELECT COUNT(*) FROM votes WHERE post_id = p.id AND vote_type = 'down') as votes
                   FROM posts p
                   LEFT JOIN users u ON p.user_id = u.id
                   WHERE p.title LIKE :search OR p.content LIKE :search";
            
            if ($limit !== null && $offset !== null) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':search', '%' . $search . '%');
            
            if ($limit !== null && $offset !== null) {
                $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $postsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $posts = [];
            foreach ($postsData as $postData) {
                $posts[] = Post::fromArray($postData);
            }

            return $posts;

        } catch (Exception $e) {
            error_log("Error fetching posts: " . $e->getMessage());
            throw $e;
        }
    }

    // Récupérer un post par son ID
    public function getPostById($postId) {
        try {
            $sql = "SELECT p.*, u.username, 
                   (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count,
                   (SELECT COUNT(*) FROM votes WHERE post_id = p.id AND vote_type = 'up') - 
                   (SELECT COUNT(*) FROM votes WHERE post_id = p.id AND vote_type = 'down') as votes
                   FROM posts p
                   LEFT JOIN users u ON p.user_id = u.id
                   WHERE p.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $postId]);
            $postData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$postData) {
                return null;
            }

            return Post::fromArray($postData);

        } catch (Exception $e) {
            error_log("Error fetching post: " . $e->getMessage());
            throw $e;
        }
    }

    // Mettre à jour un post
    public function updatePost($postId, $postData, $imageFile = null) {
        try {
            // Récupérer le post existant
            $existingPost = $this->getPostById($postId);
            if (!$existingPost) {
                throw new Exception("Post not found");
            }

            $imagePath = $existingPost->getImage();
            
            // Gestion de la nouvelle image
            if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
                // Supprimer l'ancienne image si elle existe
                if ($imagePath) {
                    $oldImagePath = __DIR__ . '/../uploads/' . $imagePath;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Uploader la nouvelle image
                $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $uploadPath = __DIR__ . '/../uploads/' . $filename;

                if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                    $imagePath = $filename;
                } else {
                    throw new Exception("Failed to upload new image");
                }
            }

            // Mettre à jour le post
            $sql = "UPDATE posts SET 
                    title = :title, 
                    content = :content, 
                    image = :image 
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                'title' => $postData['title'],
                'content' => $postData['content'],
                'image' => $imagePath,
                'id' => $postId
            ]);

            if ($success) {
                $existingPost->setTitle($postData['title']);
                $existingPost->setContent($postData['content']);
                $existingPost->setImage($imagePath);
                return $existingPost;
            }

            return false;

        } catch (Exception $e) {
            error_log("Error updating post: " . $e->getMessage());
            throw $e;
        }
    }

    // Supprimer un post
    public function deletePost($postId) {
        try {
            // Récupérer le post pour supprimer son image
            $post = $this->getPostById($postId);
            if (!$post) {
                throw new Exception("Post not found");
            }

            // Supprimer l'image associée si elle existe
            if ($post->getImage()) {
                $imagePath = __DIR__ . '/../uploads/' . $post->getImage();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Supprimer les dépendances (commentaires, votes, réactions)
            $this->db->beginTransaction();

            $this->db->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$postId]);
            $this->db->prepare("DELETE FROM votes WHERE post_id = ?")->execute([$postId]);
            $this->db->prepare("DELETE FROM reactions WHERE post_id = ?")->execute([$postId]);

            // Supprimer le post
            $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
            $success = $stmt->execute([$postId]);

            $this->db->commit();

            return $success;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error deleting post: " . $e->getMessage());
            throw $e;
        }
    }

    // Voter pour un post
    public function votePost($postId, $userId, $voteType) {
        try {
            // Vérifier si l'utilisateur a déjà voté
            $stmt = $this->db->prepare("SELECT id, vote_type FROM votes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$userId, $postId]);
            $existingVote = $stmt->fetch();

            if ($existingVote) {
                // Mettre à jour le vote existant
                if ($existingVote['vote_type'] === $voteType) {
                    // Supprimer le vote si c'est le même type
                    $this->db->prepare("DELETE FROM votes WHERE id = ?")->execute([$existingVote['id']]);
                    return 'removed';
                } else {
                    // Mettre à jour le vote
                    $this->db->prepare("UPDATE votes SET vote_type = ? WHERE id = ?")
                             ->execute([$voteType, $existingVote['id']]);
                    return 'updated';
                }
            } else {
                // Nouveau vote
                $this->db->prepare("INSERT INTO votes (user_id, post_id, vote_type) VALUES (?, ?, ?)")
                         ->execute([$userId, $postId, $voteType]);
                return 'added';
            }

        } catch (Exception $e) {
            error_log("Error voting post: " . $e->getMessage());
            throw $e;
        }
    }

    // Ajouter une réaction à un post
    public function addReaction($postId, $userId, $reactionType) {
        try {
            // Vérifier si l'utilisateur a déjà réagi
            $stmt = $this->db->prepare("SELECT id, reaction_type FROM reactions WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$userId, $postId]);
            $existingReaction = $stmt->fetch();

            if ($existingReaction) {
                if ($existingReaction['reaction_type'] === $reactionType) {
                    // Supprimer la réaction si c'est la même
                    $this->db->prepare("DELETE FROM reactions WHERE id = ?")->execute([$existingReaction['id']]);
                    return 'removed';
                } else {
                    // Mettre à jour la réaction
                    $this->db->prepare("UPDATE reactions SET reaction_type = ? WHERE id = ?")
                             ->execute([$reactionType, $existingReaction['id']]);
                    return 'updated';
                }
            } else {
                // Nouvelle réaction
                $this->db->prepare("INSERT INTO reactions (post_id, user_id, reaction_type) VALUES (?, ?, ?)")
                         ->execute([$postId, $userId, $reactionType]);
                return 'added';
            }

        } catch (Exception $e) {
            error_log("Error adding reaction: " . $e->getMessage());
            throw $e;
        }
    }
    
    private function formatDate($date) {
        return date('Y-m-d H:i:s', strtotime($date));
    }
    // Récupérer les statistiques du forum
    public function getForumStats() {
        try {
            $stats = [
                'members' => 0,
                'posts' => 0,
                'comments' => 0
            ];

            // Nombre de membres
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM users");
            $result = $stmt->fetch();
            $stats['members'] = $result['count'];

            // Nombre de posts
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM posts");
            $result = $stmt->fetch();
            $stats['posts'] = $result['count'];

            // Nombre de commentaires
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM comments");
            $result = $stmt->fetch();
            $stats['comments'] = $result['count'];

            return $stats;

        } catch (Exception $e) {
            error_log("Error fetching forum stats: " . $e->getMessage());
            return [
                'members' => 0,
                'posts' => 0,
                'comments' => 0
            ];
        }
    }
}
?>