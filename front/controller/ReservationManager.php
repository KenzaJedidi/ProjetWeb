<?php
header("Content-Type: application/json");
require_once '../config/database.php';

// Configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "localoo_reservations";

// Connexion à la base de données
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Récupération de la méthode HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Lire une réservation spécifique
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($reservation);
        } else {
            // Lire toutes les réservations (déjà géré dans le front.php)
            echo json_encode([]);
        }
        break;
        
    case 'POST':
        // Créer ou mettre à jour une réservation
        $data = $_POST;
        
        if (!empty($data['id'])) {
            // Mise à jour
            $stmt = $conn->prepare("UPDATE reservations SET 
                nom = ?, prenom = ?, email = ?, telephone = ?, 
                destination_id = ?, type = ?, date_depart = ?, 
                date_retour = ?, nombre_personnes = ?, commentaires = ?
                WHERE id = ?");
                
            $stmt->execute([
                $data['nom'], $data['prenom'], $data['email'], $data['telephone'],
                $data['destination_id'], $data['type'], $data['date_depart'],
                $data['date_retour'], $data['nombre_personnes'], $data['commentaires'],
                $data['id']
            ]);
            
            echo json_encode(['message' => 'Réservation mise à jour avec succès']);
        } else {
            // Création
            $stmt = $conn->prepare("INSERT INTO reservations 
                (nom, prenom, email, telephone, destination_id, type, 
                date_depart, date_retour, nombre_personnes, commentaires) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
            $stmt->execute([
                $data['nom'], $data['prenom'], $data['email'], $data['telephone'],
                $data['destination_id'], $data['type'], $data['date_depart'],
                $data['date_retour'], $data['nombre_personnes'], $data['commentaires']
            ]);
            
            echo json_encode(['message' => 'Réservation créée avec succès', 'id' => $conn->lastInsertId()]);
        }
        break;
        
    case 'DELETE':
        // Supprimer une réservation
        parse_str(file_get_contents("php://input"), $data);
        
        if (!empty($data['id'])) {
            $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
            $stmt->execute([$data['id']]);
            echo json_encode(['message' => 'Réservation supprimée avec succès']);
        } else {
            echo json_encode(['error' => 'ID de réservation manquant']);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Méthode non supportée']);
        break;
}