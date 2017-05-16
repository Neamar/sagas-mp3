<?php
$Episode=array();

for($i=1;$i<=$NBEpisode;$i++)
{
	$Episode[$i] = new Dialogue("Épisode n°" .  $i,'/Res/' . $_Prefix . '/Episode-' . $i);
	$Episode[$i]->CreateFromFile('Textes/' . $_Prefix . '-' . $i . '.html');
}

foreach($Bonus as $Lien)
{
	$E = new Dialogue('Bonus','/Res/' . $_Prefix . '/' . $Lien);
	$E->CreateFromFile('Textes/' . $Lien . '.html');

	$Episode[]=$E;
}

function AfficherBoiteSaga(&$Episode)
{
	echo '<div><p>Sélectionnez un épisode !</p>' . "\n<ol class=\"Comptable\">";
	foreach($Episode as &$E)
	{
		echo '<li><a href="' . $E->Lien . '">' . $E->Nom . "</a>\n<ol class=\"Comptable petitTexte\">";
		foreach($E->Chapitres as $Chapitre)
			echo '	<li>' . $Chapitre . '</li>' . "\n";
		echo "</ol>\n</li>\n";
	}
	echo '</ol></div>';
}
?>
