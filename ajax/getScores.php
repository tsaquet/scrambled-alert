<?php

require_once("../class/request.php");

session_name('game');
session_start();

$db = Request::get();

if ($_SESSION['user']->id != 0)
{
	$scoresBest = $db->getScoresBest($_SESSION['user']->id);
	$scoreRace = $db->getScoresRace($_SESSION['user']->id);
	
	$topScoresBeyondFriends = $db->topScoresBest(true);	
	$topScores = $db->topScoresBest(false);	
	
	
	$buffer = '<?xml version="1.0" encoding="UTF-8" ?>
	<scores>
		<guest>FALSE</guest>
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
		$_SESSION['score_level_'.strtolower($score['SBE_LEVEL'])]=$score;
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
	
	$i = 0;
	
	$token_full = explode("&",$_SESSION['token']);
	
	$token = explode("=",$token_full[0]);
	
	foreach($topScoresBeyondFriends as $top)
	{
		$picture = "https://graph.facebook.com/".$top['SBE_USR_ID']."/picture?access_token=".$token[1];
		$buffer .= '	<top_friend_'.$i.'>
			<tf_sbe_usr_img_'.$i.'>'.$picture.'</tf_sbe_usr_img_'.$i.'>
			<tf_sbe_usr_id_'.$i.'>'.$top['SBE_USR_ID'].'</tf_sbe_usr_id_'.$i.'>
	        <tf_sbe_level_'.$i.'>'.utf8_encode($top['SBE_LEVEL']).'</tf_sbe_level_'.$i.'>
	        <tf_sbe_score_'.$i.'>'.$top['SBE_SCORE'].'</tf_sbe_score_'.$i.'>
	    </top_friend_'.$i.'>
	';
		$i++;
	}
	
	$i = 0;
	foreach($topScores as $top)
	{
		$token_full = explode("&",$_SESSION['token']);
	
		$token = explode("=",$token_full[0]);
		
		$picture = "https://graph.facebook.com/".$top['SBE_USR_ID']."/picture?access_token=".$token[1];
		$buffer .= '    <top_'.$i.'>
			<t_sbe_usr_img_'.$i.'>'.$picture.'</t_sbe_usr_img_'.$i.'>
			<t_sbe_usr_id_'.$i.'>'.$top['SBE_USR_ID'].'</t_sbe_usr_id_'.$i.'>
	        <t_sbe_level_'.$i.'>'.utf8_encode($top['SBE_LEVEL']).'</t_sbe_level_'.$i.'>
	        <t_sbe_score_'.$i.'>'.$top['SBE_SCORE'].'</t_sbe_score_'.$i.'>
	    </top_'.$i.'>
	';
		$i++;
	}
	
	$buffer .= '</scores>';
}
elseif ($_SESSION['user']->id == 0)
{
	$buffer = '<?xml version="1.0" encoding="UTF-8" ?>
	<scores>
		<guest>TRUE</guest>
	    <user>
	        <usr_first_name>'.$_SESSION['user']->first_name.'</usr_first_name>
	        <usr_last_name></usr_last_name>
	    </user>
	</scores>';
}
else
{
	$buffer = '<?xml version="1.0" encoding="UTF-8" ?>
	<scores>
		<guest>TRUE</guest>
	    <user>
	        <usr_first_name>Guest</usr_first_name>
	        <usr_last_name></usr_last_name>
	    </user>
	</scores>';
}


header('Content-Type: text/xml');
echo $buffer;
?>