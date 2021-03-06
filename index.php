<?php 

//require_once("./facebook_sdk/php/facebook.php");
require_once("./class/request.php");

$db = Request::get();

// $app_id = "173509166035359";
// $app_secret = "1ffe2e75796ab87b27c78b93e60f8a9c";
// $canvas_page = "http://apps.facebook.com/echoes-le-jeu/";

// $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&redirect_uri=" . urlencode($canvas_page)."&scope=publish_stream,publish_actions";

session_name('game');
session_start();
 
// $facebook = new Facebook(array(  'appId'  => $app_id,  'secret' => $app_secret,  'cookie' => true,));    
// $session = $facebook->getSession();    

if($session)
{    
	if (isset($_REQUEST["code"]))
	{
		$code = $_REQUEST["code"];	
	}
	
	if (isset($_REQUEST["signed_request"]))
	{
		
		$signed_request = $_REQUEST["signed_request"];
	
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
	
		$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
	
	
		// if(empty($code)) 
		// {
	        // echo("<script> top.location.href='" . $auth_url ."'</script>");
	  	// }
		$_SESSION['token'] = @file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=" . $app_id ."&client_secret=".$app_secret."&redirect_uri=" . urlencode($canvas_page)."&code=".$code);
		
		$_SESSION['app_token'] = @file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=" . $app_id ."&client_secret=".$app_secret."&grant_type=client_credentials");
		
		if ($_SESSION['token'] == false)
		{
			echo("<script> top.location.href='" . $auth_url . "'</script>");
		}
		
		// TODO : to refactor
		$token_full = explode("&",$_SESSION['token']);
		$token = explode("=",$token_full[0]);
		
		$_SESSION['user'] = json_decode(@file_get_contents("http://graph.facebook.com/".$data["user_id"]));	
		$_SESSION['friends'] = json_decode(@file_get_contents("https://graph.facebook.com/".$data["user_id"]."/friends?".$token_full[0]));

			
		if ($db->userExists($data["user_id"]))
		{
			$db->updateUserIfNecessary($_SESSION['user']);
		}
		else
		{
			$db->addUser($_SESSION['user']);
		}
			
			
	}
	else 
	{
		echo("<script> top.location.href='" . $auth_url ."'</script>");
	}
} 
else 
{
	
	// echo 
	// (
		// '<script type="text/javascript" src="//www.facebook.com/thomas.saquet" onload=isLoggedIntoFb("'.$auth_url.'") async="async"></script>'
	// );
 	
	$_SESSION['user'] = 
	json_decode('{
	   "id": "0",
	   "name": "Guest",
	   "first_name": "Guest",
	   "last_name": "",
	   "link": "http://www.facebook.com/0",
	   "username": "",
	   "gender": "",
	   "locale": ""
	}');
	$_SESSION['friends']= json_decode('{
		  "data": [
		  ]
		}');
	$data["user_id"] = 0;
	if ($db->userExists($data["user_id"]))
	{
		$db->updateUserIfNecessary($_SESSION['user']);
	}
	else
	{
		$db->addUser($_SESSION['user']);
	}		
}

