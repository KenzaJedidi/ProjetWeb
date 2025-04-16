<?php

class Candidature
{
    private ?int $id = null;
    private ?string $nom_complet = null;
    private ?string $email = null;
    private ?string $telephone = null;
    private ?string $poste = null;
    private ?string $cv_path = null;
    private ?string $message = null;
    private ?string $status = null;
    private ?string $date_postulation = null;
    private ?int $offre_id = null; 

    public function __construct($nom_complet, $email, $telephone, $poste, $cv_path, $message, $status = 'En attente')
    {
        $this->nom_complet = $nom_complet;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->poste = $poste;
        $this->cv_path = $cv_path;
        $this->message = $message;
        $this->status = $status;
    }

    // ✅ Getters
    public function getId(): ?int { return $this->id; }

    public function getNomComplet(): ?string { return $this->nom_complet; }

    public function getEmail(): ?string { return $this->email; }

    public function getTelephone(): ?string { return $this->telephone; }

    public function getPoste(): ?string { return $this->poste; }

    public function getCvPath(): ?string { return $this->cv_path; }

    public function getMessage(): ?string { return $this->message; }

    public function getStatus(): ?string { return $this->status; }

    public function getDatePostulation(): ?string { return $this->date_postulation; }

    public function getOffre_id(): ?int { return $this->offre_id; } // ✅ corrigé

    // ✅ Setters
    public function setId(int $id): self { $this->id = $id; return $this; }

    public function setNomComplet(string $nom_complet): self { $this->nom_complet = $nom_complet; return $this; }

    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function setTelephone(?string $telephone): self { $this->telephone = $telephone; return $this; }

    public function setPoste(string $poste): self { $this->poste = $poste; return $this; }

    public function setCvPath(string $cv_path): self { $this->cv_path = $cv_path; return $this; }

    public function setMessage(?string $message): self { $this->message = $message; return $this; }

    public function setStatus(string $status): self {
        if (!in_array($status, ['En attente', 'En cours', 'Accepté', 'Rejeté'])) {
            throw new InvalidArgumentException("Statut invalide.");
        }
        $this->status = $status;
        return $this;
    }

    public function setDatePostulation(?string $date_postulation): self {
        $this->date_postulation = $date_postulation;
        return $this;
    }

    public function setOffre_id(int $offre_id): self {
        $this->offre_id = $offre_id;
        return $this;
    }
}
