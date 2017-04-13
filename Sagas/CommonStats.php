<?php
function filter($Str)
{//Enlève les caractères spéciaux des noms pour pouvoir les afficher correctement avec l'API.
	return str_replace(str_split('ÉÈÊËÀÂÄÙÛÎÏ'),str_split('EEEEAAAUUII'),$Str);
}
?>


<!-- Qui parle, et quand ? -->
<h2 class="clear">Qui parle ?</h2>
<?php
$Global=new Dialogue('Stats Globales');
foreach($Episode as &$E)
{
	$E->OutputIntro(3);
	if($E->Lien!='/')
	{
		echo '<p><a href="' . $E->Lien . '">Textes et références pour <strong>' . $E->Nom . '</strong></a></p>';
		//Préparer l'objet aux statistiques en créant les tableaux :
		$E->StartStats();

		//Préparer les statistiques globales en enregistrant les données :
		foreach($E->StatsAuteurs as $Auteur=>$NB)
		{
			if(array_key_exists($Auteur,$Global->StatsAuteurs))
				$Global->StatsAuteurs[$Auteur] +=$NB;
			else
				$Global->StatsAuteurs[$Auteur] = $NB;
		}
		$Global->Nombre_Lignes += $E->Nombre_Lignes;

		//Et trier le tableau au dernier moment :
	}
	$E->OutputIMGStats('floatR','400x150');
	$E->OutputRawStats('400x150');
}

arsort($Global->StatsAuteurs);
$Global->OutputIntro(3);
$Global->OutputIMGStats('floatR','400x150');
$Global->OutputRawStats('400x150');
?>







<!-- Taille des épisodes : Nombre de répliques -->
<h2>Nombre de répliques</h2>
<h3>Nombre de répliques par épisode</h3>
<?php
$Taille=$NBRef=array();
$NbTri=0;
$IMGValue=$IMG2Value='';
$ListValue='';
$IMGCaption='';
for($i=1;$i<=$NBEpisode;$i++)
{
	$Taille[$i] = count($Episode[$i]->Phrases);
	$StatsRef=$Episode[$i]->GetRef();
	$NbTri +=$StatsRef[2];
	$NBRef[$i]=array_sum($StatsRef);
	$IMGCaption .= 'Ep.+' . $i . '|';
	$ListValue .= '<li>' . $Taille[$i] . '/ ' . $NBRef[$i] . "</li>\n";//<br />Moyenne : ' . (round(100*$NBRef[$i]/$Taille[$i])/100) . "</li>\n";
}
 echo '<p>Total : ' . array_sum($Taille) . ' lignes.<br />';
 echo '<p>Total Références :' . array_sum($NBRef) . ' (dont ' . $NbTri . ' tricheliades)</p>';
//Repasser en pourcentage :
$MAX = max($Taille);
$MAXRef=max($NBRef);
for($i=1;$i<=$NBEpisode;$i++)
{
	$IMGValue .= round(100*$Taille[$i]/$MAX) . ',';
	$IMG2Value .= round(100*$NBRef[$i]/$MAXRef) . ',';
}
echo '<p><img class="floatR" src="http://chart.apis.google.com/chart?cht=lc&amp;chs=730x350&amp;chd=t:' . substr($IMGValue,0,-1) . '|' . substr($IMG2Value,0,-1) . '&amp;chxt=x,y&amp;chxl=0:|' . $IMGCaption .  '1:||' . round($MAX/2) . '|' . $MAX . '+\/+' . $MAXRef . '&amp;chco=FF0000,AAFFAA&amp;chdl=Repliques|References" alt="Taille en nombre de répliques" /></p>' . "\n";
echo '<p>Lignes/ Réf.</p><ol>' . $ListValue . '</ol>';
echo '<p class="erreur">ATTENTION : L\'échelle indique le nombre de répliques, et non le nombre de références, qui utilise une autre échelle.<br />Les deux courbes ne sont mises sur le même graphique qu\'afin de mettre en relation le nombre de références et la taille des épisodes.</p>';
?>
<h3>Nombre de répliques par épisode et par personnage</h3>
<p>Afin d'éviter une débauche de graphiques et de données, seuls les personnages importants et récurrents sont listés...</p>
<p class="erreur" id="ArriveeFormulaire">Le permier graphique indique le nombre de répliques en absolu.<br />
Le second graphique pondère en fonction de la taille de l'épisode : cela donne donc un pourcentage plus représentatif de la réalité.</p>
<?php
if(!isset($_POST['Envoi']))
	$Persos=$PersoDefaut;//Charger les persos par défaut pour la saga.
