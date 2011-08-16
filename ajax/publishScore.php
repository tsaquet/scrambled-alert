<?php
require_once('../class/tools.php');

session_name('game');
session_start();

$token_full = explode("&",$_SESSION['token']);

$token = explode("=",$token_full[0]);

$result = "fail";

if (isset($_POST['level']))
{
	$result = $_POST['level'];
	$session_name = 'score_level_'.strtolower($_POST['level']);
	if (isset ($_SESSION[$session_name]))
	{
		$result = https_post_to_wall($token[1],'Echoes Technologies - le Jeu'.PHP_EOL."J'ai terminé le niveau ".$_SESSION[$session_name]['SBE_LEVEL'].' en '.$_SESSION[$session_name]['SBE_NB_CLIC'].' clics !');
	}
}



$buffer = '<?xml version="1.0" encoding="UTF-8" ?>
<result>'.$result.'</result>';


header('Content-Type: text/xml');
echo $buffer;
?>