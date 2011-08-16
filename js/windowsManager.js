function openScores()
{
	document.getElementById('scores').style.display = "block"
}

function closeScores()
{
	document.getElementById('scores').style.display = "none"
}

/**
 * Méthode d'affichage des scores
 */
function displayScores(response)
{
	var first_name = response.getElementsByTagName("usr_first_name")[0].firstChild.nodeValue;
	var last_name = response.getElementsByTagName("usr_last_name")[0].firstChild.nodeValue;
	
	var sra_score = response.getElementsByTagName("sra_score")[0].firstChild.nodeValue;
	var sra_mpl = response.getElementsByTagName("sra_mpl")[0].firstChild.nodeValue;
	
	var sbe_level = new Array();
	var sbe_score = new Array();
	var sbe_nb_clic = new Array();
	var sbe_percent_satisf = new Array();
	var sbe_nb_played = new Array();
	var sbe_nb_win = new Array();
	
	for (i=0;i<4;i++)
	{
		sbe_level[i] = response.getElementsByTagName("sbe_level_"+i)[0].firstChild.nodeValue;
		sbe_score[i] = response.getElementsByTagName("sbe_score_"+i)[0].firstChild.nodeValue;
		sbe_nb_clic[i] = response.getElementsByTagName("sbe_nb_clic_"+i)[0].firstChild.nodeValue;
		sbe_percent_satisf[i] = response.getElementsByTagName("sbe_percent_satisf_"+i)[0].firstChild.nodeValue;
		sbe_nb_played[i] = response.getElementsByTagName("sbe_nb_played_"+i)[0].firstChild.nodeValue;
		sbe_nb_win[i] = response.getElementsByTagName("sbe_nb_win_"+i)[0].firstChild.nodeValue;
	}
	content = '<div class="close" onclick="closeScores();">X</div>';
	content += ' 	<p class="title">Tableau des scores</p>';
	content += ' 	<p class="player">'+first_name+' '+last_name+'</p>';
	content += ' 	<p>';
	content += '		<table cellspacing="0" cellpadding="0" width="" border="0">';
	content += '			<thead>';
	content += '				<tr>';
	content += '					<th>Score cumulé</th>';
	content += '					<th>Niveau le plus joué</th>';
	content += '				</tr>';
	content += '			</thead>';
	content += '			<tr>';
	content += '				<td>'+sra_score+'</td>';
	content += '				<td>'+sra_mpl+'</td>';
	content += '			</tr>';
	content += '		</table>';
	content += '	</p>';
	content += '	<p>';
	content += '	<table cellspacing="0" cellpadding="0" width="" border="0">';
	content += '		<thead>';
	content += '			<tr>';
	content += '				<th>Niveau</th>';
	content += '				<th>Score</th>';
	content += '				<th>Nb clics</th>';
	content += '				<th>% Satisfaction</th>';
	content += '				<th>Joués</th>';
	content += '				<th>Gagnés</th>';
	content += '				<th>Publier</th>';
	content += '			</tr>';
	content += '		</thead>';
	
	for(i = 0; i < 4; i++) 
	{
		odd = "";
		modulo = (i % 2);
		if (modulo != 0)
		{
			odd= " class='odd'";
		}
		content += '		<tr'+odd+'>';
		content += '			<td>'+sbe_level[i]+'</td>';
		content += '			<td>'+sbe_score[i]+'</td>';
		content += '			<td>'+sbe_nb_clic[i]+'</td>';
		content += '			<td>'+sbe_percent_satisf[i]+'</td>';
		content += '			<td>'+sbe_nb_played[i]+'</td>';
		content += '			<td>'+sbe_nb_win[i]+'</td>';
		content += '		</tr>';
	}
	content += '	</table>';
			
	
	document.getElementById('scores').innerHTML = content;
}

/**
 * Méthode d'affichage de la présentation du jeu
 */
