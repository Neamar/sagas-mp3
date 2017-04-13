<?php
$Titre='Modification des subtilités';

include('../header.php');

if(!isset($_POST['Modif']))
{
?>
<h1>Proposer une modification !</h1>
<form method="post" action="">
<input type="hidden" value="1" name="Modif" />
<input type="hidden" value="<?php echo stripslashes(implode(' ',$_POST)); ?>" name="Origine" />
<p>Texte à modifier :</p><p class="erreur"><?php echo stripslashes(str_replace('|','"',implode(" ",$_POST))); ?></p>
<p><textarea cols="80" rows="30" name="Proposition">Quelle subtilité ajouter ? Marquez vos idées ici...</textarea><br />
Votre mail (facultatif, permet de vous tenir informé si votre modification est acceptée) : <input type="text" value="Votre mail" name="Expediteur" /><br />
<input type="submit" value="Proposer ma modification" /></p>
<?php
}
else
{
	$to  = 'neamar@neamar.fr';

	if(!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#',$_POST['Expediteur']))
		$Expediteur='neamar@neamar.fr';
	else
		$Expediteur=$_POST['Expediteur'];

	// Sujet
	$subject = 'Modifications Saga';

	// message
	$message = '<strong>Expéditeur</strong> : ' . $_POST['Expediteur'] . "\n" .
	'<br />Concerne : ' . $_POST['Origine'] . "\n" .
	'<br /><br /><br />Proposition :' . stripslashes($_POST['Proposition']);

	// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// En-têtes additionnels
	$headers .= 'From:' . $Expediteur . "\r\n";
	// Envoi
	if(mail($to, $subject, $message, $headers))
		echo '<h1>E-mail envoyé  !</h1><p><strong>En théorie</strong>, je réponds rapidement pour ces modifications...comptez un maximum de 48 heures, sauf en périodes de vacances, WE ou sortie d\'épisode ;)</p>';
	else
		echo '<h1>ERREUR  !</h1><p>Le message n\'est pas parti.<br />' . $_POST['Proposition'] . '</p><p>Merci de réessayer dans quelques minutes.</p>';
}

include('../footer.php');
?>
