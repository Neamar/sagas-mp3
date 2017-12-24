<?php
@ini_set('default_charset', 'ISO-8859-1');

/*
- $Titre permet de donner un titre à la page.
- $UseMath=true active le parseur LaTeX
- $noKeyWords=true empeche la génération dynamique des mots clés
- $AddLine contient des lignes à ajouter dans la partie <head> de la page.
- $ScriptURI contient une adresse vers un script. S'il n'est pas fourni, c'est le script par défaut qui est appelé.
*/

if(isset($UseMath))
{
	include(__FILE__ . '/../../Latex/regexp_callback.php');
	ob_start('ParseMath');//À la fin de l'éxecution du script, le contenu de la page sera envoyé à la fonction ParseMath qui se chargera de convertir les entités mathématiques.
}


function InclureCode($URL,$LNG="AUCUN",$Discret=false,$UseClass=true)
{
	global $codeAreUTF8;
	$CodeSource=file_get_contents('Codes/' . $URL);
	if(isset($codeAreUTF8))
		$CodeSource=utf8_decode($CodeSource);

	//Charger la libraire GeShi
	include_once(substr(__FILE__,0,strrpos(__FILE__,'/')) . '/../lib/geshi.php');
	echo '<fieldset>' . "\n" . '<legend>Code source : <a href="Codes/' . $URL . '" title="Télecharger le fichier">' . $URL . '</a></legend>'. "\n";
	if($LNG!="AUCUN")
	{
		$RessourceCode = new GeSHi($CodeSource,$LNG);
		if($UseClass)
			$RessourceCode->enable_classes();//Utiliser des classes, c'est moins lourd
		if(!$Discret)
		{
			$RessourceCode->set_header_content('<ul><li>Langage : <em>{LANGUAGE}</em></li><li>&Delta;T : <em>{TIME}s</em></li><li>Taille :' . filesize('Codes/' . $URL) . ' caractères</li></ul>');
			$RessourceCode->set_header_type(GESHI_HEADER_DIV);
		}
		$RessourceCode->enable_keyword_links(false);
		$CodeColorie=$RessourceCode->parse_code();
		echo $CodeColorie;
	}
	else
		echo $CodeSource;
	echo "</fieldset>";
}

if(file_exists('Abstract.htm'))
	$Abstract=file_get_contents('Abstract.htm');

$keyWords='';
if(file_exists('.kw'))
	$keyWords=file_get_contents('.kw');
elseif(!isset($noKeyWords))
{
	function setKeyWord($buffer)
	{
		$Raw=strtolower(preg_replace('#\<([^\<]+)\>#','',$buffer));

		$Words=preg_split('(\s|[-,\'\.«»:\(\)\?!;"&])',$Raw);
		array_map('trim',$Words);
		$Freq=array_count_values($Words);//Équivalent du GROUP BY.
		arsort($Freq,SORT_NUMERIC);//Trier par nombre d'apparition du mot.

		$KeyWords=array();
		foreach($Freq as $Word=>$Nb)
		{
			if(strlen($Word)>4)
			{
				$KeyWords[]=$Word;
				if(count($KeyWords)>30)
					break;
			}
		}
		$KeyWord=addslashes(implode(', ',$KeyWords));

		file_put_contents('.kw',$KeyWord);
		$buffer .='<!--stats keyWord générées-->';
		return $buffer;
	}
	ob_start('setKeyWord');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<title><?php echo $Titre; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="owner" content="Neamar" />
	<meta name="author" content="Neamar" />
	<meta name="robots" content="all" />
	<meta name="rating" content="general" />
	<meta name="reply-to" content="neamar@neamar.fr" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="copyright" content="Copyright © - Some Right Reserved - 2006-<?php echo date("Y"); ?>" />
	<meta name="keywords" content="<?php echo $keyWords ?>" />
	<?php
	if(isset($Abstract))
		echo '<meta name="description" content="' . str_replace("\n",'',str_replace('<br />','',str_replace('"','\'',htmlentities($Abstract)))) . '" />' . "\n";
	?>

	<link href="/Res/ressources.css" rel="stylesheet" type="text/css" media="screen, handheld" />
	<link href="/Res/ressources_print.css" rel="stylesheet" type="text/css" media="print" />


	<link href="/Res/Office.css" rel="stylesheet" title="Office" type="text/css" media="screen, handheld" />

	<link href="/Res/dream.css" rel="alternate stylesheet" title="Dream" type="text/css" />

	<link rel="stylesheet" type="text/css" href="/Res/Codes.css" />
	<?php if(isset($AddLine)) echo $AddLine; ?>
	<link rel="icon" type="image/x-icon" href="/favicon.ico" />
	<script type="text/javascript" src="<?php if(!isset($ScriptURI)){ echo 'https://neamar.fr/Res/ressources.js';} else { echo $ScriptURI; }?>"></script>
</head>

<body>
<div id="Main">
<?php
if(isset($Abstract) && preg_match('#^/Res/(.+)/$#U',$_SERVER['REQUEST_URI']))//N'afficher le résumé que sur la page d'index.
	echo '<p class="abstract erreur"><q>' .$Abstract . '</q></p>';

//enregistrer les infos sur le Referrer dans le fichier Stats :
$fichier = fopen('Stats.txt', 'a'); //Ouvrir le fichier
if(!isset($_SERVER['HTTP_REFERER']))
	$_SERVER['HTTP_REFERER']='';
$Chaine = time() . '|' .  $_SERVER['REMOTE_ADDR'] . '|' . $_SERVER['HTTP_REFERER'] . '|';	//Formater la chaine : Date|IP|Referrer
fputs($fichier, $Chaine);//Puis enregistrer les données
fputs($fichier, "\n");
fclose($fichier); //Et fermer le fichier
?>