function displayPresentation(response) {
	// Récupération du titre
	var title = response.getElementsByTagName("title")[0].firstChild.nodeValue;

    /* Récupérer la liste des phrases */
    var sentences = response.getElementsByTagName("sentence");
    /* Nombre de phrases */
    nbSentences = sentences.length;

	// Est-ce le premier slide
	var isFirst = response.getElementsByTagName("isFirst")[0].firstChild.nodeValue;
	// Est-ce le dernier slide
	var isLast = response.getElementsByTagName("isLast")[0].firstChild.nodeValue;

    // Présentation HTML
    var content = '<div id="presentation">';
    // Affichage de la croix pour quitter la présentation
    content += '<a href="javascript:ajax(\'init\', \'level=' + level + '\');" style="float: right; text-decoration: none; margin-top: 5px;" title="Passer les explications">X</a>'
    // Affichage du titre
	content += '<h2>' + title + ' :</h2>';
	// Affichage des différentes phrases
    for(i = 0; i < nbSentences; i++) {
    	content += '<p>' + sentences[i].firstChild.nodeValue + '</p>';
    }

    /* Récupérer la liste des tableaux */
	var tables = response.getElementsByTagName("table");
    /* Nombre de phrases */
    nbTables = tables.length;

    // Affichage des tableaux d'illustration
    for(i = 0; i < nbTables; i++) {
        content += '<table class="presentation">';
        content += '<caption align="top">' + tables[i].getElementsByTagName("caption")[0].firstChild.nodeValue + '</caption>';
	    if(thead = tables[i].getElementsByTagName("th")){
        	content += '<thead><tr>';
        	nbTh = thead.length;
			for(j = 0; j < nbTh; j++) {
	            content += '<th>' + thead[j].firstChild.nodeValue + '</th>';
			}
            content += '</tr></thead>';
		}
	    if(tbody = tables[i].getElementsByTagName("class")){
	        content += '<tbody><tr>';
        	nbTd = tbody.length;
			for(j = 0; j < nbTd; j++) {
	            content += '<td><div style="position:static;float:none;margin:auto" class="' + tbody[j].firstChild.nodeValue + '"></div></td>';
			}
	        content += '</tr></tbody>';
		}
        content += '</table>';
	}

    content += '</div>';
    // Si c'est le dernier, on affiche commencer à jouer
    if (isLast == "true") {
        content += '<p style="text-align:center;"><a href="javascript:ajax(\'init\', \'level=' + level + '\');" style="float:middle;">Commencer à jouer !</a></p>';
    }
    // Si c'est le premier on affiche pas précédent
    else if (isFirst == "true") {
        content += '<p style="float:right;margin-right:20px;"><a href="javascript:ajax(\'presentation\', \'action=next\');" title="Passer à l\'explication suivante"><span class="nav next"></span></a></p>';
    }
    // Sinon on affiche précédent et suivant
    else {
        content += '<p style="float:left;margin-left:20px;"><a href="javascript:ajax(\'presentation\', \'action=prev\');" title="Revenir à l\'explication précédente"><span class="nav prev"></span></a></p><p style="float:right;margin-right:20px;"><a href="javascript:ajax(\'presentation\', \'action=next\');" title="Passer à l\'explication suivante"><span class="nav next"></span></a></p>';
    }
    document.getElementById('board').innerHTML = content;
}

/**
 * Méthode d'affichage du jeu la première fois
 */
