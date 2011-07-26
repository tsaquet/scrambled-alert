<?php

class Workstation extends Computer {

    // Does the workstation is OK?
    private $bOK;

    /**
     * Getters & Setters.
     */
    public function setBOK($OK)
    {
        $this->bOK = $OK;
    }
  
    public function getBOK()
    {
        return $this->bOK;
    }
}
?>