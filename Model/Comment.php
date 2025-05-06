<?php
class Comment {
    private $id;
    private $user_id;
    private $post_id;
    private $content;
    private $created_at;
    private $username;

    public function __construct($user_id, $post_id, $content, $username = null) {
        $this->user_id = $user_id;
        $this->post_id = $post_id;
        $this->content = $content;
        $this->username = $username;
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

    public function getUsername() {
        return $this->username;
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

    public function setUsername($username) {
        $this->username = $username;
    }

    // Convert to array for JSON response
    public function toArray() {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'post_id' => $this->post_id,
            'username' => $this->username,
            'content' => $this->content,
            'created_at' => $this->created_at
        ];
    }

    // Create Comment from database row
    public static function fromArray(array $data) {
        $comment = new Comment(
            $data['user_id'],
            $data['post_id'],
            $data['content'],
            $data['username'] ?? null
        );

        if (isset($data['id'])) $comment->setId($data['id']);
        if (isset($data['created_at'])) $comment->setCreatedAt($data['created_at']);

        return $comment;
    }
}
?>