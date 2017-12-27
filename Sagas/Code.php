<?php
$NoPub=true;
$Box = array("Auteur"=>"Neamar", "Date" => "Aout 2008", "But" =>"Traitement données","Voir aussi"=>'<a href="/Res/Reflets/">Accueil Reflets</a>');

$Titre="Code source de la classe Dialogue.";
include('../header.php');
?>
<h1>Code</h1>
<p>Voici la classe utilisée pour générer toutes ces statistiques : (n'hésitez pas à me contacter pour plus d'informations)</p>
<p>Le but de ce code était de <strong>ne pas utiliser de connexions à une base de données</strong>, afin de limiter le trafic, d'où l'utilisation d'une classe dédiée.</p>
<?php
InclureCode('Dialogue.php',"PHP");
include('../footer.php');
?>
