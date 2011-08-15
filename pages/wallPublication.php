<?php 

	if (isset($_GET['done']))
	{
		echo("<script>top.window.close();</script>");
	}
	else
	{
		$app_id = "173509166035359";
		
		$redirect = "http://apps.facebook.com/echoes-le-jeu/pages/wallPublication.php?done=true";
		
		$link = "http://apps.facebook.com/echoes-le-jeu/";
		 
		$name = "Echoes Technologies - Le Jeu";
		
		$feed_url = "https://www.facebook.com/dialog/feed?app_id=" 
		. $app_id . "&redirect_uri=" . urlencode($redirect)
		. "&link=" . $link
		. "&name=" . $name
		. "&display=popup";
		
		if (empty($_REQUEST["post_id"])) 
		{
			echo("<script> top.location.href='" . $feed_url . "'</script>");
		}
	}

	
	
?>