<?php
	include_once dirname(__FILE__).'/../Config.php';
	include_once dirname(__FILE__).'/../Model/Comment.php';

    class CommentC {

        /////..............................Afficher............................../////
        function AfficherComments() {
            $sql = "SELECT * FROM comments";
            $db = config::getConnexion();
            try {
                $liste = $db->query($sql);
                return $liste->fetchAll(); // <- transforme en tableau
            } catch (Exception $e) {
                die('Erreur:' . $e->getMessage());
            }
        }
        


///////////////////////////////////////////////////////////////////////
function AfficherCommentairesParPost($post_id) {
    $sql = "SELECT c.*, u.username 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = :post_id
            ORDER BY c.created_at DESC";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':post_id', $post_id);
        $query->execute();
        return $query->fetchAll();
    } catch (Exception $e) {
        die('Erreur: '.$e->getMessage());
    }
}


        
        /////..............................Supprimer............................../////
                function SupprimerComment($id){
                    $sql="DELETE FROM comments WHERE id=:id";
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
                function AjouterComment($Comment){
                    $sql="INSERT INTO comments (user_id,post_id,content) 
                    VALUES (:user_id,:post_id,:content)";
                    
                    $db = config::getConnexion();
                    try{
                        $query = $db->prepare($sql);
                        $query->execute([
                            'user_id' => $Comment->getUserId(),
                            'post_id' => $Comment->getPostId(),
                            'content' => $Comment->getContent(),
                    ]);
                                
                    }
                    catch (Exception $e){
                        echo 'Erreur: '.$e->getMessage();
                    }			
                }
        /////..............................Affichage par la cle Primaire............................../////
                function RecupererComment($id){
                    $sql="SELECT * from comments where id=$id";
                    $db = config::getConnexion();
                    try{
                        $query=$db->prepare($sql);
                        $query->execute();
        
                        $Comment=$query->fetch();
                        return $Comment;
                    }
                    catch (Exception $e){
                        die('Erreur: '.$e->getMessage());
                    }
                }
        
        /////..............................Update............................../////
                function ModifierComment($Comment,$id){
                    try {
                        $db = config::getConnexion();
                $query = $db->prepare('UPDATE comments SET  user_id = :user_id, post_id = :post_id, content = :content  WHERE id= :id');
                        $query->execute([
                            'user_id' => $Comment->getUserId(),
                            'post_id' => $Comment->getPostId(),
                            'content' => $Comment->getContent(),
                            'id' => $id
                        ]);
                        return $query->rowCount(); 
                    } catch (PDOException $e) {
                        throw $e; 
                    }
                }  
                
                
                function SupprimerCommentairesParPost($post_id) {
                    $sql = "DELETE FROM comments WHERE post_id = :post_id";
                    $db = Config::getConnexion();
                    try {
                        $query = $db->prepare($sql);
                        $query->bindValue(':post_id', $post_id, PDO::PARAM_INT);
                        return $query->execute();
                    } catch (Exception $e) {
                        error_log('Erreur SupprimerCommentairesParPost: '.$e->getMessage());
                        return false;
                    }
                }
            }


?>