function displayGame(response) {
    // Réinitialisation du nombre de clics
    document.getElementById('spanNbClick').innerHTML = nbClick;

    // Récupération des dimensions du plateau
    var dimensions = response.getElementsByTagName("dimensions");
    var width = dimensions[0].getElementsByTagName("width")[0].firstChild.nodeValue;
    var length = dimensions[0].getElementsByTagName("length")[0].firstChild.nodeValue;
    var wrapped = response.getElementsByTagName("wrapped")[0].firstChild.nodeValue;

    //document.getElementById('params').style.marginTop = "140px";
    if (wrapped == "true")
    {
    	document.getElementById('wrapped').className = "wrapped1";
    	document.getElementById('wrapped').setAttribute("onmouseover", 'showBubble("<ul> <li>Les flèches pointent vers l&#39;extérieur : les bords communiquent</li> </ul>")')
    }
    else
    {
    	document.getElementById('wrapped').className = "wrapped0"
    	document.getElementById('wrapped').setAttribute("onmouseover", 'showBubble("<ul> <li>Les flèches pointent vers l&#39;intérieur : les bords ne communiquent pas</li> </ul>")')
    }
   

    // Affichage du div de séparation sous l'indicateur de wrap
    document.getElementById('sep_operator').style.display = "block";

    // Création des 3 tableaux qui stoqueront respectivement les directions, les types et les états des cellules
    var col = new Array;
    for (i = 0; i < 3; i++) {
        for (j = 0; j < length; j++) {
            col[j] = new Array;
            for (k = 0; k < width; k++) {
                col[j][k] = new Array;
            }
        }
    }
    var cellDirections = col[0];
    var cellType = col[1];
    var cellState = col[2];

    /* Récupérer la liste des cells */
    var cells = response.getElementsByTagName("cell");
    /* Nombre de cellule */
    count = cells.length;

    // On remplis les tableaux créés précédemment
    for(i = 0; i < count; i++) {
    	if ((cells[i].getElementsByTagName("posX")[0].firstChild) && (cells[i].getElementsByTagName("posY")[0].firstChild))
    	{
	        // On récupère la position X de la cellule en lui retranchant 1 pour commencer à 0
	        var posX = cells[i].getElementsByTagName("posX")[0].firstChild.nodeValue - 1;
	        // On récupère la position Y de la cellule en lui retranchant 1 pour commencer à 0
	        var posY = cells[i].getElementsByTagName("posY")[0].firstChild.nodeValue - 1;
	        cellDirections[posX][posY] = cells[i].getElementsByTagName("directions")[0].firstChild.nodeValue;
	        cellType[posX][posY] = cells[i].getElementsByTagName("type")[0].firstChild.nodeValue;
	        cellState[posX][posY] = cells[i].getElementsByTagName("state")[0].firstChild.nodeValue;
	    }
    }

    // Présentation HTML
    var content = '';
    content += '<div style="width:' + (width * 64) + 'px;height:' + (length * 64) + 'px;margin:' + ((576 -(length * 64)) / 2) +'px ' + ((576 -(width * 64)) / 2) +'px">';
    // Contours du plateau
    for (i = 0; i < length; i++) {
        for (j = 0; j < width; j++) {
            // On vérifie s'il s'agit d'un serveur
            if (cellType[j][i] == "echoes" ||  cellType[j][i] == "server") {
                content += '<div class="cell empty" id="cell-' + (j + 1) + '-' + (i + 1) + '">'
                // Si l'état du serveur est "safe" ou "corrupted" alors le cable est connecté
                if (cellState[j][i] == "safe" || cellState[j][i] == "corrupted") {
                    cableState = "connected";
                } else {
                    cableState = cellState[j][i];
                }
                content += '<div class="cell cable ' + cellDirections[j][i] + ' ' + cableState + '" id="cable-' + (j + 1) + '-' + (i + 1) + '"></div>';
                content += '<div class="cell ' + cellType[j][i] + ' ' + cellState[j][i] + '" id="' + cellType[j][i] + '-' + (j + 1) + '-' + (i + 1) + '"></div>';
            } else {
                content += '<div class="cell ' + cellType[j][i] + ' ' + cellDirections[j][i] + ' ' + cellState[j][i] + '" id="' + cellType[j][i] + '-' + (j + 1) + '-' + (i + 1) + '">';
            }
            content += '</div>';
        }
    }
    content += '</div>';
    document.getElementById('board').innerHTML = content;

    // Récupération du nombre d'opérateurs et de leurs status
    var operators = response.getElementsByTagName("operators");
    var avOp = parseInt(operators[0].getElementsByTagName("available")[0].firstChild.nodeValue);
    var busyOp = parseInt(operators[0].getElementsByTagName("busy")[0].firstChild.nodeValue);
    var totOp = avOp + busyOp;
    // Changement du nombre d'opérateur affiché
    for (i = 1; i <= 8; i++) {
        document.getElementById('operator-' + i).style.display = "none";
    }
    for (i = 1; i <= totOp; i++) {
        document.getElementById('operator-' + i).style.display = "block";
        //phones[i] = false;
        $("#phone-"+i).rotate(0);
        document.getElementById("phone-" + i).className="phone";
    }

    // Récupération du nombre d'utilisateurs et de leurs status
    var users = response.getElementsByTagName("users");
    // Initialisation du nombre d'utilisateurs satisfaits
    var satisfiedUsers = users[0].getElementsByTagName("satisfied")[0].firstChild.nodeValue;
    // Initialisation du nombre d'utilisateurs insatisfaits
    var dissatisfiedUsers = users[0].getElementsByTagName("dissatisfied")[0].firstChild.nodeValue;
    // Placement du curseur
    document.getElementById('cursor').style.display = "block";
    document.getElementById('cursor').style.marginTop = '13px';
    moveCursor(dissatisfiedUsers);

    // Appel de la fonction de rotation pour chaque cellule.
    for (i = 0; i < width; i++) {
        for (j = 0; j < length; j++) {
            cellRotate((i + 1),(j + 1), cellType[i][j]);
        }
    }
}

