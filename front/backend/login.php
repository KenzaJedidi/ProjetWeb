<?php
session_start();
require 'config.php';

// Activer l'affichage des erreurs en développement (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (!$username || !$password) {
    echo json_encode(['error' => 'Tous les champs doivent être renseignés']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    echo json_encode([
        'success' => true,
        'user_id' => $user['id'],       // ✅ à ne pas oublier
        'username' => $user['username']
      ]);
      
} else {
    echo json_encode(['error' => 'Identifiants invalides']);
}
?>
