const LECTURE=0;
const EDITION=1;
var Strings=new Array("Passer en mode édition","");

var Mode=LECTURE;

function ToggleEditMode(o,t)
{
	if(Mode==LECTURE)
		Mode=EDITION;
	else
		Mode=LECTURE;
	o.innerHTML=Strings[Mode];

	var DT=document.getElementById(t).getElementsByTagName('dt');
	var DD=document.getElementById(t).getElementsByTagName('dd');

	var NewHTML='';
	for(i=0;i<DT.length;i++)
	{
		if(Mode==EDITION)
		{
			NewHTML='<form method="post" action="/Res/Sagas/Modif.php">';
			NewHTML+='<input type="hidden" name="Episode'+i+'" value="'+document.location+'<br />" />';
			NewHTML+='<input type="hidden" name="Content'+i+'" value="<strong>' + DT[i].innerHTML +'</strong><br />' + DD[i].innerHTML.replace(/\"/g,'|') +'" />';
			NewHTML+='<input type="submit" value="Edit" class="floatL petitTexte" /></form>'
			NewHTML+=DT[i].innerHTML;
		}
		else
		{
			NewHTML=DT[i].innerHTML;
			DT[i].getElementsByTagName('form').innerHTML='';
		}

		DT[i].innerHTML =NewHTML;
	}
}