else
{
	$Couleur=array("FF0000","00FF00","0000FF","000000","AAAAAA","AA0000","00AA00","0000AA","ABCDEF","9911CC","ABEFCD");
	$Persos=array();
	unset($_POST['Envoi']);
	foreach($_POST as $Perso=>$Inutile)
	{
		$Persos[str_replace("_",' ',$Perso)]=$Couleur[array_rand($Couleur)];
	}
}

//Pour tous les personnages demandés
$PersosDATAS=array();
$BIGMAX=$BIGMAXREL=0;
foreach($Persos as $Perso=>$Couleur)
{
	echo '<h4 class="clear">' . $Perso . '</h4>' . "\n";
	//Pour chaque perso, génerer les stats
	$Taille=array();
	$TailleREL=array();//La taille relative à la taille de l'épisode
	$IMGValue=$IMGValueREL='';
	$ListValue='';
	$IMGCaption='';
	for($i=1;$i<=$NBEpisode;$i++)
	{
		if(array_key_exists($Perso,$Episode[$i]->StatsAuteurs))
		{
			$Taille[$i] = $Episode[$i]->StatsAuteurs[$Perso];
			$TailleREL[$i]=100*($Episode[$i]->StatsAuteurs[$Perso]/array_sum($Episode[$i]->StatsAuteurs));
		}
		else
			$Taille[$i]=$TailleREL[$i]=0;
		$IMGCaption .= 'Ep.+' . $i . '|';
		$ListValue .= "<li>$Taille[$i]</li>\n";
	}
	//Repasser en pourcentage :
	$MAX = max($Taille);
	if($MAX!=0)
	{
		$MAXREL = max($TailleREL);
		$TailleSVG=$Taille;
		$TailleSVGREL=$TailleREL;
		for($i=1;$i<=$NBEpisode;$i++)
		{
			$Taille[$i]=round(100*$Taille[$i]/$MAX);
			$IMGValue .= $Taille[$i] . ',';
			$TailleREL[$i]=round(100*$TailleREL[$i]/$MAXREL);
			$IMGValueREL .= $TailleREL[$i] . ',';
		}
		//Le graphique absolu
		echo '<p class="floatR"><img src="http://chart.apis.google.com/chart?cht=lc&amp;chs=440x150&amp;chtt=' . filter($Perso) . '+(absolu)&amp;chd=t:' . substr($IMGValue,0,-1) . '&amp;chxt=x,y&amp;chxl=0:|' . $IMGCaption .  '1:||' . round($MAX/2) . '|' . $MAX . '&amp;chco=' . $Couleur . '" alt="Nombre de répliques de ' . $Perso . ' (absolu)" /><br />' . "\n";

		//Et le relatif
		echo '<img src="http://chart.apis.google.com/chart?cht=lc&amp;chs=400x150&amp;chtt=' . filter($Perso) . '&amp;chd=t:' . substr($IMGValueREL,0,-1) . '&amp;chxt=x,y&amp;chxl=0:|' . $IMGCaption .  '1:||' . round($MAXREL/2) . '%|' . round($MAXREL) . '%&amp;chco=' . $Couleur . '" alt="Nombre de répliques de ' . $Perso . ' (relatif)" /></p>' . "\n";

		echo '<ol>' . $ListValue . '</ol>' . "\n<p>Total : " . array_sum($TailleSVG) . ' répliques<br />Maximum : ' . max($TailleSVG) . '<br />Minimum : ' . min($TailleSVG) . '<br />Moyenne : ' . round(array_sum($TailleSVG)/$NBEpisode) . '</p>';
		$PersosDATAS[$Perso]=array(array_sum($Taille),$TailleSVG,$TailleSVGREL);
		$BIGMAX=max(max($TailleSVG),$BIGMAX);
		$BIGMAXREL=max(max($TailleSVGREL),$BIGMAXREL);
	}
	else
		echo '<p><strong>' . $Perso . '</strong> n\'apparait pas dans les épisodes standards de ' . $_Nom . '.<br />Il est probable qu\'il ne fasse des apparitions que dans les bonus !';
}
?>
<h3 class="clear">Mise en commun</h3>
<h4 class="clear">Absolu : Nombre de répliques par épisode</h4>
<?php
$IMGValue=$IMGValueREL='';
foreach($PersosDATAS as $Data)
{
	for($i=1;$i<=$NBEpisode;$i++)
	{
		$IMGValue .= round(100*$Data[1][$i]/$BIGMAX) . ',';
		$IMGValueREL .= round(100*$Data[2][$i]/$BIGMAXREL) . ',';
	}
	$IMGValue = substr($IMGValue,0,-1) . "|";
	$IMGValueREL = substr($IMGValueREL,0,-1) . "|";
}

