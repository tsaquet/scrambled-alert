<?php

require_once('../class/tools.php');

session_name('game');
session_start();

$token_full = explode("&",$_SESSION['token']);

$token = explode("=",$token_full[0]);

https_post_score($token[1],'florent.poinsaut');



?>