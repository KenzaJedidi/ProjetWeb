<?php
class Post {
    private $id;
    private $user_id;
    private $title;
    private $content;
    private $image;
    private $created_at;
    private $username;
    private $votes;
    private $comment_count;

    public function __construct($user_id, $title, $content, $image = null, $username = null) {
        $this->user_id = $user_id;
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->username = $username;
        $this->votes = 0;
        $this->comment_count = 0;
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

    public function getUsername() {
        return $this->username;
    }

    public function getVotes() {
        return $this->votes;
    }

    public function getCommentCount() {
        return $this->comment_count;
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

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setVotes($votes) {
        $this->votes = $votes;
    }

    public function setCommentCount($comment_count) {
        $this->comment_count = $comment_count;
    }

    // Convert to array for JSON response
    public function toArray() {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'username' => $this->username,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'votes' => $this->votes,
            'comment_count' => $this->comment_count
        ];
    }

    // Create Post from database row
    public static function fromArray(array $data) {
        $post = new Post(
            $data['user_id'],
            $data['title'],
            $data['content'],
            $data['image'] ?? null,
            $data['username'] ?? null
        );

        if (isset($data['id'])) $post->setId($data['id']);
        if (isset($data['created_at'])) $post->setCreatedAt($data['created_at']);
        if (isset($data['votes'])) $post->setVotes($data['votes']);
        if (isset($data['comment_count'])) $post->setCommentCount($data['comment_count']);

        return $post;
    }
}
?>