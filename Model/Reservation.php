<?php
class Reservation
{
    private $idReservation=null ;
    private $idBonPlan=null ;
    private $dateDepart=null ;
    private $dateRetour=null ;
    private $nbPersonne=null ;
    private $commentaire=null ;
    private $statut=null ;
    private $dateCreation=null ;
   
    
    function __construct($idBonPlan,$dateDepart,$dateRetour,$nbPersonne,$commentaire,$statut)
    {
        $this->idBonPlan = $idBonPlan;
        $this->dateDepart = $dateDepart;
        $this->dateRetour = $dateRetour;
        $this->nbPersonne = $nbPersonne;
        $this->commentaire = $commentaire;
        $this->statut = $statut;
    }
    
    function getidReservation()
    {
        return $this->idReservation;
    }

    function getidBonPlan()
    {
        return $this->idBonPlan;
    }

   
    function getnbPersonne()
    {
        return $this->nbPersonne;
    }
    function setnbPersonne(string $nbPersonne)
    {
        $this->nbPersonne = $nbPersonne;
    }

    function getdateDepart()
    {
        return $this->dateDepart;
    }
    function setdateDepart(string $dateDepart)
    {
        $this->dateDepart = $dateDepart;
    }
    function getdateRetour()
    {
        return $this->dateRetour;
    }
    function setdateRetour(string $dateRetour)
    {
        $this->dateRetour = $dateRetour;
    }
    function getcommentaire()
    {
        return $this->commentaire;
    }
    function setcommentaire(string $commentaire)
    {
        $this->commentaire = $commentaire;
    }
    function getstatut()
    {
        return $this->statut;
    }
    function setstatut(string $statut)
    {
        $this->statut = $statut;
    }
    function getdateCreation()
    {
        return $this->dateCreation;
    }
    function setdateCreation(string $dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }
}
?>