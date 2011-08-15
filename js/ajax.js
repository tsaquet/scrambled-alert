// Niveau
var level = 1;
// Initialisation du muméro du slide de présentation
var slide = 1;
// Initialisation du nombre de clic
var nbClick = 0;
// Sens de rotation (-1 ou 1)
var rotation = 1;
// Autorisation de vibration des 8 téléphones
var phones = new Array;
for (i = 0; i < 8; i++) {
    phones[i] = false;
}

/**
 * Méthode de communication AJAX
 */
function ajax(action, args) {
    // En fonction de l'action à effectuer on définit les bonnes variables
    switch (action) {
        case 'presentation':
            fct = 'displayPresentation';
            PHPFile = 'ajax/presentation.php';
            switch (args) {
                case 'action=next':
                    slide++;
                    break;
                case 'action=prev':
                    slide--;
                    break;
                default:
                    slide++;
                    break;
            }
            args = 'slide=' + slide;
            break;
        case 'init':
            if (args == 'level=didacticiel') {
                // réinitialisation du muméro du slide de présentation
                slide = 1;
                // réinitialisation du muméro du niveau de didacticiel
                level = 1;
                fct = 'displayPresentation';
                PHPFile = 'ajax/presentation.php';
                args = 'slide=1';
            } else {
                level = args.replace("level=", "");
                fct = 'displayGame';
                PHPFile = 'ajax/initBoard.php';		        
            }
            // Réinitialisation du nombre de click
            nbClick = 0;
            break;
        case 'changeDirection':
            fct = 'newDisplays';
            PHPFile = 'ajax/changeBoard.php';
            break;
        default:
            fct = 'displayGame';
            PHPFile = 'ajax/initBoard.php';
            args = 'level=1';
            break;
    }

    var xhr = getXhr();

    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200) {
            /* xhr.responseXML permet d'obtenir le fichier XML
             xhr.responseText aurait retourné le fichier sous format texte */
            var response = clean(xhr.responseXML.documentElement);
            //on définit l'appel de la fonction au retour serveur
            window[fct](response);
            // on masque l'indicateur d'attente
            document.getElementById("wait").className="tumevoispas";
        }
    }
    //on affiche l'iindicateur d'attente
    document.getElementById("wait").className="tumevois";

    // Variable permettant de bloquer le clic en attendant la réponse du serveur
    window.canRotate = true;

    // On demande le xml du plateau
    xhr.open("POST",PHPFile,true);
    // ne pas oublier ça pour le post
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    // On lui passe les arguments contenant les modifs
    xhr.send(args);
}

// Création de l'objet XmlHttpRequest
function getXhr() {
    var xhr = null;
    if(window.XMLHttpRequest) // Firefox et autres
        xhr = new XMLHttpRequest();
    else if(window.ActiveXObject) { // Internet Explorer
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
    } else { // XMLHttpRequest non supporté par le navigateur
        alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
        xhr = false;
    }
    return xhr;
}

// Node cleaner
function go(c) {
    if(!c.data.replace(/\s/g,''))
        c.parentNode.removeChild(c);
}

// XML cleaner
function clean(d) {
    var bal=d.getElementsByTagName('*');
    for(i=0;i<bal.length;i++) {
        a=bal[i].previousSibling;
        if(a && a.nodeType==3)
            go(a);
        b=bal[i].nextSibling;
        if(b && b.nodeType==3)
            go(b);
    }
    return d;
}

