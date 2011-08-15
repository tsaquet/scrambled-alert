<?php 
	if (isset($_GET['done']))
	{
		echo("<script>top.window.close();</script>");
	}
	else
	{
	    $app_id = "173509166035359";
	
	    $redirect = "http://apps.facebook.com/echoes-le-jeu/pages/invites.php?done=true";
	
	    $message = "Viens essayer de battre mon score au jeu Echoes Technologies !";
	
	    $requests_url = "https://www.facebook.com/dialog/apprequests?app_id=" 
	            . $app_id . "&redirect_uri=" . urlencode($redirect)
	            . "&message=" . $message
				. "&display=popup";
	
	    if (empty($_REQUEST["request_ids"])) 
	    {
	       echo("<script> top.location.href='" . $requests_url . "'</script>");
	    } else 
	    {
	        echo "Request Ids: ";
	       print_r($_REQUEST["request_ids"]);
	    }
	}	 
?>