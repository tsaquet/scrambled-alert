var bubbleVisible = false;

function openPopup(url)
{
    lw = window.open(url, 'LoginWindow', 'width=500,height=260,scrollbars=no,dependent=no');
    raise_lw();
}   
function raise_lw() 
{
    lw.focus(); 
}
	
function randomFromTo(from, to)
{
	return Math.floor(Math.random() * (to - from + 1) + from);
}

function getId(id)
{
	return document.getElementById(id);
}

function move(e)
{
	var screenWidth = document.body.clientWidth;
	var fromLeft = 5;
	
	if(bubbleVisible) 
	{
		if (navigator.appName!="Microsoft Internet Explorer") 
		{ // Si on est pas sous IE
			if (e.pageX > screenWidth / 2)
			{
				fromLeft=-150;	
			}
			getId("bubble").style.left=e.pageX + fromLeft +"px";
			getId("bubble").style.top=e.pageY + 10+"px";
		}
		else 
		{ 
			if(document.documentElement.clientWidth>0) 
			{
				getId("bubble").style.left=20+event.x+document.documentElement.scrollLeft+"px";
				getId("bubble").style.top=10+event.y+document.documentElement.scrollTop+"px";
			} 
			else 
			{
				getId("bubble").style.left=20+event.x+document.body.scrollLeft+"px";
				getId("bubble").style.top=10+event.y+document.body.scrollTop+"px";
			}
		}
	}
}
 
function showBubble(text) 
{
	if(bubbleVisible==false) 
	{
		getId("bubble").style.visibility="visible"; // Si il est cach� (la verif n'est qu'une securit�) on le rend visible.
		getId("bubble").innerHTML = text; // on copie notre texte dans l'�l�ment html
		bubbleVisible=true;
	}
}

function hideBubble() 
{
	if(bubbleVisible==true) 
	{
		getId("bubble").style.visibility="hidden"; // Si la bulle est visible on la cache
		bubbleVisible=false;
	}
}

document.onmousemove=move;

function isLoggedIntoFb(auth_url)
{
	top.location.href=auth_url;
}