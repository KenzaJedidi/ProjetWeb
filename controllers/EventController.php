<?php
// chargement de la config et du modèle
include_once dirname(__FILE__) . '/../Config.php';
require_once dirname(__FILE__) . '/../Model/EventModel.php';

class EventController {
    private $model;

    public function __construct() {
        $this->model = new EventModel();
    }

    /////..............................Afficher (liste)............................../////
    public function listEvents() {
        try {
            // récupère tous les événements via le modèle
            $events = $this->model->getAllEvents();
            return $events;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /////..............................Afficher la vue............................../////
    public function displayEventsView() {
        try {
            $events = $this->listEvents();
            include dirname(__FILE__) . '/../View/eventsView.php';
        } catch (Exception $e) {
            include dirname(__FILE__) . '/../View/errorView.php';
        }
    }

    /////..............................Ajouter............................../////
    public function addEvent($event) {
        try {
            // $event doit être une instance de votre entité Event avec tous les getters
            $this->model->addEvent($event);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /////..............................Supprimer............................../////
    public function deleteEvent($idEvent) {
        try {
            $this->model->deleteEvent($idEvent);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /////......................Récupérer par clé primaire....................../////
    public function getEvent($idEvent) {
        try {
            return $this->model->getEventById($idEvent);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /////..............................Modifier............................../////
    public function updateEvent($event, $idEvent) {
        try {
            // $event est une instance Event avec les nouvelles valeurs
            $this->model->updateEvent($event, $idEvent);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /////......................Modifier statut (optionnel)....................../////
    public function updateEventStatus($idEvent, $statut) {
        try {
            $this->model->updateEventStatus($idEvent, $statut);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}

// point d’entrée : affiche la liste des événements
$controller = new EventController();
$controller->displayEventsView();
?>
