<?php
// Définitions du contenu
$content = array(
				 array('title' => 'Introduction',
					   'sentence' => array('Bonjour et bienvenue dans ECHOES Technologies - Le Jeu.',
				  						   'A travers ce petit plaisir vidéo-ludique vous allez pouvoir découvrir simplement quel va être notre projet.')),
				 array('title' => 'Mise en situation',
					   'sentence' => array('Enfilez votre casquette de responsable du système d\'information de votre entreprise.',
				  						   'L\'un de vos gros travaux est d\'optimiser le temps de disponibilité de vos services clients afin d\'augmenter votre rentabilité.',
										   'Pour ce faire, vous avez fait appel à notre société ECHOES Technologies et nous vous avons fourni notre solution d\'alerte sur téléphone.',
										   'Vous avez ensuite mis en place nos sondes que vous avez préalablement parametrées sur chacun de vos serveurs.',
										   'Vous avez également fait installer notre application mobile sur tous les smartphones de votre équipe.',
										   'Bravo, c\'est déjà un grand pas vers votre objectif.')),
				 array('title' => 'Actions',
					   'sentence' => array('Néanmoins, une dernière étape reste à accomplir : faire communiquer vos machines avec notre centralisateur en faisant tourner les cables jusqu\'à ce qu\'ils soient reliés.',
				  						   'En réussissant cette action, vous et votre équipe d\'opérateurs découvrirez en temps réel l\'état de vos machines : sain ou compromis.'),
					   'tables' => array(
										 array('caption' => 'Centralisateur',
											   'tbody' => array ('cell echoes')),
										 array('caption' => 'Cable',
										 	   'thead' => array('Non connecté', 'Connecté'),
											   'tbody' => array ('cell cable _R_L disconnected', 'cell cable _R_L connected')),
										 array('caption' => 'Serveur',
										 	   'thead' => array('Non connecté', 'Sain', 'Compromis'),
											   'tbody' => array ('cell server disconnected', 'cell server safe', 'cell server corrupted')))),
				 array('title' => 'Intervention',
					   'sentence' => array('Lorsqu\'un nouveau serveur compromis est détecté, un opérateur disponible intervient.',
				  						   'Ceci se fait, cette fois, sans opération de votre part puisque l\'opérateur a reçu toutes les informations nécessaires sur son smartphone.',
				  						   'Après un temps d\'intervention, ce serveur devient sain. Toutefois, si tous les membres de votre équipe sont occupés, le serveur restera compromis jusqu\'à ce qu\'un opérateur se libère.')),
				 array('title' => 'Objectif final',
					   'sentence' => array('Le but est donc de trouver les serveurs compromis en faisant le moins de clics possible et de les réparer pour satisfaire le maximum d\'utilisateurs de votre réseau.',
				  						   'Le jeu prend fin lorsque tous les utilisateurs sont satisfaits (vous avez gagné et vous passez au niveau suivant) ou lorsque trop d\'utilisateurs sont insatisfaits (vous avez perdu et vous recommencez le même niveau).')),
				);

// Vérification de la présence d'un post et que le numéro indiqué n'est pas trop grand par rapport à notre tableau
if(isset($_POST['slide']) && $_POST['slide'] <= count($content)){
	$slide = $_POST['slide'];
} else {
	$slide = 1;
}


$buffer  = '<?xml version="1.0"?>
<presentation>
	<title>'.$content[$slide - 1]['title'].'</title>
	<sentences>'."\n";
foreach($content[$slide - 1]['sentence'] as $sentence){
	$buffer .= "\t\t".'<sentence>'.$sentence.'</sentence>'."\n";
}
$buffer .= "\t".'</sentences>'."\n";
if(isset($content[$slide - 1]['tables'])) {
	$buffer .= "\t".'<tables>'."\n";
	foreach($content[$slide - 1]['tables'] as $table){
		$buffer .= "\t\t".'<table>'."\n";
		$buffer .= "\t\t\t".'<caption>'.$table['caption'].'</caption>'."\n";
		if(isset($table['thead'])){
			$buffer .= "\t\t\t".'<thead>'."\n";
			foreach($table['thead'] as $thead){
				$buffer .= "\t\t\t\t".'<th>'.$thead.'</th>'."\n";
			}
			$buffer .= "\t\t\t".'</thead>'."\n";
		}
		$buffer .= "\t\t\t".'<tbody>'."\n";
		foreach($table['tbody'] as $tbody){
			$buffer .= "\t\t\t\t".'<class>'.$tbody.'</class>'."\n";
		}
		$buffer .= "\t\t\t".'</tbody>'."\n";
		$buffer .= "\t\t".'</table>'."\n";
	}
	$buffer .= "\t".'</tables>'."\n";
}
$buffer .= "\t".'<isFirst>';
if($slide == 1) {
	$buffer .= 'true';
} else {
	$buffer .= 'false';
}
$buffer .= '</isFirst>
	<isLast>';
if($slide == count($content)) {
	$buffer .= 'true';
} else {
	$buffer .= 'false';
}
$buffer .= '</isLast>
</presentation>'."\n";

header('Content-Type: text/xml');
echo $buffer;
