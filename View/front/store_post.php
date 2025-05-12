<?php
include '../../Controller/PostC.php';
include '../../Controller/UserC.php';

// Prevent any HTML output before redirect
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate form data
        if (!isset($_POST['title']) || empty(trim($_POST['title']))) {
            throw new Exception("Title is required");
        }
        
        if (!isset($_POST['content']) || empty(trim($_POST['content']))) {
            throw new Exception("Content is required");
        }
        
        if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != 0) {
            throw new Exception("Image upload is required");
        }
        
        // Create uploads directory if it doesn't exist
        // First, ensure the back/pages directory exists
        $root_dir = "../../";
        $back_dir = $root_dir . "View/back/";
        $pages_dir = $back_dir . "pages/";
        $target_dir = $pages_dir . "uploads/";
        
        // Create each directory in the path if it doesn't exist
        if (!file_exists($back_dir)) {
            mkdir($back_dir, 0777, true);
        }
        
        if (!file_exists($pages_dir)) {
            mkdir($pages_dir, 0777, true);
        }
        
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Process the image
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $image_name = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $image_name;
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            throw new Exception("File is not an image.");
        }
        
        // Check file size (5MB max)
        if ($_FILES["image"]["size"] > 5000000) {
            throw new Exception("Sorry, your file is too large (max 5MB).");
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }
        
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            throw new Exception("Failed to upload image. Please try again.");
        }
        
        // Create new post
        $Post = new Post(1, $_POST['title'], $_POST['content'], $image_name);
        
        $PostC = new PostC();
        $PostC->AjouterPost($Post);
        
        // Redirect to blog section
        header("Location: post_info.php#blog");
        exit;
        
    } catch (Exception $e) {
        // Display error and redirect after 3 seconds
        echo '<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border-radius: 5px;">';
        echo '<h3>Error:</h3><p>' . $e->getMessage() . '</p>';
        echo '<p>Redirecting back to the blog page in 3 seconds...</p>';
        echo '</div>';
        echo '<script>setTimeout(function() { window.location.href = "front.php#blog"; }, 3000);</script>';
    }
} else {
    // Redirect if accessed directly
    header("Location: front.php#blog");
    exit;
}
?>