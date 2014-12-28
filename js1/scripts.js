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
		} else {
			document.getElementById("frmRegister").submit();
		}
	} else {
		alert("Introduceti cimpurile marcate Obligator !");
	}
}

//Wizard 
function WizardOnDropDownChange(){
	document.getElementById("frmProperty").submit();
}

function WizardOnSectorDropDownChange(){
	sector=document.getElementById("sector_id");
	value=sector.options[sector.selectedIndex].value;
	if (value==-1){
		var the_name=window.prompt("Introdu Sectorul","");
		document.getElementById("sector_new").value=the_name;
	}
	document.getElementById("frmProperty").submit();
}
function WizardNavButtonOnClick($action){
document.getElementById("action").value=$action;
document.getElementById("frmProperty").submit();
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
 document.getElementById("files").rows[i].cells[1].innerHTML="<input type=\"file\" id=\"file[]\" name=\"file[]\" >";
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
function WizardOnMapLoad() {
	var marker;
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl);
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);		
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lat,lng), {draggable: true});
			map.addOverlay(marker);
		}

		GEvent.addListener(map, 'click', function(overlay, point) 
		{
			if (marker!=undefined){
				map.removeOverlay(marker);
			}
			marker = new GMarker(point, {draggable: true});
			map.addOverlay(marker);
			
			document.getElementById("lat").value=point.x;
			document.getElementById("lng").value=point.y;
			document.getElementById("zoom").value=map.getZoom();	
			document.getElementById("maptype").value=WizardGetCurrentMapTypeNumber(map);	
			document.getElementById("centerlat").value=map.getCenter().lat();
			document.getElementById("centerlng").value=map.getCenter().lng();
			
		});
		GEvent.addListener(marker, 'dragend', function() {

          	document.getElementById("lat").value=marker.getPoint().lat();
			document.getElementById("lng").value=marker.getPoint().lng();
			document.getElementById("zoom").value=map.getZoom();	
			document.getElementById("maptype").value=WizardGetCurrentMapTypeNumber(map);	
			document.getElementById("centerlat").value=map.getCenter().lat();
			document.getElementById("centerlng").value=map.getCenter().lng();
        });	
}
}
function WizardGetCurrentMapTypeNumber(map)
{
  var type=-1;
  for(var ix=0;ix<map.getMapTypes().length;ix++)
  {
    if(map.getMapTypes()[ix]==map.getCurrentMapType())
      type=ix;
  }
  return type;
} 
function WizardOnContactChange($pas){
document.getElementById("frmOferta").action="add.php?pas="+$pas;
document.getElementById("frmOferta").submit();
}



function ImobilViewOnMapLoad() {
	var marker;
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl);
		
		var lat=document.getElementById("lat").value;
		var lng=document.getElementById("lng").value;
		var zoom=parseInt(document.getElementById("zoom").value);		
		var maptype=parseInt(document.getElementById("maptype").value);
    	var centerlat=document.getElementById("centerlat").value;
    	var centerlng=document.getElementById("centerlng").value;
		
        map.setCenter(new GLatLng(centerlat, centerlng), zoom,  map.getMapTypes()[maptype]);

		if ((lat!=0)&&(lng!=0)){
			marker = new GMarker(new GPoint(lat,lng));
			map.addOverlay(marker);
		}
}
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
