<?php
date_default_timezone_set("Europe/Paris");

//Vérifier qu'il n'y a pas d'erreur et qu'on vient bien d'une page à Saga.
if(!isset($_Nom))
	exit();

if(!isset($UseCache))
	$UseCache = true;

if(!defined('FILE_ARE_UTF8'))
	define('FILE_ARE_UTF8',false);

error_reporting(E_ALL);

//La classe pour gérer un dialogue
include('../Sagas/Codes/Dialogue.php');

//Le titre ne doit pas contenir ce caractères spéciaux sous peine de permettre l'utilisation de n'importe quel fichier.
if(isset($_GET['E']))
	$_GET['E']=preg_replace('#[^A-Z0-9_]#i','',$_GET['E']);

if(!isset($_GET['E']))
	$Titre='Références et statistiques sur ' . $_Nom;
else
	$Titre='Liste des références de l\'épisode ' . $_GET['E'] . ' &ndash; ' . $_Nom;


//Fonction pour afficher les liens suivants et précédents.
function showBeforeAfter()
{

	global $NBEpisode;
	if(!is_numeric($_GET['E']))
		return;
	if($_GET['E']>1)
		echo '<p style="text-align:left;"><a href="Episode-' . ($_GET['E']-1) . '">Épisode précédent &larr;</a></p>';
	if($_GET['E']<$NBEpisode)
		echo '<p style="text-align:right;"><a href="Episode-' . ($_GET['E']+1) . '">Épisode suivant &rarr;</a></p>';
}

//Afficher la base du HTML
include('../header.php');

//echo '<p style="color:red;">Épisode 14 de Reflets d\'Acide disponible depuis le 4 octobre, les <a href="/Res/Reflets/Episode-14">références</a> sont disponibles !<br /><span class="petitTexte" style="color:black;">Vous aimez Reflets d\'Acide ? Vous devriez jeter un &oelig;il sur <a href="http://omnilogie.fr">omnilogie</a> pour continuer de vous cultiver !</p>';
?>
<script type="text/javascript">
function gID(Item)
{
	return document.getElementById(Item);
}
</script>
<?php

















//----------------------------------------------------------------------------------------------------------------------------------
///CAS OÙ ON DEMANDE LA PAGE D'ACCUEIL
//Notons que ce n'est pas si simple : il peut y avoir un cache à lire, des données à traiter si le visiteur utiliser À votre tambouille, ou un cache à rafraichir.
if(!isset( $_GET['E']) || (is_numeric($_GET['E']) && $_GET['E']>$NBEpisode))
{

	?>
	<h1>Statistiques globales sur <?php echo  $_Nom; ?></h1>
	<form action="http://www.google.fr/cse" id="cse-search-box" class="centre">
	<div>
		<input type="hidden" name="cx" value="partner-pub-4506683949348156:g5irco-o1uv" />

		<input type="hidden" name="ie" value="ISO-8859-1" />
		<input type="text" name="q" size="31" />
		<input type="submit" name="sa" value="Rechercher" />
	</div>
	</form>
	<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=fr"></script>


	<?php
	$cache = '.cache';
	if($UseCache && file_exists($cache) && date('d',filemtime($cache)) == date('d') && !isset($_POST['Envoi']))
		readfile($cache);
	elseif(isset($_POST['Envoi']))
	{
		include('../Sagas/PrepareForStats.php');
		include('Stats.php');
		include('../Sagas/CommonStats.php');
	}
	else
	{
		if($UseCache)
			ob_start();
		include('../Sagas/PrepareForStats.php');
		include('Stats.php');
		include('../Sagas/CommonStats.php');
		if($UseCache)
		{
			$page = ob_get_contents(); // copie du contenu du tampon dans une chaîne
			file_put_contents($cache, $page) ; // on écrit la chaîne précédemment récupérée ($page) dans un fichier ($cache)
			ob_flush();
		}
	}

	include('../footer.php');
	exit();
}











//----------------------------------------------------------------------------------------------------------------------------------
///CAS OÙ ON DEMANDE UN ÉPISODE
if(isset($DisableEpisodes))
	exit('L\'accès aux épisodes n\'est pas disponible pour l\'instant, merci.');

//Dans ce cas, il suffit de charger un seul fichier et de l'envoyer.
$cache='.cache-' . $_GET['E'];
if(file_exists($cache) && date('d',filemtime($cache)) == date('d') && !isset($_POST['Envoi']) && 0)
	readfile($cache);
else
{
// 	ob_start();
	if(is_numeric($_GET['E']))
	{
		$Episode = new Dialogue("Épisode n°" .  $_GET['E']);
		$Episode->CreateFromFile('Textes/' . $_Prefix . '-' . $_GET['E'] . '.html');
	}
	elseif(is_file('Textes/' . $_GET['E'] . '.html'))
	{
		$Episode = new Dialogue('');
		$Episode->CreateFromFile('Textes/' . $_GET['E'] . '.html');
	}
	else
		exit('WTF ar u doing ? (Sagas/index.php, ligne 151)');

	$Episode->OutputIntro();
	?>
	<p class="erreur"><img src="http://i.creativecommons.org/l/by-nc/2.0/fr/88x31.png" alt="CC BY-NC" />Cette &oelig;uvre est un travail collaboratif basé sur l'ouvrage de <?php echo $_Auteur; ?>. Les internautes ayant participé sont listés sur la <a href="./">page d'accueil</a> du projet.<br /><br />
	Une subtilité n'est pas référencée ? N'hésitez pas à la <a rel="nofollow" href="http://neamar.fr/Mail.php">signaler</a> !</p>
	<?php

	showBeforeAfter();
	?>
	<p class="centre"><a href="./">Index et statistiques</a></p>
	<?php
	echo '<h2>Statistiques de l\'épisode</h2>';

	$Episode->OutputStats();

	echo '<h2>Texte de l\'épisode</h2>';
	$Episode->OutputText();


	showBeforeAfter();
	?>
	<p class="centre"><a href="./">Retour à l'index et affichage des statistiques</a></p>

	<script type="text/javascript" src="http://neamar.fr/Res/Sagas/Edit.js"></script>

	<?php
	include('../footer.php');
// 	$page = ob_get_contents(); // copie du contenu du tampon dans une chaîne
// 	file_put_contents($cache, $page) ; // on écrit la chaîne précédemment récupérée ($page) dans un fichier ($cache)
// 	ob_flush();
}


?>
