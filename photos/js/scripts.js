function WizardNavButtonOnClick($action){
	document.getElementById("action").value=$action;
	document.getElementById("frmWizard").submit();
}
function WizardOnDropDownChange(){
	document.getElementById("frmWizard").submit();
}