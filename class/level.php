<?php

class Level {

    // level name
    private $sName;
    // level size x
    private $iSizeX;
	// level size y
    private $iSizeY;
	// wrap
	private $bWrapped;
	// max number of connection by cell
	private $iMaxConnections;
	// operator number
	private $iOperatorNumber;
	
	

    public function __construct($name,$x,$y,$wrapped,$maxConnections,$operatorNumber)
    {
    	$this->sName = $name;
		$this->iSizeX = $x;
		$this->iSizeY = $y;
		$this->bWrapped = $wrapped;
		$this->iMaxConnections = $maxConnections;
		$this->iOperatorNumber = $operatorNumber;
    }

    /**
     * Getters & Setters.
     */
    public function getName()
	{
		return $this->sName;
	}
	
	public function setName($name)
	{
		$this->sName = $name;
	}
     
     
    public function setWrapped($wrap)
    {
        $this->bWrapped = $wrap;
    }
  
    public function isWrapped()
    {
        return $this->bWrapped;
    }
	
	public function getSizeX()
	{
		return $this->iSizeX;
	}
	
	public function setSizeX($x)
	{
		$this->iSizeX = $x;
	}
	
	public function getSizeY()
	{
		return $this->iSizeY;
	}
	
	public function setSizeY($y)
	{
		$this->iSizeY = $y;
	}
	
	public function getMaxConnections()
	{
		return $this->iMaxConnections;
	}
	
	public function setMaxConnections($maxConnections)
	{
		$this->iMaxConnections = $maxConnections;
	}
	
	public function getOperatorNumber()
	{
		return $this->iOperatorNumber;
	}
	
	public function setOperatorNumber($on)
	{
		$this->iOperatorNumber = $on;
	}

}
?>