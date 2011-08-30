<?php
	require_once('../class/cell.php');
	require_once('../class/board.php');
	require_once('../class/boardGenerator.php');
	require_once('../class/level.php');
	require_once('../class/xmlParser.php');
	
	session_name('game');
	session_start();
	
	if (isset($_POST['level']))
	{
		if (is_int(intval($_POST['level'])) && (intval($_POST['level']) > 0))
		{
			initTuto($_POST['level']);	
			$handle = fopen('../levels/level'.$_POST['level'].'.xml', "r" );
			while (!feof($handle)) 
			{
				$line = fgets($handle);
				print $line;
			}
			fclose($handle);
		}
		else if ($_POST['level'] == 'novice')
		{
			$level = new Level($_POST['level'],4,4,false,3,1);
			init($level);
		}
		else if ($_POST['level'] == 'normal')
		{
			$level = new Level($_POST['level'],6,6,false,3,2);
			init($level);
		}
		else if ($_POST['level'] == 'expert')
		{
			$level = new Level($_POST['level'],8,7,true,4,3);
			init($level);
		}
		else if ($_POST['level'] == 'maitre')
		{
			$level = new Level($_POST['level'],9,9,true,4,3);
			init($level);
		}
		else
		{
			initTuto(1);
		}
	}
	else
	{
		initTuto(1);
		$handle = fopen('../levels/level1.xml', "r" );
		while (!feof($handle)) 
		{
			$line = fgets($handle);
			print $line;
		}
		fclose($handle);
	}
	
	function initTuto($level)
	{
		$xmlParser = new xmlParser("../levels/level".$level.".xml");
		$_SESSION['board'] = $xmlParser->getBoardFromXml();
		$_SESSION['nbClic'] = 0;
		//print_r($board);
		header('Content-Type: text/xml');
	}

	function init($level)
	{
		unset ($bGenerator);
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
		
		$bGenerator->getBoard()->countServers();
		
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
				<available>'.$bGenerator->getCurrentLevel()->getOperatorNumber().'</available>
	            <busy>0</busy>
	        </operators>
	        <users>
	            <satisfied>100</satisfied>
	            <dissatisfied>0</dissatisfied>
	   		</users>
		</people>
	    <cells>
	    ';
		$buffer .= $cells;
		
		$buffer .= '
		</cells>
	</board>';
	
		//echo '<pre>';
		$_SESSION['board'] = $bGenerator->getBoard();
		$_SESSION['nbClic'] = 0;
		$_SESSION['prtSatisfied'] = 0;
		header('Content-Type: text/xml');
		echo $buffer;
		//echo '</pre>';
	}
	
	
	
	
?>