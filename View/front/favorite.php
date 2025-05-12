<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = (int)$_POST['event_id'];
    
    if (!isset($_SESSION['favoris'])) {
        $_SESSION['favoris'] = [];
    }

    $key = array_search($eventId, $_SESSION['favoris']);
    if ($key !== false) {
        unset($_SESSION['favoris'][$key]);
    } else {
        $_SESSION['favoris'][] = $eventId;
    }

    // Nettoyer et réindexer le tableau
    $_SESSION['favoris'] = array_values(array_unique($_SESSION['favoris']));
    
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false]);