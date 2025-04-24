<?php
session_start();
include_once '../../../Controller/userC.php';

if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (isset($_GET['id'])) {
    $userC = new userC();
    $success = $userC->deleteUser($_GET['id']);
    
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
}