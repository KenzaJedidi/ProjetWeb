<?php
// edit_store.php

include_once '../../Controller/PostC.php';
include_once '../../Model/Post.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;

    if ($postId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid Post ID']);
        exit;
    }

    try {
        $postC = new PostC();
        $post = $postC->RecupererPost($postId);

        if (!$post) {
            echo json_encode(['success' => false, 'message' => 'Post not found']);
            exit;
        }

        $imagePath = $post['image'];

        if (!empty($image) && $image['error'] == 0) {
            $imageName = time() . '_' . basename($image['name']);
            $targetDir = '../../back/pages/uploads/';
            $targetFile = $targetDir . $imageName;

            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                $imagePath = $imageName;
            } else {
                echo json_encode(['success' => false, 'message' => "Image upload failed"]);
                exit;
            }
        }

        $postObj = new Post();
        $postObj->setUserId($post['user_id']);
        $postObj->setTitle($title);
        $postObj->setContent($content);
        $postObj->setImage($imagePath);

        $updateResult = $postC->ModifierPost($postObj, $postId); // Store the result

        if ($updateResult > 0) {
            echo json_encode(['success' => true, 'message' => 'Post updated successfully', 'redirect' => 'front.php']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Post update failed (no rows updated)']);
        }

    } catch (Exception $e) {
        error_log("Error updating post: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error updating post: ' . $e->getMessage()]); // Send the actual error message
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>