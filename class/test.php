<?php
	require_once('cell.php');
	require_once('board.php');
	require_once('boardGenerator.php');
	require_once('level.php');
	require_once('xmlParser.php');
	
	
	$level = new Level("test",5,5,true,3,1);
	
	$bGenerator = new BoardGenerator($level);
	$countEmptyCell = 10;
	$nbTry = 0;
	$maxEmptyCell = 0;
	while ($countEmptyCell > $maxEmptyCell)
	{
		$nbTry++;
		$bGenerator->generateBoard();
		$countEmptyCell = $bGenerator->countEmptyCells();
		if ($nbTry == 200)
		{
			$maxEmptyCell = 1;
		}
		if ($nbTry == 300)
		{
			$maxEmptyCell = 2;
		}
	}
	//print_r($nbTry);
	
	//print_r('<pre>');
	//print_r($bGenerator->getBoard()->getCells());
	//print_r('</pre>');
	
	$bGenerator->setServers();
	
	$bGenerator->getBoard()->updateConnections();
	
	$cells = "";
	for ($x = 1 ; $x <= $bGenerator->getCurrentLevel()->getSizeX() ; $x++) 
	{
		for ($y = 1 ; $y <= $bGenerator->getCurrentLevel()->getSizeY() ; $y++) 
		{
			$cellArray = $bGenerator->getBoard()->getCells();
			$cell = $cellArray[$x][$y];
			$cells .= '<cell>
            <posX>'.$cell->getX().'</posX>
            <posY>'.$cell->getY().'</posY>
            <directions>'.$cell->getStringCurrentDirections().'</directions>
            <type>'.$cell->getType().'</type>
            <state>'.$cell->getStringConnected().'</state>
          </cell>
      ';
		}
	}
	
	$wrapped = "false";
	if ($bGenerator->getCurrentLevel()->isWrapped())
	{
		$wrapped = "true";
	}
	
	$buffer = '<?xml version="1.0" encoding="UTF-8" ?>
<board>
    <status>play</status>
    <dimensions>
        <width>'.$bGenerator->getCurrentLevel()->getSizeX().'</width>
        <length>'.$bGenerator->getCurrentLevel()->getSizeY().'</length>
    </dimensions>
    <wrapped>'.$wrapped.'</wrapped>
    <people>
        <operators>
            <available>0</available>
            <busy>0</busy>
        </operators>
        <users>
            <satisfied>0</satisfied>
            <dissatisfied>100</dissatisfied>
   	    </users>
    </people>
    <cells>
        ';
	$buffer .= $cells;
	
	$buffer .= '</cells>
</board>';

    
    header('Content-Type: text/xml');
	//echo '<pre>';
	echo $buffer;
	//echo '</pre>';
	
?>