<?php
require_once('cell.php');   
require_once('board.php');   
Class xmlParser
{
    private $dom;
	
	//private $XML = '../xml.xml';
	
	public function __construct($xml)
	{
		$this->dom = new DomDocument();
		//$this->dom->load($this->XML_FILE_NAME);
		$this->dom->load($xml);
		//$this->dom->validate();
	}
	 
	public function getBoardFromXml()
	{
		//abscice
		$width = $this->dom->getElementsByTagName('width')->item(0)->nodeValue;
		//ordonnée
		$length = $this->dom->getElementsByTagName('length')->item(0)->nodeValue;
		
		$nbOperators = $this->dom->getElementsByTagName('available')->item(0)->nodeValue;
		
		$listeCells = $this->dom->getElementsByTagName('cell');
		
		$wrapped = "";
		if ($this->dom->getElementsByTagName('wrapped')->item(0)->nodeValue == "true")
		{
			$wrapped = true;
		}	
		else
		{
			$wrapped = false;
		}	
	
		foreach($listeCells as $cell)
		{
			foreach ($cell->childNodes as $node)
			{
		        switch ($node->nodeName) 
		        {
					case 'posX':
						$posX = $node->nodeValue;
						break;
					case 'posY':
						$posY = $node->nodeValue;
						break;
					case 'directions':
						$directions = $node->nodeValue;
						break;
					case 'type':
						$type = $node->nodeValue;
						break;
					case 'state':
						$state = $node->nodeValue;
						break;
				}
			}
			
			$arrCells[$posX][$posY]=new Cell($directions,$type);
			
		}
		
		//print_r("wrap = ".$wrapped);
		
		return new Board($width,$length,$arrCells,$wrapped,$nbOperators,'Didacticiel');
	}
	
	
	
}
	
?>