$db->log('index');

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="description" content="" />
        <meta name="generator" content="" />
        <meta name="author" content="ECHOES Technologies" />

        <title>ECHOES Technologies - Le Jeu</title>

        <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
        <link rel="shortcut icon" href="./img/Logo icon/ECHOES Technologies - Le Jeu BETA 16.png" />
        <link rel="apple-touch-icon" href="./img/Logo icon/ECHOES Technologies - Le Jeu BETA 16.png" />

        <link rel="stylesheet" type="text/css" href="./css/style.css" />

        <script type="text/javascript" src="./js/jquery-1.5.2.min.js"></script>
        <script type="text/javascript" src="./js/jQueryRotate-2.1.min.js"></script>
        <script type="text/javascript" src="./js/ajax.js"></script>
        <script type="text/javascript" src="./js/windowsManager.js"></script>
		<script type="text/javascript" src="./js/menu.js"></script>
		<script type="text/javascript" src="./js/tools.js"></script>
    </head>
    <body>
		<div id="bubble" class="bubble"></div>
		<div id="scores" class="scores"></div>
		<div id="page" class="shell">
			<header>
				<!-- Top Navigation -->
				<div id="top-nav">
					<ul>
						<li class="home"><a href="http://www.echoes-tech.com" target="_blank">home</a></li>
						<li><a href="http://blog.echoes-tech.com" target="_blank">blog</a></li>
						<li><a href="http://game.echoes-tech.com" target="_blank">jeu</a></li>
						<li><a href="http://twitter.com/#!/echoes" target="_blank">twitter</a></li>
						<li class="last"><a href="http://www.facebook.com/pages/Echoes-Technologies/121052081290468" target="_blank">facebook</a></li>
					</ul>
				</div>
				<div class="logo">
					<img src='./img/headline.png' />
				</div>
			</header>
		

			<div class="cl">&nbsp;</div>
			<!-- Sort Navigation -->

			<div id="sort-nav">
				<div class="bg-right bg-left">
					<div class="cl">&nbsp;</div>
					<ul class="menu">
						<li class="first bg-left">
							<a href="javascript:ajax('init', 'level=didacticiel')">Nouvelle partie</a>
							<ul>
								<li><a href="javascript:ajax('init', 'level=didacticiel')">Didacticiel</a></li>
								<li><a href="javascript:ajax('init', 'level=novice')">Novice</a></li>
								<li><a href="javascript:ajax('init', 'level=normal')">Normal</a></li>
								<li><a href="javascript:ajax('init', 'level=expert')">Expert</a></li>
								<li><a href="javascript:ajax('init', 'level=maitre')">Maître</a></li>
							</ul>
						</li>
						<li class="pointer" onclick='openScores(); ' onmouseout='hideBubble();' onmouseover='showBubble("Ouvrir le tableau des scores.")'>
							<a>Scores</a>
						</li>
						<li onmouseout='hideBubble();' onmouseover='showBubble("Version bêta : pas encore disponible") '>
							<a>Hauts faits</a>
						</li>
						<li>
							<a>Partager</a>
							<ul>
								<li><a href="javascript:openPopup('pages/invites.php')">Inviter mes amis</a></li>
								<li><a href="javascript:openPopup('pages/wallPublication.php')">Sur mon mur</a></li>
							</ul>
						</li>
						<div class="tumevoispas" id="wait"></div>
					</ul>
					<div class="cl">&nbsp;</div>
				</div>
			</div>
			<div id="content">
				<div class="cl">&nbsp;</div>
				<div class="main">
					<div id="leftside">
						<div class="leftside">
							<table id="nbClick" onmouseout='hideBubble();' onmouseover='showBubble("Il s&#39;agit du nombre de clics utilisés jusque là pour trouver la solution.") '>
								<tr>
									<td><span id="spanNbClick">0</span></td>
									<td class="mouse empty"></td>
								</tr>
							</table>
							<div class="sep"></div>
							<table id="inverse">
								<tr>
									<td><div class="mouse click left" onmouseout='hideBubble();' onmouseover='showBubble("Clic gauche") '></div></td>
									<td><div class="mouse click right" onmouseout='hideBubble();' onmouseover='showBubble("Clic droit") '></div></td>
								</tr>
								<tr>
									<td><div class="space"></div></td>
									<td><div class="space"></div></td>
								</tr>
								<tr>
									<td class="left-click" id="left-click" onmouseout='hideBubble();' onmouseover='showBubble("Sens horaire") '></td>
									<td class="right-click" id="right-click" onmouseout='hideBubble();' onmouseover='showBubble("Sens anti-horaire") '></td>
								</tr>
							</table>
							<div class="space"></div>
							<p><a href="javascript:changeRotation();">Inverser</a></p>
							<div class="space"></div>
							<div class="sep"></div>
							<div class="wrapped" id="wrapped" onmouseout='hideBubble();' onmouseover='showBubble("Connexion bord à bord : <br> <ul> <li> Si les flèches pointent vers l&#39;intérieur les bords ne communiquement pas </li> <li> Si les flèches pointent vers l&#39;extérieur les bords communiquement </li> </ul>") '>
							</div>
							<div id="sep_operator" class="sep"></div>
							<div id="stats_operator" onmouseout='hideBubble();' onmouseover='showBubble("Les opérateurs sont chargés d&#39;intervenir sur les machines lorsqu&#39;un problème est remonté à la machine centrale") '>
								<div id="operator-1" class="operator">
									<div id="phone-1" class="phone" alt="Téléphone" title="Téléphone"></div>
									<img src="./img/operator.png" alt="Operator"  />
								</div>
								<div id="operator-2" class="operator">
									<div id="phone-2" class="phone" alt="Téléphone" title="Téléphone"></div>
									<img src="./img/operator.png" alt="Operator"  />
								</div>
								<div id="operator-3" class="operator">
									<div id="phone-3" class="phone" alt="Téléphone" title="Téléphone"></div>
									<img src="./img/operator.png" alt="Operator"  />
								</div>
								<div id="operator-4" class="operator">
									<div id="phone-4" class="phone" alt="Téléphone" title="Téléphone"></div>
									<img src="./img/operator.png" alt="Operator"  />
								</div>
								<div id="operator-5" class="operator">
									<div id="phone-5" class="phone" alt="Téléphone" title="Téléphone"></div>
									<img src="./img/operator.png" alt="Operator"  />
								</div>
								<div id="operator-6" class="operator">
									<div id="phone-6" class="phone" alt="Téléphone" title="Téléphone"></div>
									<img src="./img/operator.png" alt="Operator"  />
								</div>
								<div id="operator-7" class="operator">
									<div id="phone-7" class="phone" alt="Téléphone" title="Téléphone"></div>
									<img src="./img/operator.png" alt="Operator"  />
								</div>
								<div id="operator-8" class="operator">
									<div id="phone-8" class="phone" alt="Téléphone" title="Téléphone"></div>
									<img src="./img/operator.png" alt="Operator"  />
								</div>
							</div>
						</div>
						
					</div>

					<div id="board">
						<a href="javascript:ajax('init', 'level=didacticiel');">
							<img id="splash" src="./img/splash-screen.png" alt="Splash Screen" title="Cliquer pour commencer à jouer" />
						</a>
						<a id="play" href="javascript:ajax('init', 'level=didacticiel');"></a>
					</div>

					<div id="rightside">
						<div id="stats_users">
							<div class="stats">
								<div id="satisfiedUsers" class="users" alt="Clients satisfaits" onmouseout='hideBubble();' onmouseover='showBubble("Clients satisfaits") '>
									<img style="" src="./img/satisfied-users.png" alt="Clients satisfait" title="Clients satisfait">
								</div>
								<div id="rule" onmouseout='hideBubble();' onmouseover='showBubble("Cette règle indique le taux de satisfaction des utilisateurs. Si elle est sous les 50% quand le réseau est complété, la partie est perdue.") '>
									<div id="cursor"></div>
									<img style="" src="./img/rule.png" alt="Satisfaction Rule">
								</div>
								<div id="dissatisfiedUsers" class="users">
									<img style="" src="./img/dissatisfied-users.png" alt="Clients insatisfaits" onmouseout='hideBubble();' onmouseover='showBubble("Clients insatisfaits") '>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				<div class="cl">&nbsp;</div>
				<div class="clear"></div>
			</div>
			<div id="footer">
				<div class="center">
					<p>Optimisé pour : Firefox 3.5+, Chrome 10+, Opera 10+, Safari 5+, Internet Explorer 9</p> 
					<p>&copy; Copyright ECHOES Technologies 2011.</p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			ajax('scores')
		</script>
		<div class="ads ads_bottom">
			<div class="center">
				<div class="ads_315f3135375f31313137">
					<script type="text/javascript">
						var rdads=new String(Math.random()).substring (2, 11)
						document.write('<sc'+'ript type="text/javascript" src="http://ads.makemereach.com/tracking/ads_display.php?n=315f3135375f31313137_efb2a78165&rdads='+rdads+'"></sc'+'ript>');
					</script>
				</div>
			</div>
		</div>
		
    </body>
</html>

<?php
	$db->disconnect();
?>