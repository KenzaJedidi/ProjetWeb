<?php
// api.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__.'/config.php';
require_once __DIR__.'/Controller/PostController.php';
require_once __DIR__.'/Controller/CommentController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

try {
    // Routeur API
    switch(true) {
        case preg_match('/\/api\/posts(\?.*)?$/', $uri) && $method === 'GET':
            $search = $_GET['search'] ?? '';
            $controller = new PostController();
            $posts = $controller->getAllPosts(0, 0, $search);
            echo json_encode($posts);
            break;

        case preg_match('/\/api\/posts$/', $uri) && $method === 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $controller = new PostController();
            $post = $controller->createPost($data, $_FILES['image'] ?? null);
            echo json_encode($post->toArray());
            break;

        case preg_match('/\/api\/posts\/(\d+)$/', $uri, $matches) && $method === 'GET':
            $controller = new PostController();
            $post = $controller->getPostById($matches[1]);
            echo json_encode($post ? $post->toArray() : null);
            break;

        case preg_match('/\/api\/forum\/stats$/', $uri) && $method === 'GET':
            $controller = new PostController();
            $stats = $controller->getForumStats();
            echo json_encode($stats);
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
    }
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}