<?php
include_once dirname(__FILE__).'/../Config.php';
include_once dirname(__FILE__).'/../Model/Reclamation.php';

class ReclamationC {

    /////..............................Afficher............................../////
    function AfficherReclamation() {
        $sql = "SELECT * FROM Reclamation";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /////..............................Supprimer............................../////
    function SupprimerReclamation($idReclamation) {
        $sql = "DELETE FROM Reclamation WHERE idReclamation=:idReclamation";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':idReclamation', $idReclamation);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Erreur:' . $e->getMessage());
        }
    }

    /////..............................Ajouter............................../////
    function AjouterReclamation($Reclamation) {
        $sql = "INSERT INTO Reclamation (idUser, Type, Message, statut) 
                VALUES (:idUser, :Type, :Message, :statut)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'idUser' => $Reclamation->getidUser(),
                'Type' => $Reclamation->getType(),
                'Message' => $Reclamation->getMessage(),
                'statut' => $Reclamation->getstatut(),
            ]);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }

    /////..............................Affichage par la clé primaire............................../////
    function RecupererReclamation($idReclamation) {
        $sql = "SELECT * FROM Reclamation WHERE idReclamation = :idReclamation";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['idReclamation' => $idReclamation]);
            $Reclamation = $query->fetch();
            return $Reclamation;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /////..............................Update............................../////
    function ModifierReclamation($Reclamation, $idReclamation) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare('UPDATE Reclamation 
                                    SET idUser = :idUser, Type = :Type, Message = :Message, statut = :statut 
                                    WHERE idReclamation = :idReclamation');
            $query->execute([
                'idUser' => $Reclamation->getidUser(),
                'Type' => $Reclamation->getType(),
                'Message' => $Reclamation->getMessage(),
                'statut' => $Reclamation->getstatut(),
                'idReclamation' => $idReclamation
            ]);
            echo $query->rowCount() . " UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function UpdateStatutReclamation($idReclamation, $statut) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare('UPDATE Reclamation SET statut = :statut WHERE idReclamation = :idReclamation');
            $query->execute([
                'statut' => $statut,
                'idReclamation' => $idReclamation
            ]);
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /////..............................Tri............................../////
    function getReclamationsTries($sort_by) {
        // Liste des champs autorisés pour le tri
        $allowed_fields = ['idUser', 'Type', 'statut', 'dateReclamation'];
        
        if (!in_array($sort_by, $allowed_fields)) {
            $sort_by = 'dateReclamation'; // valeur par défaut si tri non autorisé
        }

        $sql = "SELECT * FROM Reclamation ORDER BY $sort_by DESC";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(); // important pour le JSON
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }


////////////////.......................rechercher...................................///////////////
public function rechercherReclamations($search) {
    $sql = "SELECT * FROM reclamation WHERE 
            idReclamation LIKE :search OR 
            Type LIKE :search OR 
            Message LIKE :search OR 
            dateReclamation LIKE :search OR 
            statut LIKE :search";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute(['search' => '%'.$search.'%']);
        return $query->fetchAll();
    } catch (Exception $e) {
        die('Erreur: ' . $e->getMessage());
    }
}

public function getReclamationStatsByType() {
    $sql = "SELECT Type, COUNT(*) as count FROM reclamation GROUP BY Type";
    $db = config::getConnexion(); // Obtenir la connexion comme dans les autres méthodes
    try {
        $stmt = $db->prepare($sql); // Utiliser $db au lieu de $this->db
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Erreur: ' . $e->getMessage());
    }
}
}

?>
