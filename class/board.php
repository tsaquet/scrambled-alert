<?php

require_once('cell.php');
require_once('scoresManager.php');

class Board
{
	// size : number of cells
	private $iSizex;
	private $iSizey;
	
	private $iNbOperators;
	private $iNbBusyOperators;
	private $arrOperatorsX;
	private $arrOperatorsY;
	
	// whether the board is wrapped
	private $bWrapped;
	private $sState;
	private $arrCells;
	private $bFourDirections;
	
	//cells connected to root
	private $arrToBeUpdatedCells;
	private $arrConnectingCells;
	
	//private $arrNonConnectingCells;
	private $arrOperators;
	private $arrOpNbClick;
	
	private $sLevel;
	
	private $nbServer;
	
	//set in checkServersStatus
	private $bIsSolved;
	
	
	
	//public function __construct($sizex,$sizey)
	//{
	//	$this->iSizex = $sizex;
	//	$this->iSizey = $sizey;
	//	$this->arrCells = array();
	//}
	
	public function __construct($sizex,$sizey,$cells,$wrapped,$nbOperators,$level)
	{
		$this->iSizex = $sizex;
		$this->iSizey = $sizey;
		$this->bWrapped = $wrapped;
		$this->sLevel = $level;
		$this->bIsSolved = false;
		if ($nbOperators > 8)
		{
			$nbOperators = 8;
		}
		$this->iNbOperators = $nbOperators;
		for ($k = 1 ; $k <= $nbOperators; $k++)
		{
			$this->arrOperators[$k]='available';
			$this->arrOperatorsX[$k]= "";
			$this->arrOperatorsY[$k]= "";
			$this->arrOpNbClick[$k]=0;
		}

		$this->arrCells = $cells;
		for ($i = 0 ; $i <= $sizex ; $i++)
		{
			$this->arrCells[$i][0] = new Cell("____",'cable');
			$this->arrCells[$i][0]->setInited(true);
			$this->arrCells[$i][$sizey+1] = new Cell("____",'cable');
			$this->arrCells[$i][$sizey+1]->setInited(true);
		}
		for ($j = 0 ; $j <= $sizey ; $j++)
		{
			$this->arrCells[0][$j] = new Cell("____",'cable');
			$this->arrCells[0][$j]->setInited(true);
			$this->arrCells[$sizex+1][$j] = new Cell("____",'cable');
			$this->arrCells[$sizex+1][$j]->setInited(true);
		}
		$this->countServers();
	}
	
	
	
	/**
     * Scan the board to see which cells are connected to the server.
     * Update the state of every cell accordingly.  This function is
     * called each time a cell is rotated, to re-compute the connectedness
     * of every cell.
     * 
     * @return                                  true iff one or more cells have been
     *                                                  connected that previously weren't.
     */
    public function updateConnections()
    {
		
        // Reset the array of connected flags per cell.
        for ($x1 = 1 ; $x1 <= $this->iSizex ; $x1++)
		{
			for ($y1 = 1 ; $y1 <= $this->iSizey ; $y1++)
			{
				$this->arrCells[$x1][$y1]->setX($x1);
				$this->arrCells[$x1][$y1]->setY($y1);
				//print_r($x1."/".$y1." ");
				$this->arrCells[$x1][$y1]->setConnected(false);
				// Clear the list of cells which are connected 
				$this->arrToBeUpdatedCells[$x1][$y1] = $this->arrCells[$x1][$y1];
				unset($this->arrConnectingCells[$x1][$y1]);
				unset($this->arrNonConnectingCells[$x1][$y1]);
			}	
		}

		//$this->arrToBeUpdatedCells = $this->arrCells;

    	
    	
		
 		//$this->arrConnectingCells[][] = "";
		
		// set root connected
 		for ($x2 = 1 ; $x2 <= $this->iSizex ; $x2++)
		{
			for ($y2 = 1 ; $y2 <= $this->iSizey ; $y2++)
			{
				$cell = $this->arrCells[$x2][$y2];
				if ($cell->isRoot())
				{

					$this->setConnected($cell,$x2,$y2);
					//recursive
					$this->checkCellConnections($cell,$x2,$y2);
				}
			}	
		}
		
		//update
		for ($x3 = 1 ; $x3 <= $this->iSizex ; $x3++)
		{
			for ($y3 = 1 ; $y3 <= $this->iSizey ; $y3++)
			{
				if (isset($this->arrConnectingCells[$x3][$y3]))
				{
					$this->arrCells[$x3][$y3]->setConnected(true);
				}
				else
				{
					$this->arrCells[$x3][$y3]->setConnected(false);
				}
			}
		}	
    }
	
