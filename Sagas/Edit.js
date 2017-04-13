const LECTURE = 0;
const EDITION = 1;
var Strings = new Array("Passer en mode édition", "");

var Mode = LECTURE;

function ToggleEditMode(o,t)
{
  var c = confirm("Vous allez être redirigé vers Github. Vous aurez besoin d'un compte sur le site pour proposer votre modification. Cliquez simplement sur l'icone en forme de crayon, puis proposez votre changement.");

  if(c) {
    var path = document.location.toString().replace("http://neamar.fr/Res/", "").split("/");
    var saga = path[0]
    // var episode = path[1];
    document.location = "https://github.com/Neamar/sagas-mp3/blob/master/" + saga + "/Textes/";
  }

}
