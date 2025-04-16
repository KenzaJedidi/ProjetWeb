<?php
// backend/register.php
session_start();
require 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');
$confirm = trim($data['confirm'] ?? '');

if (!$username || !$password || !$confirm) {
    echo json_encode(['error' => 'Tous les champs sont requis']);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(['error' => 'Les mots de passe ne correspondent pas']);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
    echo json_encode(['error' => 'Nom d’utilisateur déjà pris']);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $hashed]);

echo json_encode(['success' => true]);
?>
