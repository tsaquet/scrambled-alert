<?php

class Operator {

    // Does the operator is busy?
    private $bBusy;
    // position of the operator on the board
    private $iPosX;
    private $iPosY;

    public function __construct()
    {
    
    }

    /**
     * Display an operator.
     */
    public function displayOperator()
    {

    }

    /**
     * Getters & Setters.
     */
    public function setBBUsy($busy)
    {
        $this->bBusy = $busy;
    }
  
    public function getBBusy()
    {
        return $this->bBusy;
    }

    public function setIPosX($posX)
    {
        $this->iPosX = $posX;
    }
  
    public function getIPosX()
    {
        return $this->iPosX;
    }

    public function setIPosY($posY)
    {
        $this->iPosY = $posY;
    }
  
    public function getIPosY()
    {
        return $this->iPosY;
    }
}
?>