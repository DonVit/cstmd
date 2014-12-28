function FilterOnRaionChange($basename){
	var r=document.getElementById("raionid").value;
	var l=0;
	var s=0;
	document.location=$basename+"?raionid="+r+"&locationid="+l+"&sectorid="+s;
}

function FilterOnLocationChange($basename){
	var r=document.getElementById("raionid").value;
	var l=document.getElementById("locationid").value;
	var s=0;
	document.location=$basename+"?raionid="+r+"&locationid="+l+"&sectorid="+s;
}

function FilterOnSectorChange($basename){
	var r=document.getElementById("raionid").value;
	var l=document.getElementById("locationid").value;
	var s=document.getElementById("sectorid").value;
	document.location=$basename+"?raionid="+r+"&locationid="+l+"&sectorid="+s;
}

function RegisterOnSave(){
	var n=document.getElementById("name").value;
	var e=document.getElementById("email").value;
	var p=document.getElementById("password").value;
	var rp=document.getElementById("repassword").value;
	var vc=document.getElementById("validationcode").value;
	if((n!="")&&(e!="")&&(p!="")&&(rp!="")&&(vc!="")){
		if (p!=rp){
			alert("Parola reintrodusa incorect !");
		} else if (!isEmail(e)){
			alert("Email introdus gresit !");
		} else {
			document.getElementById("frmRegister").submit();
		}
	} else {
		alert("Introduceti cimpurile marcate Obligator !");
	}
}
function SettngsOnSave(){
	var n=document.getElementById("name").value;
	var e=document.getElementById("email").value;
	var p=document.getElementById("password").value;
	var rp=document.getElementById("repassword").value;
	if((n!="")&&(e!="")&&(p!="")&&(rp!="")){
		if (p!=rp){
			alert("Parola reintrodusa incorect !");
		} else if (!isEmail(e)){
			alert("Email introdus gresit !");
		} else {
			document.getElementById("frmRegister").submit();
		}
	} else {
		alert("Introduceti cimpurile marcate Obligator !");
	}
}
function isEmail(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   return reg.test(email);
}
function onClickCheckBox(){
	///if (this.value==1){
	//	this.value=0;
	///} else {
	///	this.value=1;
	//}
	if (document.getElementById('negocibil').value==1){
		document.getElementById('negocibil').value=0;
	} else {
		document.getElementById('negocibil').value=1;
	}
}

function FilterOnSubTipimobilChange(){
var f=document.getElementById("fscop").value;
var t=document.getElementById("ftip").value;
var r=document.getElementById("fraionid").value;
var l=document.getElementById("flocalitateid").value;
var s=document.getElementById("fsectorid").value;
var st=document.getElementById("fsubtipimobilid").value;
document.location="imobil.php?fscop="+f+"&ftip="+t+"&fraionid="+r+"&flocalitateid="+l+"&fsectorid="+s+"&fsubtipimobilid="+st;
}
function FilterOnViewTypeChange(){
document.getElementById("frmMenu").submit();
}
function FilterOnViewTypeChange1(){
var t=document.getElementById("ftip").value;
var r=document.getElementById("fraionid").value;
var l=document.getElementById("flocalitateid").value;
var s=document.getElementById("fsectorid").value;
var st=document.getElementById("fsubtipimobilid").value;
var vt=document.getElementById("fviewtypeid").value;
document.location="imobil.php?ftip="+t+"&fraionid="+r+"&flocalitateid="+l+"&fsectorid="+s+"&fsubtipimobilid="+st+"&fviewtypeid="+vt;
}
function ReplaceRow(i)
{	
 document.getElementById("files").rows[i].cells[0].innerHTML="Imaginea "+(i+1)+":<input type=\"hidden\" id=\"fileid[]\" name=\"fileid[]\" value=\""+i+"\"><input type=\"hidden\" id=\"status[]\" name=\"status[]\" value=\"2\">";
 document.getElementById("files").rows[i].cells[1].innerHTML="<input type=\"file\" id=\"file[]\" name=\"file[]\"  style=\"width:50%;\">";
 document.getElementById("files").rows[i].cells[2].innerHTML=""; 
}
function WizardOnCancel()
{
document.getElementById("frmOferta").action="add.php?pas=0";
document.getElementById("frmOferta").submit();
}
function WizardOnBack($pas)
{
document.getElementById("frmOferta").action="add.php?pas="+$pas;
document.getElementById("frmOferta").submit();
}

function WizardOnUpload($pas)
{
document.getElementById("frmOferta").action="add.php?pas="+$pas;
document.getElementById("frmOferta").submit();
}


function WizardOnSubTipImobilChange($pas) {
SubTipImobil=document.getElementById("subtipimobilid");
value=SubTipImobil.options[SubTipImobil.selectedIndex].value;
if (value==-1){
	var the_name=window.prompt("Introdu Tipul","");
	document.getElementById("subtipimobilidnew").value=the_name;
	document.getElementById("frmOferta").action="add.php?pas="+$pas;
	document.getElementById("frmOferta").submit();
}
}

function WizardOnCheckBoxChange(cb){
	if (document.getElementById(cb).value==1){
		document.getElementById(cb).value=0;
	} else {
		document.getElementById(cb).value=1;
	}
}
function WizardOnRaionChange($pas) {
document.getElementById("frmOferta").action="add.php?pas="+$pas;
document.getElementById("frmOferta").submit();
}

function WizardOnLocalitateChange(){
localitate=document.getElementById("localitateid");
value=localitate.options[localitate.selectedIndex].value;
if (value==-1){
	var the_name=window.prompt("Introdu Localiatea","");
	document.getElementById("localitateidnew").value=the_name;
}
document.getElementById("frmOferta").action="add.php?pas="+$pas;
document.getElementById("frmOferta").submit();
}

function WizardOnSectorChange($pas){
sector=document.getElementById("sectorid");
value=sector.options[sector.selectedIndex].value;
if (value==-1){
	var the_name=window.prompt("Introdu Sectorul","");
	document.getElementById("sectoridnew").value=the_name;
}
document.getElementById("frmOferta").action="add.php?pas="+$pas;
document.getElementById("frmOferta").submit();
}
function WizardOnContactChange($pas){
document.getElementById("frmOferta").action="add.php?pas="+$pas;
document.getElementById("frmOferta").submit();
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
	document.getElementById("frmWizard").submit();
}
function isNumberKey(evt)
{
   var charCode = (evt.which) ? evt.which : evt.keyCode
   //if ((charCode < 31)||((charCode > 47 && charCode < 58)||(charCode == 46)))
   // char "/" is not filtered
   if (charCode > 31 && (charCode < 46 || charCode > 57))
      return false;
   return true;
}

function FormPost($frm,$act){
	document.getElementById($frm).action=$act;
	document.getElementById($frm).submit();
}
function ImobilTableRowClick($id){
	document.location = 'property.php?id='+$id;
}
function ImobilTableRowMouseOver($id,$r){
	if ($r==0) {
		$id.className='gridtr0over';
	} else {
		$id.className='gridtr1over';
	}
}
function ImobilTableRowMouseOut($id,$r){
	if ($r==0) {
		$id.className='gridtr0';
	} else {
		$id.className='gridtr1';
	}
}