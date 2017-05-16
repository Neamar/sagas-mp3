<?php
class Dialogue
{
	// Déclarations des données membres
	public $Nom;
	public $Chapitres=array();
	public $StatsAuteurs=array();
	public $Auteurs=array();
	public $Phrases=array();
	public $Nombre_Lignes;
	public $Lien;

	private $NBInterventions = null;
	private $DernierInterlocuteur='';

	private $ID;

	//Le constructeur
	public function __construct($Titre,$URL='/')
	{
		$this->Nom=str_replace("\n",'',$Titre);
		$this->ID=substr($URL,strrpos($URL,'/')+1);
		$this->Nombre_Lignes=0;
		$this->Lien=$URL;
	}

/////////////////////

//FONCTIONS PUBLIQUES

/////////////////////

	//Remplir l'objet à partir du contenu d'un fichier proprement formaté :
	public function CreateFromFile($Fichier)
	{
		if(!is_file($Fichier))
			throw new Exception("Le fichier " . $Fichier . " n'existe pas.");
		$Lignes=file($Fichier,FILE_IGNORE_NEW_LINES);
		if(FILE_ARE_UTF8)
		{
			$Lignes=array_map('utf8_decode',$Lignes);
			$Lignes[0]=substr($Lignes[0],1);//BOM
		}

		//Mettre à jour le titre :
		if($this->Nom!='')
			$this->Nom .= ' : ' . $Lignes[0];
		else
			$this->Nom = $Lignes[0];
		unset($Lignes[0]);

		//Puis récuperer le dialogue :
		foreach($Lignes as $buffer)
		{
			if(preg_match('#Chapitre ([0-9]+) - (.+)#is',$buffer))
				$this->AddChapter(str_replace('-','&ndash;',$buffer));
			elseif($buffer!='' && $buffer!="\n")
			{
				$Ligne = explode(" : ",$buffer,2);
				if(count($Ligne)==2)
					$this->AddLine($Ligne[0],$Ligne[1]);
				else//C'est la suite de la phrase précédente.
					$this->AppendLastLine($buffer);
			}
		}
	}

	//Renvoie le texte d'introduction :
	public function OutputIntro($Taille=1)
	{
		$Texte='<h' . $Taille . '  class="clear">' . $this->Nom . '</h' . $Taille . '>';
		echo $Texte;
	}

