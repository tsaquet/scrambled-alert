<?php

function https_post_to_friend($token,$friendId,$message)
{
$params = array('access_token'=>$token,'message'=>$message,'from'=>$_SESSION['user']->id);
	$url = "https://graph.facebook.com/$friendId/feed";
	$ch = curl_init();
	curl_setopt_array($ch, array(
	CURLOPT_URL => $url,
	CURLOPT_POSTFIELDS => $params,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_VERBOSE => true
	));
	$result = curl_exec($ch);
	return $result;
}

function https_post_to_wall($token,$message)
{
$params = array('access_token'=>$token,'message'=>$message);
	$url = "https://graph.facebook.com/".$_SESSION['user']->id."/feed";
	$ch = curl_init();
	curl_setopt_array($ch, array(
	CURLOPT_URL => $url,
	CURLOPT_POSTFIELDS => $params,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_VERBOSE => true
	));
	$result = curl_exec($ch);
	return $result;
}

function https_post_score($token,$score)
{	
	if ($_SESSION['user']->id != '0')
	{
		$params = array('access_token'=>$token,'score'=>$score);
		$url = "https://graph.facebook.com/".$_SESSION['user']->id."/scores";
		$ch = curl_init();
		curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_POSTFIELDS => $params,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FRESH_CONNECT => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_TIMEOUT_MS, 1
		));
		$result = curl_exec($ch);
	}
}

?>