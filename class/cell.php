<?php
    
class Cell
{
	// The cell's position in the board. Only used for board generation
    private $iXindex;
    private $iYindex;
	
	// The directions in which this cell is isConnected
	private $sConnectedDirections;

	// Highlight cell if misclick
	private $bHighlightOn;
	
	private $bIsConnected;
	
	// True if the cell is currently part of a fully connected (solved) network
    private $bIsFullyConnected;
        
    // True if the cell is blind; ie. doesn't display its connections.
    private $bIsBlind;
        
    // True if this is the root cell of the network; ie. the collector.
    private $bIsRoot;
        
    // True if the cell has been isLocked by the user
    private $bIsLocked;
	
	// Type of cell : 
	/**
	 * 0 : cable
	 * 1 : server 
	 * 2 : echoes
	 */
	private $iType;
	
	private $iServerNbClick;
	
	private $arrDirectionName = array(
			"____"    => "0000",
			"___L"    => "0001",
			"__D_"    => "0010",
			"__DL"    => "0011",
			"_R__"    => "0100",
			"_R_L"    => "0101",
			"_RD_"    => "0110",
			"_RDL"    => "0111",
			"U___"    => "1000",
			"U__L"    => "1001",
			"U_D_"    => "1010",
			"U_DL"    => "1011",
			"UR__"    => "1100",
			"UR_L"    => "1101",
			"URD_"    => "1110",
			"URDL"    => "1111"
			);
			
	private $sBaseDirections;
	
	private $sCurrentDirections;
	
	private $sState;
	
	private $bInited;
			

	
	public function __construct($directions,$type)
	{
		//$this->iXindex = $posX;
		//$this->iYindex = $posY;
		
		// binary value
		$this->sBaseDirections = $this->arrDirectionName[$directions];
		$this->sCurrentDirections = $this->arrDirectionName[$directions];
		
		//locked : default false
		$this->bIsLocked = false;
		switch ($type) 
		{
			case 'cable':
				$this->iType = 0;
				break;	
			case 'server':
				$this->iType = 1;
				$this->sState = 'safe';
				break;
			case 'echoes':
				$this->iType = 2;
				$this->setRoot(true);
				break;
		}
	}
	
	public function getBaseDirections()
	{
		return $this->sBaseDirections;
	}
	
	public function setBaseDirections($dir)
	{
		$this->sBaseDirections = $dir;
		$this->sCurrentDirections = $dir;
	}
	
	public function getCurrentDirections()
	{
		return $this->sCurrentDirections;
	}
	
	public function getStringCurrentDirections()
	{
		return array_search($this->sCurrentDirections,$this->arrDirectionName);
	}
	
	public function addDir($dir)
	{
		$this->sCurrentDirections = $this->sCurrentDirections | $dir;
	}
	
	public function removeDir($dir)
	{
		$this->sCurrentDirections = $this->sCurrentDirections & $dir;
	}
	
	public function rotateClockwise() 
	{
		$arrClockWise = array(
					"0000"    => "0000",
					"0001"    => "1000",
					"0010"    => "0001",
					"0011"    => "1001",
					"0100"    => "0010",
					"0101"    => "1010",
					"0110"    => "0011",
					"0111"    => "1011",
					"1000"    => "0100",
					"1001"    => "1100",
					"1010"    => "0101",
					"1011"    => "1101",
					"1100"    => "0110",
					"1101"    => "1110",
					"1110"    => "0111",
					"1111"    => "1111");
        $this->sCurrentDirections = $arrClockWise[$this->sCurrentDirections];
    }
	
	public function rotateCounterClockwise() 
	{
		$arrCounterClockWise = array(
					"0000"    => "0000",
					"0001"    => "0010",
					"0010"    => "0100",
					"0011"    => "0110",
					"0100"    => "1000",
					"0101"    => "1010",
					"0110"    => "1100",
					"0111"    => "1110",
					"1000"    => "0001",
					"1001"    => "0011",
					"1010"    => "0101",
					"1011"    => "0111",
					"1100"    => "1001",
					"1101"    => "1011",
					"1110"    => "1101",
					"1111"    => "1111");
        $this->sCurrentDirections = $arrCounterClockWise[$this->sCurrentDirections];
    }
	
	public function isLocked() 
	{
        return $this->$bIsLocked;
    }


    public function setLocked($bLock) 
    {
        $this->bIsLocked = $bLock;
    }


    public function isConnected() 
    {
         return $this->bIsConnected;
    }
	
	public function getStringConnected()
	{
		switch ($this->iType) 
		{
			case 0:
				if ($this->isConnected())
				{
					return 'connected';
				}
				else
				{
					return 'disconnected';
				}
				break;	
			case 1:
				if ($this->isConnected())
				{
					return $this->getState();
				}
				else
				{
					return 'disconnected';
				}
				break;
			case 2:
				return 'connected';
				break;
		}
		
	}
	
	public function setConnected($bConn) 
    {
         $this->bIsConnected = $bConn;
    }  	
	
		
	public function isRoot() 
	{
        return $this->bIsRoot;
    }
	
	public function getType()
	{
		switch ($this->iType) 
		{
			case 0:
				return 'cable';
				break;	
			case 1:
				return 'server';
				break;
			case 2:
				return 'echoes';
				break;
		}
	}
	
	public function setType($type)
	{
		$this->iType = $type;
	}


    public function setRoot($bRoot) 
    {
        $this->bIsRoot = $bRoot;
    }
	
	public function setState($sState)
	{
		$this->sState = $sState;
	}
	
	public function getState()
	{
		return $this->sState;
	}
	
	public function setServerNbClick($nbClick)
	{
		$this->iServerNbClick = $nbClick;
	}
	
	public function getServerNbClick()
	{
		return $this->iServerNbClick;
	}
	
	public function setX($x)
	{
		$this->iXindex = $x;
	}
	
	public function getX()
	{
		return $this->iXindex;
	}
	
	public function setY($y)
	{
		$this->iYindex = $y;
	}
	
	public function getY()
	{
		return $this->iYindex;
	}
        
	public function isInited()
	{
		return $this->bInited;
	}	
	
	public function setInited($init)
	{
		$this->bInited = $init;
	}
        
}
    
    
    
    
    
?>