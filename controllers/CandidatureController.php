<?php
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../models/Candidature.php';

class CandidatureController
{
    // Ajouter une candidature
    public function ajouter($candidature)
    {
        $sql = "INSERT INTO candidatures (nom_complet, email, telephone, poste, cv_path, message, offre_id, status, date_postulation)
                VALUES (:nom_complet, :email, :telephone, :poste, :cv_path, :message, :offre_id, :status, NOW())";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([
            'nom_complet' => $candidature->getNomComplet(),
            'email' => $candidature->getEmail(),
            'telephone' => $candidature->getTelephone(),
            'poste' => $candidature->getPoste(),
            'cv_path' => $candidature->getCvPath(),
            'message' => $candidature->getMessage(),
            'offre_id' => $candidature->getOffre_id(),
            'status' => $candidature->getStatus()
        ]);
        return $db->lastInsertId();
    }

    // Afficher toutes les candidatures
    public function afficher()
    {
        $sql = "SELECT * FROM candidatures";
        $db = config::getConnexion();
        $query = $db->query($sql);
        $candidatures = [];
        while ($row = $query->fetch()) {
            $candidature = new Candidature(
                $row['nom_complet'],
                $row['email'],
                $row['telephone'],
                $row['poste'],
                $row['cv_path'],
                $row['message'],
                $row['status']
            );
            $candidature->setId($row['id']);
            $candidature->setOffre_id($row['offre_id']);
            $candidature->setDatePostulation($row['date_postulation']);
            $candidatures[] = $candidature;
        }
        return $candidatures;
    }

    // Récupérer une candidature par ID
    public function getCandidatureById($id)
    {
        $sql = "SELECT * FROM candidatures WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
        $row = $query->fetch();
        if ($row) {
            $candidature = new Candidature(
                $row['nom_complet'],
                $row['email'],
                $row['telephone'],
                $row['poste'],
                $row['cv_path'],
                $row['message'],
                $row['status']
            );
            $candidature->setId($row['id']);
            $candidature->setOffre_id($row['offre_id']);
            $candidature->setDatePostulation($row['date_postulation']);
            return $candidature;
        }
        return null;
    }

    // Modifier une candidature
    public function modifier($candidature, $id)
    {
        $sql = "UPDATE candidatures 
                SET nom_complet = :nom_complet, email = :email, telephone = :telephone, poste = :poste,
                    cv_path = :cv_path, message = :message, offre_id = :offre_id, status = :status
                WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([
            'nom_complet' => $candidature->getNomComplet(),
            'email' => $candidature->getEmail(),
            'telephone' => $candidature->getTelephone(),
            'poste' => $candidature->getPoste(),
            'cv_path' => $candidature->getCvPath(),
            'message' => $candidature->getMessage(),
            'offre_id' => $candidature->getOffre_id(),
            'status' => $candidature->getStatus(),
            'id' => $id
        ]);
        return $query->rowCount() > 0;
    }

    // Supprimer une candidature
    public function supprimer($id)
    {
        $sql = "DELETE FROM candidatures WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
        return $query->rowCount() > 0;
    }

    // Afficher les candidatures avec pagination
    public function afficherPag($limit = null, $offset = null)
    {
        $sql = "SELECT * FROM candidatures";
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $db = config::getConnexion();
        $query = $db->prepare($sql);

        if ($limit !== null && $offset !== null) {
            $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $query->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $query->execute();
        $candidatures = [];
        while ($row = $query->fetch()) {
            $candidature = new Candidature(
                $row['nom_complet'],
                $row['email'],
                $row['telephone'],
                $row['poste'],
                $row['cv_path'],
                $row['message'],
                $row['status']
            );
            $candidature->setId($row['id']);
            $candidature->setOffre_id($row['offre_id']);
            $candidature->setDatePostulation($row['date_postulation']);
            $candidatures[] = $candidature;
        }
        return $candidatures;
    }

    public function add()
    {
        header('Content-Type: application/json');

        if ($_POST['action'] !== 'add') {
            echo json_encode(['success' => false, 'message' => 'Action invalide']);
            return;
        }

        // Validation des champs
        $nom_complet = $_POST['nom_complet'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $poste = $_POST['poste'] ?? '';
        $message = $_POST['message'] ?? '';
        $offre_id = $_POST['offre_id'] ?? '';
        $cv = $_FILES['cv'] ?? null;

        if (empty($nom_complet) || empty($email) || empty($poste) || empty($cv) || empty($offre_id)) {
            echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
            return;
        }

        // Validation du fichier CV
        if ($cv['type'] !== 'application/pdf') {
            echo json_encode(['success' => false, 'message' => 'Le fichier doit être un PDF']);
            return;
        }
        if ($cv['size'] > 2097152) {
            echo json_encode(['success' => false, 'message' => 'Le fichier ne doit pas dépasser 2MB']);
            return;
        }

        // Gestion du téléchargement du CV
        $upload_dir = __DIR__ . '/../Uploads/cvs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $cv_filename = uniqid('cv_') . '.pdf';
        $cv_path = $upload_dir . $cv_filename;

        if (!move_uploaded_file($cv['tmp_name'], $cv_path)) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors du téléchargement du CV']);
            return;
        }

        // Création de la candidature
        $candidature = new Candidature(
            $nom_complet,
            $email,
            $telephone,
            $poste,
            $cv_filename,
            $message
        );
        $candidature->setStatus('En attente');
        $candidature->setOffre_id($offre_id);

        // Enregistrement en base de données
        $sql = "INSERT INTO candidatures (nom_complet, email, telephone, poste, cv_path, message, offre_id, status, date_postulation)
                VALUES (:nom_complet, :email, :telephone, :poste, :cv_path, :message, :offre_id, :status, NOW())";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        try {
            $query->execute([
                'nom_complet' => $candidature->getNomComplet(),
                'email' => $candidature->getEmail(),
                'telephone' => $candidature->getTelephone(),
                'poste' => $candidature->getPoste(),
                'cv_path' => $candidature->getCvPath(),
                'message' => $candidature->getMessage(),
                'offre_id' => $candidature->getOffre_id(),
                'status' => $candidature->getStatus()
            ]);
            $candidature_id = $db->lastInsertId();

            echo json_encode([
                'success' => true,
                'message' => 'Candidature envoyée avec succès',
                'candidature_id' => $candidature_id
            ]);
        } catch (PDOException $e) {
            unlink($cv_path);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()]);
        }
    }

    public function update()
    {
        header('Content-Type: application/json');

        if ($_POST['action'] !== 'update') {
            echo json_encode(['success' => false, 'message' => 'Action invalide']);
            return;
        }

        // Validation des champs
        $candidature_id = $_POST['candidature_id'] ?? '';
        $nom_complet = $_POST['nom_complet'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $poste = $_POST['poste'] ?? '';
        $offre_id = $_POST['offre_id'] ?? '';
        $message = $_POST['message'] ?? '';
        $cv = $_FILES['cv'] ?? null;

        if (empty($candidature_id) || empty($nom_complet) || empty($email) || empty($poste) || empty($offre_id)) {
            echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
            return;
        }

        // Vérification que l'offre existe
        $db = config::getConnexion();
        $query = $db->prepare("SELECT id FROM offres_emploi WHERE id = :offre_id AND status = 'Active'");
        $query->execute(['offre_id' => $offre_id]);
        if ($query->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Offre invalide']);
            return;
        }

        // Préparer les données à mettre à jour
        $update_data = [
            'candidature_id' => $candidature_id,
            'nom_complet' => $nom_complet,
            'email' => $email,
            'telephone' => $telephone,
            'poste' => $poste,
            'offre_id' => $offre_id,
            'message' => $message
        ];

        // Gestion du CV (si fourni)
        if ($cv && $cv['size'] > 0) {
            if ($cv['type'] !== 'application/pdf') {
                echo json_encode(['success' => false, 'message' => 'Le fichier doit être un PDF']);
                return;
            }
            if ($cv['size'] > 2097152) {
                echo json_encode(['success' => false, 'message' => 'Le fichier ne doit pas dépasser 2MB']);
                return;
            }

            $upload_dir = __DIR__ . '/../Uploads/cvs/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $cv_filename = uniqid('cv_') . '.pdf';
            $cv_path = $upload_dir . $cv_filename;

            if (!move_uploaded_file($cv['tmp_name'], $cv_path)) {
                echo json_encode(['success' => false, 'message' => 'Erreur lors du téléchargement du CV']);
                return;
            }

            $update_data['cv_path'] = $cv_filename;
        }

        // Mise à jour en base de données
        $sql = "UPDATE candidatures 
                SET nom_complet = :nom_complet, 
                    email = :email, 
                    telephone = :telephone, 
                    poste = :poste, 
                    offre_id = :offre_id, 
                    message = :message";
        if (isset($update_data['cv_path'])) {
            $sql .= ", cv_path = :cv_path";
        }
        $sql .= " WHERE id = :candidature_id";
        
        $query = $db->prepare($sql);
        try {
            $query->execute($update_data);
            if ($query->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Candidature mise à jour avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Aucune candidature trouvée pour cet ID']);
            }
        } catch (PDOException $e) {
            if (isset($cv_path) && file_exists($cv_path)) {
                unlink($cv_path);
            }
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    // Supprimer une candidature
    public function delete()
    {
        header('Content-Type: application/json');

        if ($_POST['action'] !== 'delete') {
            echo json_encode(['success' => false, 'message' => 'Action invalide']);
            return;
        }

        $candidature_id = $_POST['candidature_id'] ?? '';
        if (empty($candidature_id)) {
            echo json_encode(['success' => false, 'message' => 'ID de candidature requis']);
            return;
        }

        try {
            $success = $this->supprimer($candidature_id);
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Candidature supprimée avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Aucune candidature trouvée pour cet ID']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }

    // Récupérer toutes les candidatures en JSON
    public function getCandidatures()
    {
        header('Content-Type: application/json');
        $candidatures = $this->afficher();
        $result = [];
        foreach ($candidatures as $candidature) {
            $result[] = [
                'id' => $candidature->getId(),
                'nom_complet' => $candidature->getNomComplet(),
                'email' => $candidature->getEmail(),
                'telephone' => $candidature->getTelephone(),
                'poste' => $candidature->getPoste(),
                'cv_path' => $candidature->getCvPath(),
                'message' => $candidature->getMessage(),
                'status' => $candidature->getStatus(),
                'date_postulation' => $candidature->getDatePostulation(),
                'offre_id' => $candidature->getOffre_id()
            ];
        }
        echo json_encode(['success' => true, 'candidatures' => $result]);
    }

    // Récupérer une candidature spécifique par ID en JSON
    public function getCandidature()
    {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID de candidature requis']);
            return;
        }
        $candidature = $this->getCandidatureById($id);
        if ($candidature) {
            echo json_encode([
                'success' => true,
                'candidature' => [
                    'id' => $candidature->getId(),
                    'nom_complet' => $candidature->getNomComplet(),
                    'email' => $candidature->getEmail(),
                    'telephone' => $candidature->getTelephone(),
                    'poste' => $candidature->getPoste(),
                    'cv_path' => $candidature->getCvPath(),
                    'message' => $candidature->getMessage(),
                    'status' => $candidature->getStatus(),
                    'date_postulation' => $candidature->getDatePostulation(),
                    'offre_id' => $candidature->getOffre_id()
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Candidature non trouvée']);
        }
    }
}

// Gestion de la requête
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CandidatureController();
    if ($_POST['action'] === 'add') {
        $controller->add();
    } elseif ($_POST['action'] === 'update') {
        $controller->update();
    } elseif ($_POST['action'] === 'delete') {
        $controller->delete();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new CandidatureController();
    if (isset($_GET['action']) && $_GET['action'] === 'getCandidatures') {
        $controller->getCandidatures();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'getCandidature') {
        $controller->getCandidature();
    }
}
?>