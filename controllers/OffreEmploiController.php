<?php
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../models/OffreEmploi.php';



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

}
?>