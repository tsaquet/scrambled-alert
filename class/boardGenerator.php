<?php
require_once('board.php');   
require_once('cell.php');   
require_once('level.php');   

class BoardGenerator
{
	private $currentLevel;
	
	private $board;
	
	private $arrCells;
	
	public function __construct($level)
	{
		$this->currentLevel = $level;
	}
	
	public function generateBoard()
	{
		unset($this->board);
		$res = false;
		while (!$res)
		{
			unset($this->arrCells);
			$res = $this->createCells();	
		}
		
		$this->board = new Board($this->currentLevel->getSizeX(),$this->currentLevel->getSizeY(),$this->arrCells,$this->currentLevel->isWrapped(),$this->currentLevel->getOperatorNumber(),$this->currentLevel->getName());
		$this->board->randomize();
	}
	
	public function getBoard()
	{
		return $this->board;
	}
	
	/**
	 * Board initialisation.
	 */
	public function createCells()
	{
		
		for ($x = 1 ; $x <= $this->currentLevel->getSizeX() ; $x++)
		{
			for ($y = 1 ; $y <= $this->currentLevel->getSizeY() ; $y++)
			{
				$cell = new Cell("____", 'cable');
				$cell->setX($x);
				$cell->setY($y);
				$cell->setInited(false);
				$this->arrCells[$x][$y] = $cell;
				
			}
		}
		
		
		//set root cell
		$cellRootCell = new Cell("____",'echoes');
		$this->addRandomDirRoot($cellRootCell);
		//FIXME !!!!!
		$cellRootCell->setX(mt_rand(1,$this->currentLevel->getSizeX()));
		$cellRootCell->setY(mt_rand(1,$this->currentLevel->getSizeY()));
		//$cellRootCell->setX(2);
		//$cellRootCell->setY(2);
		$cellRootCell->setRoot(true);
		$cellRootCell->setInited(true);
		$this->arrCells[$cellRootCell->getX()][$cellRootCell->getY()] = $cellRootCell;
		
		
		//check whether root cell direction are ok
		$resTemp = false;
		
		//recursive
		if (($cellRootCell->getCurrentDirections() & "1000") != 0)
		{
			if (!$this->initCellNextToRoot($this->getNextCell($cellRootCell, 'U'),"1000"))
			{
				$cellRootCell->removeDir("0111");
			}
			else
			{
				$resTemp = true;
			}
		}
		if (($cellRootCell->getCurrentDirections() & "0100") != 0) 
		{
			if (!$this->initCellNextToRoot($this->getNextCell($cellRootCell, 'R'),"0100"))
			{
				$cellRootCell->removeDir("1011");
			}
			else
			{
				$resTemp = true;
			}
		}
		if (($cellRootCell->getCurrentDirections() & "0010") != 0)
		{
			if (!$this->initCellNextToRoot($this->getNextCell($cellRootCell, 'D'),"0010"))
			{
				$cellRootCell->removeDir("1101");
			}
			else
			{
				$resTemp = true;
			}
		}
		if (($cellRootCell->getCurrentDirections() & "0001") != 0) 
		{
			if (!$this->initCellNextToRoot($this->getNextCell($cellRootCell, 'L'),"0001"))
			{
				$cellRootCell->removeDir("1110");
			}
			else
			{
				$resTemp = true;
			}
		}
		$this->arrCells[$cellRootCell->getX()][$cellRootCell->getY()] = $cellRootCell;
		if (!$resTemp)
		{
			return false;
		}
		return $resTemp;
					
	}

	private function initCellNextToRoot($nextCell,$dirToRevert)
	{
		if (get_class($nextCell) != "Cell")
		{
			return false;
		}
		if ((!$nextCell->isInited()) && ($this->initCellConnections($nextCell)))
		{
			$nextCell->addDir($this->reverseDirection($dirToRevert));
			$this->arrCells[$nextCell->getX()][$nextCell->getY()] = $nextCell;
			return true;
		}
		else
		{
			return false;	
		}
	}

	private function initCellConnections($cell)
	{
		if (get_class($cell) != "Cell")
		{
			print_r("out");
			return false;
		}
		unset($randomCell0);
		unset($randomCell1);
		unset($randomCell2);
		
		$cell->setInited(true);
		$this->arrCells[$cell->getX()][$cell->getY()] = $cell;

        // 50% of the time, add a second direction, if we can
        // find one.
        if ($this->rand50())
		{
			$randomCell0 = $this->addRandomDir($cell);
		}
		
        if ($this->rand50())
		{
            $randomCell1 = $this->addRandomDir($cell);
			// A third pass makes networks more complex, but also
            // introduces 4-way crosses.
            if ($this->currentLevel->getMaxConnections() > 3 && $this->rand50())
			{
                $randomCell2 = $this->addRandomDir($cell);
			}
		}

		$this->arrCells[$cell->getX()][$cell->getY()] = $cell;
		if (isset($randomCell0) && (get_class($randomCell0) == "Cell"))
		{
			$this->initCellConnections($randomCell0);
		}
		
		if (isset($randomCell1) && (get_class($randomCell1) == "Cell"))
		{
			$this->initCellConnections($randomCell1);
			if (isset($randomCell2) && (get_class($randomCell2) == "Cell"))
			{
				$this->initCellConnections($randomCell2);
			}
		}
		return true;
	}
	
