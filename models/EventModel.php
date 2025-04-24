<?php
class Event
{
    private $idEvent = null;
    private $title = null;
    private $location = null;
    private $start_date = null;
    private $end_date = null;
    private $event_type = null;
    private $participants = null;
    private $status = null;
    private $description = null;

    /////..............................Constructeur............................../////
    public function __construct($title, $location, $start_date, $end_date, $event_type, $participants, $status, $description)
    {
        $this->title        = $title;
        $this->location     = $location;
        $this->start_date   = $start_date;
        $this->end_date     = $end_date;
        $this->event_type   = $event_type;
        $this->participants = $participants;
        $this->status       = $status;
        $this->description  = $description;
    }

    /////..............................Getters et Setters............................../////
    public function getIdEvent()
    {
        return $this->idEvent;
    }

    public function setIdEvent($idEvent)
    {
        $this->idEvent = $idEvent;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }

    public function getEventType()
    {
        return $this->event_type;
    }

    public function setEventType($event_type)
    {
        $this->event_type = $event_type;
    }

    public function getParticipants()
    {
        return $this->participants;
    }

    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}
?>
