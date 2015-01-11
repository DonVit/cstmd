function _WizardNavButtonOnClick($action){
	document.getElementById("action").value=$action;
	document.getElementById("frmImage").submit();
}
function _WizardOnDropDownChange(){
	document.getElementById("frmImage").submit();
}