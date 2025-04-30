<?php
class User
{
    private $idUser=null ;
    private $username=null ;
   
    
    function __construct($username)
    {
        $this->username = $username;
    }

    function getidUser()
    {
        return $this->$idUser;
    }

    function getUsername()
    {
        return $this->username;
    }
    function setUsername(string $username)
    {
        $this->username = $username;
    }



}
?>