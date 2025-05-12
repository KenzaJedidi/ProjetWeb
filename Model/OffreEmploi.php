<?php
class OffreEmploi {
    private $id;
    private $titre;
    private $description;
    private $type_contrat;
    private $salaire;
    private $localisation;
    private $competences;
    private $status;
    private $date_creation;

    public function __construct($titre, $description, $type_contrat, $salaire = null, $localisation = null, $competences = null, $status = 'Active') {
        $this->titre = $titre;
        $this->description = $description;
        $this->type_contrat = $type_contrat;
        $this->salaire = $salaire;
        $this->localisation = $localisation;
        $this->competences = $competences;
        $this->status = $status;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getTypeContrat() {
        return $this->type_contrat;
    }

    public function getSalaire() {
        return $this->salaire;
    }

    public function getLocalisation() {
        return $this->localisation;
    }

    public function getCompetences() {
        return $this->competences;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getDateCreation() {
        return $this->date_creation;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setTypeContrat($type_contrat) {
        $this->type_contrat = $type_contrat;
    }

    public function setSalaire($salaire) {
        $this->salaire = $salaire;
    }

    public function setLocalisation($localisation) {
        $this->localisation = $localisation;
    }

    public function setCompetences($competences) {
        $this->competences = $competences;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setDateCreation($date_creation) {
        $this->date_creation = $date_creation;
    }
}
?>