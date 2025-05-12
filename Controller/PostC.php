<?php
	include_once dirname(__FILE__).'/../Config.php';
	include_once dirname(__FILE__).'/../Model/Post.php';

    class PostC {

        /////..............................Afficher............................../////
        function AfficherPosts($sort = null) {
            $sql = "SELECT * FROM posts";
            
            // Add sorting if specified
            if ($sort === 'date_asc') {
                $sql .= " ORDER BY created_at ASC";
            } elseif ($sort === 'date_desc') {
                $sql .= " ORDER BY created_at DESC";
            } else {
                $sql .= " ORDER BY created_at DESC"; // Par défaut, trier par date décroissante
            }
            
            $db = config::getConnexion();
            try {
                $liste = $db->query($sql);
                $posts = $liste->fetchAll(PDO::FETCH_ASSOC);
                
                // Ajouter les réactions à chaque publication
                foreach ($posts as &$post) {
                    $postObj = new Post(null, null, null, null);
                    $postObj->setId($post['id']);
                    $post['reactions'] = $postObj->getReactions();
                }
                
                return $posts;
            } catch (Exception $e) {
                die('Erreur:' . $e->getMessage());
            }
        }
        
        /////..............................Rechercher par titre............................../////
        function RechercherPostsParTitre($searchTerm) {
            $sql = "SELECT * FROM posts WHERE title LIKE :searchTerm";
            $db = config::getConnexion();
            try {
                $query = $db->prepare($sql);
                $searchParam = '%' . $searchTerm . '%';
                $query->bindParam(':searchTerm', $searchParam, PDO::PARAM_STR);
                $query->execute();
                return $query->fetchAll();
            } catch (Exception $e) {
                die('Erreur:' . $e->getMessage());
            }
        }
        
        /////..............................Obtenir posts avec nombre de commentaires............................../////
        function ObtenirPostsAvecCommentaires() {
            $sql = "SELECT p.id, p.title, COUNT(c.id) as comment_count 
                    FROM posts p 
                    LEFT JOIN comments c ON p.id = c.post_id 
                    GROUP BY p.id 
                    ORDER BY comment_count DESC 
                    LIMIT 10";
            $db = config::getConnexion();
            try {
                $query = $db->query($sql);
                return $query->fetchAll();
            } catch (Exception $e) {
                die('Erreur:' . $e->getMessage());
            }
        }
        
        
        /////..............................Supprimer............................../////
                function SupprimerPost($id){
                    $sql="DELETE FROM posts WHERE id=:id";
                    $db = config::getConnexion();
                    $req=$db->prepare($sql);
                    $req->bindValue(':id', $id);   
                    try{
                        $req->execute();
                    }
                    catch(Exception $e){
                        die('Erreur:'. $e->getMessage());
                    }
                }
        
        
        /////..............................Ajouter............................../////
                function AjouterPost($Post){
                     $sql="INSERT INTO posts (user_id,title,content,image,metier) 
                     VALUES (:user_id,:title,:content,:image,:metier)";
                     
                     $db = config::getConnexion();
                     try{
                         $query = $db->prepare($sql);
                         $query->execute([
                             'user_id' => $Post->getUserId(),
                             'title' => $Post->getTitle(),
                             'content' => $Post->getContent(),
                             'image' => $Post->getImage(),
                             'metier' => $Post->getMetier() ?? null,
                     ]);
                                 
                     }
                     catch (Exception $e){
                         echo 'Erreur: '.$e->getMessage();
                     }			
                 }
        /////..............................Affichage par la cle Primaire............................../////
                function RecupererPost($id){
                     $sql="SELECT * FROM posts WHERE id = :id";
                     $db = config::getConnexion();
                     try{
                         $query = $db->prepare($sql);
                         $query->bindParam(':id', $id, PDO::PARAM_INT);
                         $query->execute();
        
                         $Post = $query->fetch(PDO::FETCH_ASSOC);
                         return $Post;
                     } catch (Exception $e){
                         die('Erreur: '.$e->getMessage());
                     }
                 }

                 function ObtenirReactionsPost($postId, $userId = null) {
                      try {
                          $post = new Post(null, null, null, null);
                          $post->setId($postId);

                          // Si un utilisateur est spécifié, obtenir sa réaction
                          if ($userId !== null) {
                              $userReaction = $post->getReactionsByType(null, $userId);
                              return $userReaction ? $userReaction : null;
                          }

                          // Sinon, obtenir toutes les réactions
                          $reactions = $post->getReactionsByType();

                          return $reactions;
                      } catch (Exception $e) {
                          error_log('Erreur ObtenirReactionsPost: ' . $e->getMessage());
                          return [];
                      }
                  }

                  function AjouterReaction($postId, $userId, $reactionType) {
                    try {
                        // Validation des paramètres
                        if (!$postId || !$userId || !$reactionType) {
                            return ['success' => false, 'message' => 'Paramètres invalides'];
                        }
                
                        // Types de réactions valides
                        $validReactions = ['like', 'love', 'haha', 'wow', 'sad', 'angry'];
                        if (!in_array($reactionType, $validReactions)) {
                            return ['success' => false, 'message' => 'Type de réaction invalide'];
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
                                    return [
                                        'success' => true, 
                                        'message' => 'Réaction supprimée',
                                        'status' => 'removed'
                                    ];
                                } else {
                                    // Sinon, mettre à jour la réaction existante
                                    $stmt = $pdo->prepare("UPDATE reactions SET reaction_type = ?, created_at = NOW() WHERE id = ?");
                                    $stmt->execute([$reactionType, $existingReaction['id']]);
                                    $pdo->commit();
                                    return [
                                        'success' => true, 
                                        'message' => 'Réaction mise à jour',
                                        'status' => 'updated'
                                    ];
                                }
                            }
                
                            // Insérer la nouvelle réaction
                            $stmt = $pdo->prepare("INSERT INTO reactions (user_id, post_id, reaction_type, created_at) VALUES (?, ?, ?, NOW())");
                            $result = $stmt->execute([$userId, $postId, $reactionType]);
                
                            if ($result) {
                                $pdo->commit();
                                return [
                                    'success' => true, 
                                    'message' => 'Réaction ajoutée',
                                    'status' => 'added'
                                ];
                            } else {
                                $pdo->rollBack();
                                return [
                                    'success' => false, 
                                    'message' => 'Échec de l\'ajout de la réaction'
                                ];
                            }
                        } catch (PDOException $e) {
                            $pdo->rollBack();
                            error_log('Reaction Transaction Error: ' . $e->getMessage());
                            return ['success' => false, 'message' => $e->getMessage()];
                        }
                    } catch (Exception $e) {
                        error_log('Erreur AjouterReaction: ' . $e->getMessage());
                        return ['success' => false, 'message' => $e->getMessage()];
                    }
                }
                 function handleReactionRequest() {
                     header('Content-Type: application/json');
                     $data = json_decode(file_get_contents('php://input'), true);

                     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['action']) && $data['action'] === 'add_reaction') {
                         $response = $this->AjouterReaction($data['post_id'], $data['user_id'], $data['reaction_type']);
                         echo json_encode($response);
                         exit;
                     } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_reactions') {
                         $postId = $_GET['post_id'];
                         $reactions = $this->ObtenirReactionsPost($postId);
                         echo json_encode($reactions);
                         exit;
                     }
                 }
        
        /////..............................Update............................../////
                function ModifierPost($Post){
                     try {
                         $db = config::getConnexion();
                         $query = $db->prepare('UPDATE posts SET title = :title, content = :content, image = :image, metier = :metier WHERE id = :id');
                         $query->execute([
                             'title' => $Post['title'],
                             'content' => $Post['content'],
                             'image' => $Post['image'] ?? '',
                             'metier' => $Post['metier'] ?? '',
                             'id' => $Post['id']
                         ]);
                         return true;
                      } catch (PDOException $e) {
                          return false;
                      }
                  }

        
        function SupprimerReaction($postId, $userId, $reactionType) {
            try {
                $post = new Post(null, null, null, null);
                $post->setId($postId);

                $result = $post->removeReaction($userId, $reactionType);

                return $result ? ['success' => true, 'message' => 'Réaction supprimée'] 
                               : ['success' => false, 'message' => 'Erreur lors de la suppression'];
            } catch (Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }

    }
?>