//Le graphique absolu
$ListeCouleurs=implode(",",$Persos);
$ListePerso= implode("|",array_keys($Persos));
echo '<p class="floatR"><img src="http://chart.apis.google.com/chart?cht=lc&amp;chs=810x370&amp;chtt=Qui+parle+?+(absolu)&amp;chd=t:' . substr($IMGValue,0,-1) . '&amp;chco=' . $ListeCouleurs . '&amp;chxt=x,y&amp;chxl=0:|' . $IMGCaption .  '1:||' . round($BIGMAX/2) . '|' . $BIGMAX . '&amp;chdl=' . filter($ListePerso) . '" alt="Qui parle quand ?" /></p>' . "\n";
?>
<h4 class="clear">Relatif : Nombre de répliques par épisode, rapporté à la taille de l'épisode</h4>
<?php
//Le graphique relatif
echo '<p class="floatR"><img src="http://chart.apis.google.com/chart?cht=lc&amp;chs=810x370&amp;chtt=Qui+parle&amp;chd=t:' . substr($IMGValueREL,0,-1) . '&amp;chco=' .  $ListeCouleurs . '&amp;chxt=x,y&amp;chxl=0:|' . $IMGCaption .  '1:||' . round($BIGMAXREL/2) . '%|' . round($BIGMAXREL) . '%&amp;chdl=' . filter($ListePerso) . '" alt="Qui parle quand ?" /></p>' . "\n";
?>
<h3 class="clear">À votre tambouille !</h3>
<form method="post" action="index.php#ArriveeFormulaire">
<fieldset>
<legend>Liste des personnages</legend>
<p>Sélectionnez les personnes que vous souhaitez comparer, puis appuyez sur Valider.<br />
(personnages triés par ordre d'apparition dans la série)</p>
<ul style="-moz-column-count: 3; -moz-column-gap: 5em;">
<?php
$Persos=array();
foreach($Global->StatsAuteurs as $Auteur=>$Nb)
	array_push($Persos,$Auteur);

$Persos=array_unique($Persos);
foreach($Persos as $Auteur)
	echo '<li><input type="checkbox" name="' . $Auteur . '" id="Perso-' . str_replace(" ","-",$Auteur) .'"/> <label for="Perso-' . str_replace(" ","-",$Auteur) .'">' . $Auteur . '</label></li>';
?>
</ul>
<p class="centre"><input type="hidden" value="Envoi" name="Envoi" /><input type="submit" value="Valider !" /></p>
</fieldset>
</form>