	/**
	 * 
	 */
	private function checkCellConnections($cell,$x,$y)
	{
		$directions = $cell->getCurrentDirections();
		//up
		if (($directions & "1000") != 0)
		{
			//print_r("jepasseenhaut");
			$nextCell = $this->getNextCell($cell,'U');
			if ((get_class($nextCell) == "Cell") && isset($this->arrToBeUpdatedCells[$nextCell->getX()][$nextCell->getY()]))
			{
				$nextCellDir = $nextCell->getCurrentDirections();
				if (($nextCellDir & "0010") != 0)
				{
					$this->setConnected($nextCell,$nextCell->getX(),$nextCell->getY());
					$this->checkCellConnections($nextCell,$nextCell->getX(),$nextCell->getY());
				}
				else
				{
					$this->setNotConnected($nextCell,$nextCell->getX(),$nextCell->getY());	
				}
			}
		}
		
		//right
		if (($directions & "0100") != 0)
		{
			//print_r("jepasseadroite");
			$nextCell = $this->getNextCell($cell,'R');
			if ((get_class($nextCell) == "Cell") && isset($this->arrToBeUpdatedCells[$nextCell->getX()][$nextCell->getY()]))
			{
				$nextCellDir = $nextCell->getCurrentDirections();
				if (($nextCellDir & "0001") != 0)
				{
					$this->setConnected($nextCell,$nextCell->getX(),$nextCell->getY());
					$this->checkCellConnections($nextCell,$nextCell->getX(),$nextCell->getY());
				}
				else
				{
					$this->setNotConnected($nextCell,$nextCell->getX(),$nextCell->getY());	
				}
			}
		}
		
		//down
		if (($directions & "0010") != 0)
		{
			//print_r("jepasseenbas");
			$nextCell = $this->getNextCell($cell,'D');
			if ((get_class($nextCell) == "Cell") && isset($this->arrToBeUpdatedCells[$nextCell->getX()][$nextCell->getY()]))
			{
				$nextCellDir = $nextCell->getCurrentDirections();
				if (($nextCellDir & "1000") != 0)
				{
					$this->setConnected($nextCell,$nextCell->getX(),$nextCell->getY());
					$this->checkCellConnections($nextCell,$nextCell->getX(),$nextCell->getY());
				}
				else
				{
					$this->setNotConnected($nextCell,$nextCell->getX(),$nextCell->getY());	
				}
			}
		}
		
		//left
		if (($directions & "0001") != 0)
		{
			//print_r("jepasseagauche");
			$nextCell = $this->getNextCell($cell,'L');
			if ((get_class($nextCell) == "Cell") && isset($this->arrToBeUpdatedCells[$nextCell->getX()][$nextCell->getY()]))
			{
				$nextCellDir = $nextCell->getCurrentDirections();
				if (($nextCellDir & "0100") != 0)
				{
					$this->setConnected($nextCell,$nextCell->getX(),$nextCell->getY());
					$this->checkCellConnections($nextCell,$nextCell->getX(),$nextCell->getY());
				}
				else
				{
					$this->setNotConnected($nextCell,$nextCell->getX(),$nextCell->getY());	
				}
			}
		}
		
	}
	
	private function setConnected($cell,$x,$y)
	{
		//print_r("connecté : " . $x.$y."<br>");
		$this->arrConnectingCells[$x][$y] = $cell;
		//$this->arrConnectingCells[$x][$y]->setConnected(true);
		unset($this->arrToBeUpdatedCells[$x][$y]);
	}
	
	private function setNotConnected($cell,$x,$y)
	{
		//print_r($cell. " x : " . $x . " y : ". $y);
		//$this->arrNotConnectingCells[$x][$y] = $cell;
		//$this->arrNotConnectingCells[$x][$y]->setConnected(false);
		//unset($this->arrToBeUpdatedCells[$x][$y]);
	}
	
