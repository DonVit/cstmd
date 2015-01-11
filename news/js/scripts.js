function _WizardNavButtonOnClick($action){
	document.getElementById("action").value=$action;
	document.getElementById("frmNews").submit();
}
function _WizardOnDropDownChange(){
	document.getElementById("frmNews").submit();
}
function WizardLocationsRemoveOption(){
	var x=document.getElementById("lb_locations");
	x.remove(x.selectedIndex);
}
function WizardLocationsAddOptions(from,to) {
        var select1=document.getElementById(from);
        var select2=document.getElementById(to);
        var newoption=document.createElement('option');
        newoption.value=select1.options[select1.selectedIndex].value;
        newoption.text=select1.options[select1.selectedIndex].text;
	newoption.selected=true;
	select2.add(newoption,null);
	select1.remove(select1.selectedIndex);
	select1.options[select1.length-1].selected=true;
	selectall(to);
}
function WizardLocationsRemoveOptions(from,to) {
        var select1=document.getElementById(from);
        var select2=document.getElementById(to);
        var newoption=document.createElement('option');
        newoption.value=select2.options[select2.selectedIndex].value;
        newoption.text=select2.options[select2.selectedIndex].text;
	select1.add(newoption,null);
	select2.remove(select2.selectedIndex);
	select2.options[select2.length-1].selected=true;
	selectall(to);
}
function selectall(selector) {
        var sel=document.getElementById(selector);

        for (i=0;i<=sel.length-1;i++){
		sel.options[i].selected=true;
	}
}