/**
 * Méthode de ré-affichage des cellules modifiées après clic 
 */
function newDisplays(response) {
    /* Récupérer la liste des cells */
    var cells = response.getElementsByTagName("cell");
    /* Nombre de cellule */
    count = cells.length;

    for(i = 0; i < count; i++) {
        // On récupère la position X de la cellule
        var posX = cells[i].getElementsByTagName("posX")[0].firstChild.nodeValue;
        // On récupère la position Y de la cellule
        var posY = cells[i].getElementsByTagName("posY")[0].firstChild.nodeValue;
        cellDirections = cells[i].getElementsByTagName("directions")[0].firstChild.nodeValue;
        cellType = cells[i].getElementsByTagName("type")[0].firstChild.nodeValue;
        cellState = cells[i].getElementsByTagName("state")[0].firstChild.nodeValue;

        // Enlevage de la rotation de la cellule
        $('#cable-' + posX + '-' + posY).rotate({angle:0});

        // Présentation HTML
        // On vérifie s'il s'agit d'un serveur
        if (cellType == "echoes" ||  cellType == "server") {
            // Si l'état du serveur est "safe" ou "corrupted" alors le cable est connecté
            if (cellState == "safe" || cellState == "corrupted") {
                cableState = "connected";
            } else {
                cableState = cellState;
            }
            document.getElementById('cable-' + posX + '-' + posY).className='cell cable ' + cellDirections + ' ' + cableState;
            document.getElementById(cellType + '-' + posX + '-' + posY).className='cell ' + cellType + ' ' + cellState;
        } else {
            document.getElementById(cellType + '-' + posX + '-' + posY).className='cell ' + cellType + ' ' + cellDirections + ' ' + cellState;
        }
        cellRotate(posX, posY, cellType);
    }

    // Récupération du nombre d'opérateurs et de leurs status
    var operators = response.getElementsByTagName("operators");
    var angle = 10;
    if (operators.length != 0) {
        var avOp = parseInt(operators[0].getElementsByTagName("available")[0].firstChild.nodeValue);
        var busyOp = parseInt(operators[0].getElementsByTagName("busy")[0].firstChild.nodeValue);
        if (busyOp > 0) {
            for (i = 1 ; i <= busyOp ; i++) {
                document.getElementById("phone-" + i).className = "alerted-phone";
                var rotation = function () {
                    //phones[i] = true;
                    angle = (angle * -1);
                    $("#phone-"+i).rotate({
                        duration:50,
                        angle:angle,
                        animateTo:-angle/*,
                        callback: function() {
                            if(phones[i] == true) {
                                rotation();
                            }
                        }*/
                    });
                }
                rotation();
            }
        }  else {
        for (i = 1 ; i <= (avOp + busyOp); i++)
        {
            document.getElementById("phone-" + i).className = "phone";
            //phones[i] = false;
            $("#phone-"+i).rotate(0);
        }
        }
    }
    // Récupération du nombre d'utilisateurs et de leurs status
    var users = response.getElementsByTagName("users");
    if (users.length != 0) {
        // Initialisation du nombre d'utilisateurs satisfaits
        var satisfiedUsers = users[0].getElementsByTagName("satisfied")[0].firstChild.nodeValue;
        // Initialisation du nombre d'utilisateurs insatisfaits
        var dissatisfiedUsers = users[0].getElementsByTagName("dissatisfied")[0].firstChild.nodeValue;
        // Déplacement du curseur
        moveCursor(dissatisfiedUsers);
    }

    var changeMargin = false;
    var status = response.getElementsByTagName("status");
    if (status[0])
    {
        if (status[0].firstChild.nodeValue == "win" || status[0].firstChild.nodeValue == "loose") {
        	ajax('scores')
            setTimeout( function() {
                // Présentation HTML
                var content = '<div id="endGame">';
                if (status[0].firstChild.nodeValue == "win") {
                    content += '<p>Félicitation, vous avez gagné cette partie avec '+ satisfiedUsers + '% d\'utilisateurs satisfaits et ce  en ' + nbClick + ' clic';
                    if (nbClick > 1) {
                        content += 's';
                    }
                    content += '.<p>';
                    var changeMargin = level;
                    switch (level) {
                        case '1':
                            content += '<p>Attention, cette fois-ci il va y avoir deux serveurs.</p> \
                                        <p>Il faut qu\'ils soient reliés en même temps pour gagner la partie.</p>';
                            level++;
                            break;
                        case '2':
                            content += '<p>On augmente encore un peu la difficulté ?</p> \
                                        <p>Plus de serveurs = plus de variation d\'humeur des clients.</p> \
                                        <p>Il ne va pas falloir laisser le curseur en bas à droite tomber définitivement dans le rouge.</p>';
                            level++;
                            break;
                        case '3':
                            level++;
                            content += '<p>Le personnage en haut à droite avec son téléphone est un opérateur.</p> \
                                        <p>C\'est lui qui est chargé de réparer les serveurs une fois qu\'ils sont découverts comme étant corrompus.</p> \
                                        <p>Pour la prochaine partie, il y aura deux opérateurs.</p> \
                                        <p>Les résolutions de problèmes seront ainsi plus rapides.</p>';
                            break;
                        case '4':
                            content += '<p>Dernier chapitre du didacticiel.</p> \
                                        <p>Et encore une difficulté en plus.</p> \
                                        <p>Les bords du plateau de jeu communiquent entre eux. C\'est à dire qu\'un cable en bout de course sur le coté gauche pourra être relié avec un cable en bout de course sur le coté droit.</p>';
                            level++;
                            break;
                        case '5':
                            level = "novice";
                            content += '<p>Le didacticiel se termine ici. Il est temps d\'attaquer le jeu en mode novice.<p>';
                            break;
                        default:
                            break;
                    }
                    content += '<p><a href="javascript:ajax(\'init\', \'level=' + level + '\');">Cliquez-ici pour passer à la partie suivante.</a><p>';
                } else {
                    content += '<p>Quel dommage, vous avez perdu cette partie car  '+ dissatisfiedUsers + '% d\'utilisateurs sont insatisfaits.<p>';
                    content += '<p><a href="javascript:ajax(\'init\', \'level=' + level + '\');">Cliquez-ici pour recommencer.</a><p>';
                }
                content += '</div>';
                document.getElementById('board').innerHTML = content;
                // Replaçage du cadre s'il s'agit du dernier niveau du didacticiel
                switch (changeMargin) {
                    case '1':
                        document.getElementById('endGame').style.marginTop = '224px';
                        break;
                    case '2':
                        document.getElementById('endGame').style.marginTop = '191px';
                        break;
                    case '3':
                        document.getElementById('endGame').style.marginTop = '178px';
                        break;
                    case '4':
                        document.getElementById('endGame').style.marginTop = '174px';
                        break;
                    case '5':
                        document.getElementById('endGame').style.marginTop = '224px';
                        break;
                    default:
                        break;
                }
            },1000);
        }
    }

    document.getElementById('spanNbClick').innerHTML = nbClick;

    // Variable permettant de bloquer le clic en attendant la réponse du serveur
    window.canRotate = true;
}

