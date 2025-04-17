<?php
class Reponse
{
    private $idReponse=null ;
    private $idReclamation=null ;
    private $Message=null ;
    private $dateReponse=null ;
    
    function __construct($idReclamation,$Message)
    {
        $this->idReclamation = $idReclamation;
        $this->Message = $Message;
    }
    
 
    function getidReponse()
    {
        return $this->$idReponse;
    }

    function getidReclamation()
    {
        return $this->idReclamation;
    }
    function setidReclamation(string $idReclamation)
    {
        $this->idReclamation = $idReclamation;
    }
    function getMessage()
    {
        return $this->Message;
    }
    function setMessage(string $Message)
    {
        $this->Message = $Message;
    }
    function getdateReponse()
    {
        return $this->dateReponse;
    }
    function setdateReponse(string $dateReponse)
    {
        $this->dateReponse = $dateReponse;
    }
      
    
}
?>