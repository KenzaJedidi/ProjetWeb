<?php
// backend/config.php
$host = 'localhost';
$db   = 'reddit';   // Remplacez 'nom_de_votre_base' par le nom exact de votre base de données
$user = 'root';                // Utilisateur XAMPP par défaut
$pass = '';                    // Mot de passe vide si vous n'en avez pas défini
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
