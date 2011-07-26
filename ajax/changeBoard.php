<?php
require_once('../class/cell.php');
require_once('../class/board.php');
require_once('../class/xmlParser.php');

session_name('game');
session_start();

if (isset($_SESSION['nbClic']))
{
	$_SESSION['nbClic']++;
}

if (isset($_SESSION['board']))
{
	$_SESSION['board']->updateConnections();
	$previousConnectingCells = $_SESSION['board']->getConnectingCells();
	//print_r("<pre>");
	//print_r($previousConnectingCells);
	//print_r("</pre>");
	
	$arrCells = $_SESSION['board']->getCells();
	
	if ((isset($_POST['posX'])) && (isset($_POST['posY'])) && (isset($_POST['rotation'])))
	{
		if ($_POST['rotation'] == 1)
		{
			$arrCells[$_POST['posX']][$_POST['posY']]->rotateClockwise();
		}
		else
		{
			$arrCells[$_POST['posX']][$_POST['posY']]->rotateCounterClockwise();
		}
	}
	$_SESSION['board']->setCells($arrCells);
	$_SESSION['board']->updateConnections();
	$newConnectingCells = $_SESSION['board']->getConnectingCells();
	
	//print_r("<pre>");
	//print_r($newConnectingCells);
	//print_r("</pre>");
	
	$arrCorrupted = $_SESSION['board']->checkServersStatus();
	
	$arrRepaired = $_SESSION['board']->repairServers();
	
	$nbOp = $_SESSION['board']->getNbOperators();
	
	$opAvailable = $_SESSION['board']->getNbOperatorsAv();
	$opBusy = $nbOp - $opAvailable;
	
	$percentSatisfied = round($_SESSION['board']->checkPrtSatisfied());
	
	$status = $_SESSION['board']->checkStatus($percentSatisfied);
	
	$changedCells = $_SESSION['board']->getChangedCells($previousConnectingCells,$newConnectingCells,$_POST['posX'],$_POST['posY'],$arrCorrupted,$arrRepaired);
	
	$buffer = '<?xml version="1.0" encoding="UTF-8" ?>
<board>
    <status>'.$status.'</status>';
	$buffer .= $changedCells;
	
	$buffer .= '<people>
        <operators>
            <available>'.$opAvailable.'</available>
            <busy>'.$opBusy.'</busy>
        </operators>
        <users>
            <satisfied>'.$percentSatisfied.'</satisfied>
            <dissatisfied>'.(100 - $percentSatisfied).'</dissatisfied>
        </users>
    </people>
</board>';

	header('Content-Type: text/xml');
	echo $buffer;
	
}

?>