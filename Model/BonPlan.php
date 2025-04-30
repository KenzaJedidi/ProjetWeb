<?php
class BonPlan
{
    private $idBonplan =null ;
    private $destination=null ;
    private $restaurant=null ;
    private $hotel	=null ;
    private $dateCreation=null ;
   
    
    function __construct($destination,$restaurant,$hotel)
    {
        $this->destination = $destination;
        $this->restaurant = $restaurant;
        $this->hotel = $hotel;
    }
    

    function getidBonplan()
    {
        return $this->$idBonplan;
    }


    function getdestination()
    {
        return $this->destination;
    }
    function setdestination(string $destination)
    {
        $this->destination = $destination;
    }
    function getrestaurant()
    {
        return $this->restaurant;
    }
    function setrestaurant(string $restaurant)
    {
        $this->restaurant = $restaurant;
    }
    function gethotel()
    {
        return $this->hotel;
    }
    function sethotel(string $hotel)
    {
        $this->hotel = $hotel;
    }

}
?>