	//Renvoie le texte du dialogue :
	public function OutputText()
	{
		$Remplacements=array(
		'oe'=>'&oelig;',
		'?!'=>'&#8253;',
		'!?'=>'&#8253;',
		'OE'=>'&OElig;',
		'Oe'=>'&OElig;',
		'ae'=>'&aelig;',
		'AE'=>'&AElig;',
		'Ae'=>'&AElig;',
		'...'=>'&hellip;',
		);
 		$Texte="\n";
		foreach($this->Phrases as $ID=>$Ligne)
		{
			if(isset($this->Chapitres[$ID]))
			{//Nouveau chapitre
				if($ID>=2)
					$Texte .= '</dl>' . "\n\n";
				$Texte .= '<h3 class="clear">' . $this->Chapitres[$ID] . '</h3>';
				$Texte .= "\n\n" . '<p class="petitTexte floatR"><a href="#" onclick="ToggleEditMode(this,\'Dialogue_' . $ID . '\'); return false;">Passer en mode édition</a></p><p class="clear"></p>
<dl id="Dialogue_' . $ID . '">' . "\n";
			}

			foreach($Ligne as $Auteur=>$Phrase)
			{
				if(substr($Phrase,0,1)=="(")
				{//Nous sommes en présence d'une didascalie !
					$Didascalie=substr($Phrase,1,strpos($Phrase,')')-1);
					$Auteur .= ' <em>[' . $Didascalie . ']</em>';
					$Phrase=substr($Phrase,strlen($Didascalie)+2);
				}

				$Texte .= '<dt>' . $Auteur . '</dt>' . "\n";

				if(strpos($Phrase,"REF:")!==false)
				{//Ajoute une référence
					$Reference = '<span class="Reference floatR">' . preg_replace('#(é|É)pisode ([1-9]?[0-9])#i','<a href="Episode-$2">$0</a>',preg_replace('#"(.+)"#isU','«&nbsp;<strong>$1</strong>&nbsp;»',substr($Phrase,strpos($Phrase,"REF:")+4))) . '</span>';
					$Phrase=substr($Phrase,0,strpos($Phrase,"REF:"));
				}
				elseif(strpos($Phrase,"REF :")!==false)
				{//Ajoute une référence
					$Reference = '<span class="Reference floatR">' . preg_replace('#(é|É)pisode ([1-9]?[0-9])#i','<a href="Episode-$2">$0</a>',preg_replace('#"(.+)"#isU','«&nbsp;<strong>$1</strong>&nbsp;»',substr($Phrase,strpos($Phrase,"REF :")+5))) . '</span>';
					$Phrase=substr($Phrase,0,strpos($Phrase,"REF :"));
				}
				elseif(strpos($Phrase,"JDM:")!==false)
				{//Ajoute un jeu de mots
					$Reference = '<span class="Reference floatR"><small>LOL</small>&nbsp;: ' . substr($Phrase,strpos($Phrase,"JDM:")+4) . '</span>';
					$Phrase=substr($Phrase,0,strpos($Phrase,"JDM:"));
				}
				elseif(preg_match('#TRI$#',$Phrase))
				{//Ajoute une tricheuliade
					$Reference = '<span class="Reference floatR"><small><strong>TRICHELIADE !</strong></small></span>';
					$Phrase=substr($Phrase,0,strrpos($Phrase,"TRI"));
				}
				else
					$Reference='';

				$Texte .= '	<dd';
				if($Reference!='')
					$Texte .=' class="ReferenceParent"';
				$Texte .='>' . $Reference . nl2br(str_replace(array_keys($Remplacements),array_values($Remplacements),$Phrase)) . '</dd>' . "\n";
			}
		}
		$Texte .= '</dl>' . "\n";
		echo $Texte;
		echo $this->OutputRef();
	}

	public function StartStats()
	{//génere les tableaux utiles aux statistiques
		$this->StatsAuteurs=array_count_values($this->Auteurs);
		arsort($this->StatsAuteurs);
		$this->Nombre_Lignes=count($this->Auteurs);
	}
	//Renvoie les statistiques :
	public function OutputStats($ListeType="ol")
	{//Renvoie toutes les statistiques.
		$this->StartStats();
		$this->OutputIMGStats('floatR');
		$this->OutputRawStats('500x200',$ListeType);
	}

	public function OutputRawStats($Taille='500x200',$ListeType="ol")
	{//Ne renvoie que la liste des protagonistes. Nécessite d'avoir lancé StartStats() pour fonctionner.
		$Stats='';
		$Max = max($this->StatsAuteurs);
		$Stats .= '<p>Nombre de lignes pour cet épisode : ' . $this->Nombre_Lignes . '</p>' . "\n";
		//On utilise des colonnes pour clarifier la lecture
		$Stats .= '<' . $ListeType . ' style="-moz-column-count: 2; -moz-column-gap:5em; min-height:220px;">' . "\n";
		foreach($this->StatsAuteurs as $Auteur=>$NB)
		{
			$PourcentageREL=round(100*$NB/$Max);
			$PourcentageABS_NotRounded=100*$NB/$this->Nombre_Lignes;
			$PourcentageABS=round($PourcentageABS_NotRounded);

			//La liste
			$Stats .= '<li style="';
			if($PourcentageABS_NotRounded<1.5)
				$Stats .='font-size:x-small;"';
			else
				$Stats .= 'cursor:wait;" onmouseover="gID(\'Graphique' . $this->ID .'\').SVG=gID(\'Graphique' . $this->ID .'\').src; gID(\'Graphique' . $this->ID .'\').src=\'http://chart.apis.google.com/chart?chs=' . $Taille . '&amp;cht=gom&amp;chd=t:' . $PourcentageREL . ',' . $PourcentageABS . '&amp;chl=(relatif)|(absolu)&amp;chtt=' . str_replace('Ï','I',$Auteur) . '\';" onmouseout="gID(\'Graphique' . $this->ID .'\').src=gID(\'Graphique' . $this->ID .'\').SVG;"';//Quand on passe la souris sur un des noms, cela affiche un indicateur pour la personne. Géré via Javascript.
			$Stats .= '>' . $Auteur . '&rarr;' . $NB . '</li>' . "\n";
		}
		$Stats .= '</' . $ListeType . '>' . "\n";
		echo $Stats;
	}

