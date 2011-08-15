<?php

require_once("../class/request.php");

session_name('game');
session_start();

$db = Request::get();

$scoresBest = $db->getScoresBest($_SESSION['user']->id);
$scoreRace = $db->getScoresRace($_SESSION['user']->id);	

$buffer = '<?xml version="1.0" encoding="UTF-8" ?>
<scores>
    <user>
        <usr_first_name>'.$_SESSION['user']->first_name.'</usr_first_name>
        <usr_last_name>'.$_SESSION['user']->last_name.'</usr_last_name>
    </user>
    <race>
        <sra_score>'.$scoreRace['SRA_SCORE'].'</sra_score>
        <sra_mpl>'.$scoreRace['SRA_MOST_PLAYED_LEVEL'].'</sra_mpl>
    </race>
';
$i = 0;

foreach($scoresBest as $score)
{
	$buffer .= '    <best_'.$i.'>
        <sbe_level_'.$i.'>'.utf8_encode($score['SBE_LEVEL']).'</sbe_level_'.$i.'>
        <sbe_score_'.$i.'>'.$score['SBE_SCORE'].'</sbe_score_'.$i.'>
        <sbe_nb_clic_'.$i.'>'.$score['SBE_NB_CLIC'].'</sbe_nb_clic_'.$i.'>
        <sbe_percent_satisf_'.$i.'>'.$score['SBE_PERCENT_SATISF'].'</sbe_percent_satisf_'.$i.'>
        <sbe_nb_played_'.$i.'>'.$score['SBE_NB_PLAYED'].'</sbe_nb_played_'.$i.'>
        <sbe_nb_win_'.$i.'>'.$score['SBE_NB_WIN'].'</sbe_nb_win_'.$i.'>
    </best_'.$i.'>
';
	$i++;
}

$buffer .= '</scores>';

header('Content-Type: text/xml');
echo $buffer;
?>