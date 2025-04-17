<?php
require_once 'config/db.php';

class EventModel {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function createEvent($title, $location, $start_date, $end_date, $event_type, $participants, $status,  $description) {
        try {
            // Vérification des dates au format DATETIME
            $start_date = date('Y-m-d H:i:s', strtotime($start_date));
            $end_date = date('Y-m-d H:i:s', strtotime($end_date));

            // Vérification que la date de fin est après la date de début
            if ($start_date >= $end_date) {
                throw new Exception("La date de fin doit être après la date de début.");
            }

            // Préparation de la requête SQL
            $query = "INSERT INTO events (title, location, start_date, end_date, event_type, participants, status,  description) 
                      VALUES (:title, :location, :start_date, :end_date, :event_type, :participants, :status,  :description)";
            $stmt = $this->conn->prepare($query);

            // Liaison des paramètres
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":location", $location);
            $stmt->bindParam(":start_date", $start_date);
            $stmt->bindParam(":end_date", $end_date);
            $stmt->bindParam(":event_type", $event_type);
            $stmt->bindParam(":participants", $participants);
            $stmt->bindParam(":status", $status);
            
            $stmt->bindParam(":description", $description);

            // Exécution et retour du résultat
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Échec de l'ajout de l'événement : " . implode(", ", $stmt->errorInfo()));
            }
        } catch (Exception $e) {
            // Gestion d'erreur avec le message
            error_log($e->getMessage());
            return false;
        }
    }
}
?>
