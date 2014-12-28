function WizardNavButtonOnClick($action){
	document.getElementById("action").value=$action;
	document.getElementById("frmProperty").submit();
}
function WizardOnDropDownChange(){
	document.getElementById("frmWizard").submit();
}
function WizardOnSectorDropDownChange(){
	sector=document.getElementById("sector_id");
	value=sector.options[sector.selectedIndex].value;
	if (value==-1){
		var the_name=window.prompt("Introdu Sectorul","");
		document.getElementById("sector_new").value=the_name;
	}
	document.getElementById("frmWizard").submit();
}
function WizardUploadButtonOnClick(){
	document.getElementById("frmProperty").submit();
}