	public function getChangedCells($previousConnectingCells,$newConnectingCells,$xClicked,$yClicked, $arrCorrupted,$arrRepaired)
	{
		$changedCells = "";
		for ($x = 1 ; $x <= $this->iSizex ; $x++)
		{
			for ($y = 1 ; $y <= $this->iSizey ; $y++)
			{
				if ((isset($previousConnectingCells[$x][$y])&& !isset($newConnectingCells[$x][$y])) 
				|| !isset($previousConnectingCells[$x][$y])&& isset($newConnectingCells[$x][$y]))
				{
					$changedCells .= '
    <cell>
        <posX>'.$x.'</posX>
        <posY>'.$y.'</posY>
        <directions>'.$this->arrCells[$x][$y]->getStringCurrentDirections().'</directions>
        <type>'.$this->arrCells[$x][$y]->getType().'</type>
        <state>'.$this->arrCells[$x][$y]->getStringConnected().'</state>
    </cell>
';
				}
				if (($xClicked == $x) && ($yClicked == $y))
				{
					$changedCells .= '
    <cell>
        <posX>'.$x.'</posX>
        <posY>'.$y.'</posY>
        <directions>'.$this->arrCells[$x][$y]->getStringCurrentDirections().'</directions>
        <type>'.$this->arrCells[$x][$y]->getType().'</type>
        <state>'.$this->arrCells[$x][$y]->getStringConnected().'</state>
    </cell>
';
				}
			}	
		}
		if (isset($arrCorrupted)) 
		{
			$changedCells .= '<cell>
										<posX>'.$arrCorrupted[0].'</posX>
								        <posY>'.$arrCorrupted[1].'</posY>
								        <directions>'.$this->arrCells[$arrCorrupted[0]][$arrCorrupted[1]]->getStringCurrentDirections().'</directions>
								        <type>'.$this->arrCells[$arrCorrupted[0]][$arrCorrupted[1]]->getType().'</type>
								        <state>'.$this->arrCells[$arrCorrupted[0]][$arrCorrupted[1]]->getStringConnected().'</state>
								      </cell>
								      ';
		}
		if (isset($arrRepaired)) 
		{
			foreach ($arrRepaired as $serv)
			{
				$changedCells .= '<cell>
										<posX>'.$serv[0].'</posX>
								        <posY>'.$serv[1].'</posY>
								        <directions>'.$this->arrCells[$serv[0]][$serv[1]]->getStringCurrentDirections().'</directions>
								        <type>'.$this->arrCells[$serv[0]][$serv[1]]->getType().'</type>
								        <state>'.$this->arrCells[$serv[0]][$serv[1]]->getStringConnected().'</state>
								      </cell>
								      ';
			}
		}
		
		return $changedCells;
	}
	
	public function getConnectingCells()
	{
		return $this->arrConnectingCells;
	}
	
	public function getCells()
	{
		return $this->arrCells;
	}
	
	public function setCells($cells)
	{
		$this->arrCells = $cells;
	}
	
	public function isSolved()
	{
		$servers = array();
		for ($x = 1 ; $x <= $this->iSizex ; $x++)
		{
			for ($y = 1 ; $y <= $this->iSizey ; $y++)
			{
				if ($this->arrCells[$x][$y]->getType() == 'server')
				{
					$servers[] = $this->arrCells[$x][$y];
				}
			}
		}
		$win = true;
		foreach($servers as $server)
		{
			$win = $win && $server->isConnected();
		}
		$this->bIsSolved = $win;
	}
	
	/**
	 * Check the board state.
	 * @return status
	 */
	public function checkStatus($percentSatisfied)
	{
		$win = $this->bIsSolved;
		
		$lost = false;
		if ($win && $percentSatisfied <= 49)
		{
			$lost  = true;
		}
		
		
		if ($lost)
		{
			//$scoresManager = ScoresManager::get();
			//$scoresManager->lost($_SESSION['user']->id,$this->sLevel);
			return 'loose';
		}
		else if ($win)
		{
			//$scoresManager = ScoresManager::get();
			//$scoresManager->win($_SESSION['user']->id,$this->sLevel,$_SESSION['nbClic'],$_SESSION['prtSatisfied']);
			return 'win';
		}
		else
		{
			return 'play';
		}	
	}

	public function checkPrtSatisfied()
	{
		$servers = array();
		for ($x = 1 ; $x <= $this->iSizex ; $x++)
		{
			for ($y = 1 ; $y <= $this->iSizey ; $y++)
			{
				if ($this->arrCells[$x][$y]->getType() == 'server')
				{
					$servers[] = $this->arrCells[$x][$y];
				}
			}
		}
		$nbServ = 0;
		$nbSafe = 0;
		foreach($servers as $server)
		{
			$nbServ++;
			if ($server->getState() == "safe")
			{
				$nbSafe++;
			}
		}
		
		$res = $nbSafe/$nbServ*100;
		
		$_SESSION['prtSatisfied'] = $res;
		
		return $res;
		
	}
	
