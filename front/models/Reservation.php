<?php
class reservation
{
    private $idReservation = null;
    private $idUser = null;
    private $Type = null;
    private $Lieu = null;
    private $Date = null;
    private $Details = null;
    private $Statut = null;
    private $DateCreation = null;
    
    function __construct($idUser, $Type, $Lieu, $Date, $Details = null, $Statut = 'en attente')
    {
        $this->idUser = $idUser;
        $this->Type = $Type;
        $this->Lieu = $Lieu;
        $this->Date = $Date;
        $this->Details = $Details;
        $this->Statut = $Statut;
    }
    
    // Getters
    function getIdReservation()
    {
        return $this->idReservation;
    }

    function getIdUser()
    {
        return $this->idUser;
    }

    function getType()
    {
        return $this->Type;
    }

    function getLieu()
    {
        return $this->Lieu;
    }

    function getDate()
    {
        return $this->Date;
    }

    function getDetails()
    {
        return $this->Details;
    }

    function getStatut()
    {
        return $this->Statut;
    }

    function getDateCreation()
    {
        return $this->DateCreation;
    }

    // Setters
    function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    function setType($Type)
    {
        $this->Type = $Type;
    }

    function setLieu($Lieu)
    {
        $this->Lieu = $Lieu;
    }

    function setDate($Date)
    {
        $this->Date = $Date;
    }

    function setDetails($Details)
    {
        $this->Details = $Details;
    }

    function setStatut($Statut)
    {
        $this->Statut = $Statut;
    }

    function setDateCreation($DateCreation)
    {
        $this->DateCreation = $DateCreation;
    }
}
?>