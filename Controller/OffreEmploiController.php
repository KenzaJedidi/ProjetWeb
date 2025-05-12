<?php
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../Model/OffreEmploi.php';



class OffreEmploiController
{
    public function ajouter($offre)
    {
        $sql = "INSERT INTO offres_emploi (titre, description, type_contrat, salaire, localisation, competences, status, date_creation)
                VALUES (:titre, :description, :type_contrat, :salaire, :localisation, :competences, :status, NOW())";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([
            'titre' => $offre->getTitre(),
            'description' => $offre->getDescription(),
            'type_contrat' => $offre->getTypeContrat(),
            'salaire' => $offre->getSalaire(),
            'localisation' => $offre->getLocalisation(),
            'competences' => $offre->getCompetences(),
            'status' => $offre->getStatus()
        ]);
        return $db->lastInsertId();
    }

    public function afficher()
    {
        $sql = "SELECT * FROM offres_emploi";
        $db = config::getConnexion();
        $query = $db->query($sql);
        $offres = [];
        while ($row = $query->fetch()) {
            $offre = new OffreEmploi(
                $row['titre'],
                $row['description'],
                $row['type_contrat'],
                $row['salaire'],
                $row['localisation'],
                $row['competences'],
                $row['status']
            );
            $offre->setId($row['id']);
            $offre->setDateCreation($row['date_creation']);
            $offres[] = $offre;
        }
        return $offres;
    }

    public function modifier($offre, $id)
    {
        $sql = "UPDATE offres_emploi 
                SET titre = :titre, description = :description, type_contrat = :type_contrat, 
                    salaire = :salaire, localisation = :localisation, competences = :competences, status = :status
                WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([
            'titre' => $offre->getTitre(),
            'description' => $offre->getDescription(),
            'type_contrat' => $offre->getTypeContrat(),
            'salaire' => $offre->getSalaire(),
            'localisation' => $offre->getLocalisation(),
            'competences' => $offre->getCompetences(),
            'status' => $offre->getStatus(),
            'id' => $id
        ]);
        return $query->rowCount() > 0;
    }

    public function supprimer($id)
    {
        $sql = "DELETE FROM offres_emploi WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
        return $query->rowCount() > 0;
    }

    public function afficherpag($limit = null, $offset = null)
{
    $sql = "SELECT * FROM offres_emploi";
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
    $offres = [];
    while ($row = $query->fetch()) {
        $offre = new OffreEmploi(
            $row['titre'],
            $row['description'],
            $row['type_contrat'],
            $row['salaire'],
            $row['localisation'],
            $row['competences'],
            $row['status']
        );
        $offre->setId($row['id']);
        $offre->setDateCreation($row['date_creation']);
        $offres[] = $offre;
    }
    return $offres;
}
public function getSalaryStats() {
    $sql = "SELECT 
                COUNT(*) as count,
                CASE 
                    WHEN salaire IS NULL THEN 'Non spécifié'
                    WHEN salaire < 1000 THEN 'Moins de 1000 TND'
                    WHEN salaire BETWEEN 1000 AND 1499 THEN '1000-1499 TND'
                    WHEN salaire BETWEEN 1500 AND 1999 THEN '1500-1999 TND'
                    WHEN salaire BETWEEN 2000 AND 2499 THEN '2000-2499 TND'
                    WHEN salaire BETWEEN 2500 AND 4999 THEN '2500-4999 TND'
                    WHEN salaire >= 5000 THEN '5000 TND et plus'
                    ELSE 'Autre'
                END as salary_range
            FROM offres_emploi
            GROUP BY salary_range
            ORDER BY 
                CASE 
                    WHEN salary_range = 'Moins de 1000 TND' THEN 1
                    WHEN salary_range = '1000-1499 TND' THEN 2
                    WHEN salary_range = '1500-1999 TND' THEN 3
                    WHEN salary_range = '2000-2499 TND' THEN 4
                    WHEN salary_range = '2500-4999 TND' THEN 5
                    WHEN salary_range = '5000 TND et plus' THEN 6
                    ELSE 7
                END";
    
    try {
        $db = config::getConnexion();
        $query = $db->query($sql);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatage des résultats pour garantir toutes les tranches
        $allRanges = [
            'Moins de 1000 TND' => 0,
            '1000-1499 TND' => 0,
            '1500-1999 TND' => 0,
            '2000-2499 TND' => 0,
            '2500-4999 TND' => 0,
            '5000 TND et plus' => 0,
            'Non spécifié' => 0
        ];
        
        foreach ($results as $row) {
            if (isset($allRanges[$row['salary_range']])) {
                $allRanges[$row['salary_range']] = (int)$row['count'];
            }
        }
        
        // Conversion en format attendu par le frontend
        $formattedResults = [];
        foreach ($allRanges as $range => $count) {
            $formattedResults[] = [
                'salary_range' => $range,
                'count' => $count
            ];
        }
        
        return $formattedResults;
    } catch (PDOException $e) {
        error_log("Erreur dans getSalaryStats: " . $e->getMessage());
        return [];
    }
}


// Dans OffreEmploiController.php
public function getLocalisationsDistinctes() {
    $db = config::getConnexion();
    $query = $db->query("SELECT DISTINCT localisation FROM offres_emploi WHERE status = 'Active' AND localisation IS NOT NULL");
    return $query->fetchAll(PDO::FETCH_COLUMN);
}
}

?>