	public function OutputIMGStats($Class,$Taille='500x200')
	{//Ne renvoie que l'URL de l'image correspondante à l'épisode . Nécessite d'avoir lancé StartStats() pour fonctionner.
		$IMGValue='';
		$IMGCaption='';

		$Max = max($this->StatsAuteurs);
		//On utilise des colonnes pour clarifier la lecture
		foreach($this->StatsAuteurs as $Auteur=>$NB)
		{
			$PourcentageABS=100*$NB/$this->Nombre_Lignes;
			//L'image globale
			if($PourcentageABS>=1.5)
			{
				$IMGValue .= round($PourcentageABS) . ',';
				$IMGCaption .=$Auteur . '|';
			}
		}
		$Stats = '<p><img id="Graphique' . $this->ID . '" class="' . $Class . '"  src="http://chart.apis.google.com/chart?chs=' . $Taille . '&amp;cht=p3&amp;chco=FFFFFF,FF0000,00FF00,0000FF,000000&amp;chd=t:' . substr($IMGValue,0,-1) . '&amp;chl=' . str_replace(str_split('ÉÈÊËÀÂÄÙÛÎÏÔ°'),str_split('EEEEAAAUUIIOo'),substr($IMGCaption,0,-1)) . '" alt="Représentation graphique" onmouseover="this.src=this.src.replace(\'cht=p3\',\'cht=p\');" " onmouseout="this.src=this.src.replace(\'cht=p\',\'cht=p3\');" /></p>';
		echo $Stats;
	}

	public function GetRef()
	{//renvoie le nombre de références et de jeux de mots
		$NBRef=$NBJdm=$NBTri=0;
		foreach($this->Phrases as $ID=>$Ligne)
		{
			foreach($Ligne as $Auteur=>$Phrase)
			{
				if(strpos($Phrase,"REF:")!==false)
					$NBRef++;
				elseif(strpos($Phrase,"JDM:")!==false)
					$NBJdm++;
				elseif(strpos($Phrase,"TRI")!==false)
					$NBTri++;
			}
		}
		return array($NBRef,$NBJdm,$NBTri);
	}
	public function OutputRef()
	{//Affiche le nombre de référence. Fait appel à GetRef().
		$Ref=$this->GetRef();
		$Texte = '<h2>Références et jeux de mots</h2>' . "\n" . '<ul>' . "\n";
		$Texte .= '<li>Nombre de références : ' . $Ref[0] . '</li>' . "\n";
		$Texte .= '<li>Nombre de jeux de mots cachés (notion certes subjective, qui n\'englobe pas les running jokes ni les blagues « évidentes ») : ' . ($Ref[1]+$Ref[2]) . '</li>' . "\n";
		$Texte .= '</ul>' . "\n";
		echo $Texte;
	}


/////////////////////

//FONCTIONS PRIVÉES

/////////////////////

	//Ajouter un chapitre :
	private function AddChapter($Titre)
	{
		$this->Chapitres[count($this->Phrases)] =$Titre;
	}


	private function AddLine($Auteur,$Phrase)
	{//Ajouter une phrase :
		$Phrase=strtoupper($Phrase{0}) . substr($Phrase,1);
		array_push($this->Phrases,array($Auteur=>$Phrase));
		$this->DernierInterlocuteur=$Auteur;

		array_push($this->Auteurs,$Auteur);
	}


	private function AppendLastLine($SuitePhrase)
	{//Continuer la dernière phrase :
		$DerniereLigne=array_pop($this->Phrases);
		array_push($this->Phrases,array($this->DernierInterlocuteur=>$DerniereLigne[$this->DernierInterlocuteur] . "\n" . $SuitePhrase));
	}
}
?>
