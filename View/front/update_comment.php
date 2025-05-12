<?php
session_start();
include_once dirname(__FILE__).'/../../Config.php';
include_once dirname(__FILE__).'/../../Model/Comment.php';
include_once dirname(__FILE__).'/../../Controller/CommentC.php';

// Prevent any HTML output
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['id']) && isset($_POST['content']) && isset($_POST['post_id'])) {
            // Create Comment object with required parameters
            $comment = new Comment(
                1,                      // user_id (default to 1 for now)
                $_POST['post_id'],      // post_id
                $_POST['content']       // content
            );
            
            // Set the ID separately since it's not in the constructor
            $comment->setId($_POST['id']);
            
            $commentC = new CommentC();
            $commentC->ModifierComment($comment, $_POST['id']);
            
            echo json_encode(['success' => true, 'message' => 'Comment updated successfully']);
        } else {
            throw new Exception('Missing required fields');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 