<?php
header('Content-Type: application/json');
require_once '../config.php'; // Fichier de configuration de la base de données

$method = $_SERVER['REQUEST_METHOD'];

try {
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    switch ($method) {
        case 'GET':
            // Lire les réservations
            if (isset($_GET['id'])) {
                // Lire une seule réservation
                $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = :id");
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();
                $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($reservation);
            } else {
                // Lire toutes les réservations
                $stmt = $conn->prepare("SELECT * FROM reservations");
                $stmt->execute();
                $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($reservations);
            }
            break;

        case 'POST':
            // Créer ou mettre à jour une réservation
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['id']) && $data['id']) {
                // Mise à jour
                $stmt = $conn->prepare("UPDATE reservations SET 
                    nom = :nom, prenom = :prenom, email = :email, telephone = :telephone,
                    destination_id = :destination_id, type = :type, date_depart = :date_depart,
                    date_retour = :date_retour, nombre_personnes = :nombre_personnes,
                    commentaires = :commentaires WHERE id = :id");
                
                $stmt->bindParam(':id', $data['id']);
            } else {
                // Création
                $stmt = $conn->prepare("INSERT INTO reservations 
                    (nom, prenom, email, telephone, destination_id, type, date_depart, 
                    date_retour, nombre_personnes, commentaires, statut) 
                    VALUES (:nom, :prenom, :email, :telephone, :destination_id, :type, 
                    :date_depart, :date_retour, :nombre_personnes, :commentaires, 'en_attente')");
            }
            
            $stmt->bindParam(':nom', $data['nom']);
            $stmt->bindParam(':prenom', $data['prenom']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':telephone', $data['telephone']);
            $stmt->bindParam(':destination_id', $data['destination_id']);
            $stmt->bindParam(':type', $data['type']);
            $stmt->bindParam(':date_depart', $data['date_depart']);
            $stmt->bindParam(':date_retour', $data['date_retour']);
            $stmt->bindParam(':nombre_personnes', $data['nombre_personnes']);
            $stmt->bindParam(':commentaires', $data['commentaires']);
            
            $stmt->execute();
            
            if (!isset($data['id']) {
                $data['id'] = $conn->lastInsertId();
            }
            
            echo json_encode($data);
            break;

        case 'DELETE':
            // Supprimer une réservation
            $id = $_GET['id'];
            $stmt = $conn->prepare("DELETE FROM reservations WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données: ' . $e->getMessage()]);
}