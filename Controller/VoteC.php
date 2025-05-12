<?php
// require_once dirname(__DIR__) . '/Model/Database.php';
include_once dirname(__FILE__).'/../Config.php';

class VoteC {

    public function AddVote($postId) {
        // First, check if the user has already voted on this post
        $db = config::getConnexion();
        $query = $db->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
        $query->execute([2, $postId]);
      

        return $query->execute();
    }


}
?>
