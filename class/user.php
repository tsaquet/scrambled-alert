<?php

class User {

    // Does the user is happy?
    private $bHappy;
    // Workstation's ID
    private $iIDWS;

    public function __construct()
    {
    
    }

    /**
     * Display users.
     */
    public function displayUser()
    {

    }

    /**
     * Getters & Setters.
     */
    public function setBHappy($happy)
    {
        $this->bHappy = $happy;
    }
  
    public function getBHappy()
    {
        return $this->bHappy;
    }

    public function setIIDWS($IDWS)
    {
        $this->iIDWS = $IDWS;
    }
  
    public function getIIDWS()
    {
        return $this->iIDWS;
    }
}
?>