	public function checkServersStatus()
	{
		$this->isSolved();
		$fini = false;
		// For each cell (double for loop)
		for ($x = 1 ; $x <= $this->iSizex ; $x++)
		{
			for ($y = 1 ; $y <= $this->iSizey ; $y++)
			{
				// the only intersting case is when it's a server
				if ($this->arrCells[$x][$y]->getType() == "server")
				{
					if ($this->arrCells[$x][$y]->getState() == "safe")
					{
						$this->arrCells[$x][$y]->setServerNbClick($this->arrCells[$x][$y]->getServerNbClick() + 1);
						$probaToBeCorrupted = 0;
						if ($this->arrCells[$x][$y]->getServerNbClick() >= 8)
						{
							// random between 1 and nb of server
							$probaToBeCorrupted = mt_rand(1,$this->nbServer);
						}
						if ($this->arrCells[$x][$y]->getServerNbClick() >= 12)
						{
							$probaToBeCorrupted = mt_rand(1,3);
						}
						if ($probaToBeCorrupted == 1)
						{
							$this->arrCells[$x][$y]->setServerNbClick(0);
							$this->arrCells[$x][$y]->setState("corrupted");
							for ($x1 = 1 ; $x1 <= $this->iSizex ; $x1++)
							{
								for ($y1 = 1 ; $y1 <= $this->iSizey ; $y1++)
								{
									$this->arrCells[$x1][$y1]->setServerNbClick(mt_rand(0,8));
								}
							}
							$res = array();
							$res[] = $x;
							$res[] = $y;
							return $res;
							//a virer ?
							$fini = true;
							break;
						}
					}
				}
			}
			if ($fini)
			{
				break;
			}
		}
	}
	
	public function repairServers()
	{
		$res = array();
		$indexRes = 0;
		// For each cell (double for loop)
		for ($x = 1 ; $x <= $this->iSizex ; $x++)
		{
			for ($y = 1 ; $y <= $this->iSizey ; $y++)
			{
				// the only intersting case is when it's a server
				if ($this->arrCells[$x][$y]->getType() == "server")
				{
					if ($this->arrCells[$x][$y]->getState() == "corrupted")
					{
						if ($this->arrCells[$x][$y]->isConnected())
						{
							// check whether an operator is already repairing
							$idOperator = 0;
							for ($k = 1 ; $k <= $this->iNbOperators ; $k++) 
							{
								if (($this->arrOperatorsX[$k]==$x) 
									&& ($this->arrOperatorsY[$k]==$y))
								{
									$idOperator = $k;
									break;
								}
							}
							if ($idOperator == 0)
							{
								//check whether an operator is available
								for ($k = 1 ; $k <= $this->iNbOperators ; $k++)
								{
									if ($this->arrOperators[$k]=='available')
									{
										$this->arrOperators[$k]='busy';
										$this->arrOperatorsX[$k]=$x;
										$this->arrOperatorsY[$k]=$y;
										$this->arrOpNbClick[$k]=0;
										break;
									}
								}
							}
							else
							{
								$this->arrOpNbClick[$k] = $this->arrOpNbClick[$k] + 1;
							}
							
						}
						else
						{
							//if not connected but operator already began to repair, he continues
							for ($k = 1 ; $k <= $this->iNbOperators ; $k++) 
							{
								if (($this->arrOperators[$k]=='busy') && ($this->arrOperatorsX[$k]==$x) && ($this->arrOperatorsY[$k]==$y))
								{
									$this->arrOpNbClick[$k] = $this->arrOpNbClick[$k] + 1;
									break;
								}
							}
						}
						
						//check whether the server is repaired connected or not
						for ($k = 1 ; $k <= $this->iNbOperators ; $k++) 
						{
							if (($this->arrOperatorsX[$k]==$x) 
								&& ($this->arrOperatorsY[$k]==$y))
							{
								if ($this->arrOpNbClick[$k] >= 9)
								{
									$this->arrCells[$x][$y]->setState("safe");
									$this->arrOperatorsX[$k] = "";
									$this->arrOperatorsY[$k] = "";
									$this->arrOpNbClick[$k]=0;
									$this->arrOperators[$k]='available';
									$res[$indexRes] = array($x,$y);
									$indexRes++;
									break;
								}
							}
						}
					}

				}
			}
		}
		if ($this->bIsSolved)
		{
			for ($k = 1 ; $k <= $this->iNbOperators ; $k++) 
			{
				if (($this->arrOperators[$k]=='busy') && ($this->arrOpNbClick[$k]>=0))
				{
					//$f = @fopen("./toto", 'a+');
					//fwrite($f, $k);
					//fwrite($f, $this->arrOperators[$k]);
					//fwrite($f, $this->arrOpNbClick[$k]);
					//fwrite($f, $this->arrOperatorsX[$k]);
					//fwrite($f, "\n");
					//fclose($f);
					$this->arrCells[$this->arrOperatorsX[$k]]
						[$this->arrOperatorsY[$k]]->setState("safe");
					$res[] = array($this->arrOperatorsX[$k],$this->arrOperatorsY[$k]);
				}
			}
		} 
		return $res;
	}
		
	
	
