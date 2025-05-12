<?php
class Comment {
    private $id;
    private $user_id;
    private $post_id;
    private $content;
    private $created_at;

    public function __construct($user_id, $post_id, $content) {
        $this->user_id = $user_id;
        $this->post_id = $post_id;
        $this->content = $content;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getPostId() {
        return $this->post_id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }



    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setPostId($post_id) {
        $this->post_id = $post_id;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
}
?>