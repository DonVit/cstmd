function WizardNavButtonOnClick($action){
	document.getElementById("action").value=$action;
	document.getElementById("frmImage").submit();
}
function WizardOnDropDownChange(){
	document.getElementById("frmImage").submit();
}