	private function addRandomDirRoot($cell)
	{
		$dir = "1000";	
		if ($this->rand50())
		{
			$dir = $dir | "0100";
		}
		
		if ($this->rand50())
		{
			$dir = $dir | "0010";
		}
		
		if ($this->currentLevel->getMaxConnections() > 3 && $this->rand50())
		{
			$dir = $dir | "0001";
		}
		
		$iMax = rand (1,4);
		for ($i = 1 ; $i <= $iMax; $i++)
		{
			$dir = $this->rotateClockwise($dir);
		}
		
		$cell->setBaseDirections($dir);
	}
	
	/**
     * Add a connection in a random direction from the first cell
     * in the given cell connectingCells.  We enumerate the free adjacent
     * cells around the starting cell, then pick one to connect to at random.
     * If there is no free adjacent cell, we do nothing.
     * 
     * If we connect to a cell, it is added to the passed-in connectingCells.
     * 
     * @param   list                    Current list of cells awaiting connection.
     */
	private function addRandomDir($cell)
	{
		if (get_class($cell) != "Cell")
		{
			return false;
		}
		
		$randomCell = "";
		$nbTry = 0;
		while(!is_object($randomCell) &&  ($nbTry < 25))
		{
			$nbTry++;
			switch (mt_rand(1,4)) 
			{
			case 1:
				$randomCell	= $this->getNextCell($cell,'U');
				$dir = "1000";
				break;
			case 2: 
				$randomCell	= $this->getNextCell($cell,'R');
				$dir = "0100";
				break;
			case 3: 
				$randomCell	= $this->getNextCell($cell,'D');
				$dir = "0010";
				break;
			case 4: 
				$randomCell	= $this->getNextCell($cell,'L');
				$dir = "0001";
				break;
			}
			
			if (is_object($randomCell) && get_class($randomCell) == "Cell")
			{
				if ($randomCell->isInited())
				{
					$randomCell = "";
				}
			}
			
		}
		
		if (is_object($randomCell) && get_class($randomCell) == "Cell")
		{
			$cell->addDir($dir);
			$this->arrCells[$cell->getX()][$cell->getY()] = $cell;
			$randomCell->addDir($this->reverseDirection($dir));
			$randomCell->setInited(true);
			$this->arrCells[$randomCell->getX()][$randomCell->getY()] = $randomCell;
			return $randomCell;
		}
	}
	
	public function rand50()
	 {
		if (mt_rand(0,1) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	 
	public function reverseDirection($dir) 
	{
		$arrReverseDir = array(
					"0001"    => "0100",
					"0010"    => "1000",
					"0100"    => "0001",
					"1000"    => "0010");
        return $arrReverseDir[$dir];
    }
	
	private function rotateClockwise($dir) 
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
        return $arrClockWise[$dir];
    }
	
	private function getNextCell($cell,$dir)
	{
		if (get_class($cell) != "Cell")
		{
			return null;
		}
		if ($this->currentLevel->isWrapped())
		{
			// x - 1
			if ($cell->getX() == 1)
				$xm1= $this->currentLevel->getSizeX();
			else 	
				$xm1= $cell->getX() - 1;
			// x + 1
			if ($cell->getX() == $this->currentLevel->getSizeX())
				$xp1= 1;
			else 	
				$xp1= $cell->getX() + 1;
			// y - 1
			if ($cell->getY() == 1)
				$ym1= $this->currentLevel->getSizeY();
			else 	
				$ym1= $cell->getY() - 1;	
			// y + 1
			if ($cell->getY() == $this->currentLevel->getSizeY())
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
			if ($xp1 == $this->currentLevel->getSizeX()+1)
			{
				return null;
			}
			return $this->arrCells[$xp1][$cell->getY()];
			break;
		case 'D':
			if ($yp1 == $this->currentLevel->getSizeY()+1)
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
		}
	}

	function setServers()
	{
		for ($x = 1 ; $x <= $this->currentLevel->getSizeX() ; $x++)
		{
			for ($y = 1 ; $y <= $this->currentLevel->getSizeY() ; $y++)
			{
				$cell = $this->arrCells[$x][$y];
				
				if ($cell->getType() != "echoes")
				{
					if (($cell->getCurrentDirections() == ("1000")) 
						||($cell->getCurrentDirections() == ("0100")) 
						||($cell->getCurrentDirections() == ("0010")) 
						||($cell->getCurrentDirections() == ("0001")))
					{
						$cell->setType(1);
						$cell->setState("safe");
					}
				}
				$this->arrCells[$x][$y] = $cell;			
			}
		}
	}
	
	function countEmptyCells()
	{
		$count = 0;
		for ($x = 1 ; $x <= $this->currentLevel->getSizeX() ; $x++)
		{
			for ($y = 1 ; $y <= $this->currentLevel->getSizeY() ; $y++)
			{
				$cell = $this->arrCells[$x][$y];
				
				if ($cell->getCurrentDirections() == ("0000")) 
				{
					$count++;
				}	
			}
		}
		return $count;
	}
	
	function getCurrentLevel()
	{
		return $this->currentLevel;
	}
}
      
?>