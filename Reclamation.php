<?php
class Reclamation
{
    private $idReclamation=null ;
    private $idUser=null ;
    private $Type=null ;
    private $Message=null ;
    private $statut=null ;
    private $dateReclamation=null ;
   
    
    function __construct($idUser,$Type,$Message,$statut)
    {
        $this->idUser = $idUser;
        $this->Type = $Type;
        $this->Message = $Message;
        $this->statut = $statut;
    }
    

    function getidReclamation()
    {
        return $this->$idReclamation;
    }

    function getidUser()
    {
        return $this->idUser;
    }
    function setidUser(string $idUser)
    {
        $this->idUser = $idUser;
    }
    function getType()
    {
        return $this->Type;
    }
    function setType(string $Type)
    {
        $this->Type = $Type;
    }
    function getMessage()
    {
        return $this->Message;
    }
    function setMessage(string $Message)
    {
        $this->Message = $Message;
    }
    function getstatut()
    {
        return $this->statut;
    }
    function setstatut(string $statut)
    {
        $this->statut = $statut;
    }
    function getdateReclamation()
    {
        return $this->dateReclamation;
    }
    function setdateReclamation(string $dateReclamation)
    {
        $this->dateReclamation = $dateReclamation;
    }



}
?>