// Rotation des cellules
function cellRotate(posX, posY, type) {
    var duration = 500;
    var idTrigger = '#' + type +'-' + posX + '-' + posY;
    var idCell = '#cable-' + posX + '-' + posY;

    $(document).ready( function() {
        // Clic gauche
        $(idTrigger).click( function() {
            // On vérifie s'il a le droit de cliquer
            if (window.canRotate == true) {
                // On block le clic
                window.canRotate = false;
                // Rotation de l'image de 90° supplémentaire'
                $(idCell).rotate({duration:duration,animateTo:(rotation * 90)});
                // Incrémentation du compteur de clics
                nbClick++;
                // On envoi les info de changements au serveur
                setTimeout( function() {ajax('changeDirection', 'posX=' + posX + '&posY=' + posY + '&rotation=' + (rotation * 1))},duration);
            }
            return false;
        });
        // Clic droit
        $(idTrigger).bind("contextmenu", function() {
            // On vérifie s'il a le droit de cliquer
            if (window.canRotate == true) {
                // On block le clic
                window.canRotate = false;
                // Rotation de l'image de -90°
                $(idCell).rotate({duration:duration,animateTo:(rotation * -90)});
                // Incrémentation du compteur de clics
                nbClick++;
                setTimeout(function(){ajax('changeDirection', 'posX=' + posX + '&posY=' + posY + '&rotation=' + (rotation * -1))},duration);
            }
            return false;
        });
    });
}

// Changement du sens de rotaiton des cellules
function changeRotation() {
    // On inverse le sens de rotation
    rotation = (rotation * -1);
    // On sauvegarde les deux propriétés d'une des deux flèches
    var leftClass = document.getElementById('left-click').className;
    var leftTitle = document.getElementById('left-click').title;
    var leftOnmouseover = document.getElementById('left-click').onmouseover;
    document.getElementById('left-click').className = document.getElementById('right-click').className;
    document.getElementById('left-click').title = document.getElementById('right-click').title;
    document.getElementById('left-click').onmouseover = document.getElementById('right-click').onmouseover;
    document.getElementById('right-click').className = leftClass;
    document.getElementById('right-click').title = leftTitle;
    document.getElementById('right-click').onmouseover = leftOnmouseover;
}

// Déplacement du curseur d'humeur des utilisateurs
function moveCursor(dissatisfiedUsers) {
    var usersMarginTop = document.getElementById('cursor').style.marginTop.replace("px", "");
    var cursorPosition = (((dissatisfiedUsers * 200) / 100) + 13);
    if (usersMarginTop != cursorPosition){
        document.getElementById('cursor').style.marginTop = cursorPosition + 'px';
    }
}