	/**
	 * Getters & Setters.
	 */
	 public function setSState($state)
	 {
	 	$this->sState = $state;
	 }
	 
	 public function getSState()
	 {
	 	return $this->sState;
	 }
	 
	 public function getNbOperators()
	 {
	 	return $this->iNbOperators;
	 }
	 
	 public function getNbOperatorsAv()
	 {
	 	$count = 0;
	 	for ($k = 1 ; $k <= $this->iNbOperators ; $k++) 
		{
			if ($this->arrOperators[$k] == 'available')
			{
				$count++;
			}
		}
	 	return $count;
	 }
	 
	 public function isWrapped()
	 {
	 	return $this->bWrapped;
	 }
	 
	 private function getNextCell($cell,$dir)
	 {
		if (get_class($cell) != "Cell")
		{
			return null;
		}
		//print_r("wrapped : ".$this->isWrapped());
		if ($this->isWrapped())
		{
			//print_r("wrapped");
			// x - 1
			if ($cell->getX() == 1)
				$xm1= $this->iSizex;
			else 	
				$xm1= $cell->getX() - 1;
			// x + 1
			if ($cell->getX() == $this->iSizex)
				$xp1= 1;
			else 	
				$xp1= $cell->getX() + 1;
			// y - 1
			if ($cell->getY() == 1)
				$ym1= $this->iSizey;
			else 	
				$ym1= $cell->getY() - 1;	
			// y + 1
			if ($cell->getY() == $this->iSizey)
				$yp1= 1;
			else 	
				$yp1= $cell->getY() + 1;
		}
		else
		{
			// x - 1
			$xm1= $cell->getX() - 1;
			// x + 1
			$xp1= $cell->getX() + 1;
			// y - 1
			$ym1= $cell->getY() - 1;	
			// y + 1
			$yp1= $cell->getY() + 1;
		}
		
		switch ($dir) 
		{
		case 'U':
			if ($ym1 == 0)
			{
				return null;
			}
			return $this->arrCells[$cell->getX()][$ym1];
			break;
		case 'R':
			if ($xp1 == $this->iSizex+1)
			{
				return null;
			}
			return $this->arrCells[$xp1][$cell->getY()];
			break;
		case 'D':
			if ($yp1 == $this->iSizey+1)
			{
				return null;
			}
			return $this->arrCells[$cell->getX()][$yp1];
			break;
		case 'L':
			if ($xm1 == 0)
			{
				return null;
			}
			return $this->arrCells[$xm1][$cell->getY()];
			break;
		default :
			return null;
		}
	}

	function randomize()
	{
		for ($x1 = 1 ; $x1 <= $this->iSizex ; $x1++)
		{
			for ($y1 = 1 ; $y1 <= $this->iSizey ; $y1++)
			{
				$xMax = mt_rand(0,3);
				for ($x = 0; $x <= $xMax ; $x++)
				{
					$this->arrCells[$x1][$y1]->rotateClockwise();
				}
			}	
		}
		$this->updateConnections();
		
	}
	
	public function countServers()
	{
		$count = 0;
		for ($x = 1 ; $x <= $this->iSizex ; $x++)
		{
			for ($y = 1 ; $y <= $this->iSizey ; $y++)
			{
				// the only interesting case is when it's a server
				if ($this->arrCells[$x][$y]->getType() == "server")
				{
					$count++;
				}
			}
		}
		$this->nbServer = $count;
	}
}

?>