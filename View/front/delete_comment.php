<?php
session_start();
include_once dirname(__FILE__).'/../../Config.php';
include_once dirname(__FILE__).'/../../Controller/CommentC.php';

// Prevent any HTML output
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['id'])) {
            $commentC = new CommentC();
            $commentC->SupprimerComment($_POST['id']);
            
            echo json_encode(['success' => true, 'message' => 'Comment deleted successfully']);
        } else {
            throw new Exception('Missing comment ID');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 