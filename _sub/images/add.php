<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class AddImageWebPage extends MainWebPage {
	public $mod="view";
	public $step=1;
	public $steps=4;
	public $step_title="";
	public $currentphoto;	

	public $errormessage="";
	function __construct(){
		parent::__construct();
		$this->setJavascript("js/scripts.js");
		$this->setCSS("style/styles.css");

		//get photo from session
		$this->currentphoto=User::getCurrentPhoto();

		//store photo properties temporarily
		$tmpraionid=$this->currentphoto->raion_id;

		Logger::setLogs("current photo before=".$this->currentphoto);
		foreach ($_POST as $field => $value) {
			if ($this->currentphoto->isMember($field)){
				$this->currentphoto->$field=$this->getParamValue($value);
			}
		}
		//if (isset($_FILES["file"])){
		if (!empty($_FILES["file"]["name"])){
			$newfilename=System::getRandomFileName($_FILES["file"]["name"]);
			$this->currentphoto->file=$newfilename;
			move_uploaded_file($_FILES["file"]["tmp_name"], "files/".$this->currentphoto->file);
			//Image::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/t".$this->currentphoto->file, 100);
			//Image::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/s".$this->currentphoto->file, 300);
			//unlink("files/".$this->currentphoto->file);
			
		}
		Logger::setLogs("current photo after=".$this->currentphoto);
		if($tmpraionid!=$this->currentphoto->raion_id){
			$r=new Raion();
			$r->loadById($this->currentphoto->raion_id);
			$this->currentphoto->setRaion($r);
			$this->currentphoto->localitate_id=Location::getTopFirstLocationByRaionId($this->currentphoto->raion_id)->id;
		}		
		//set photo to session
		User::setCurrentPhoto($this->currentphoto);
		//Logger::setLogs("current photo after=".$this->currentphoto);

		//Cancel the wizard
		if ($this->action=="cancel"){
			User::delCurrentPhoto();
			$this->redirect("index.php");
		}
		
		if ($this->step==1){
			$this->step_title="Adauga Foto";
			if ($this->action=="next"){
				$this->redirect($this->getBaseName()."?step=".($this->step+1));
			}		
			$this->setFile();			
		}
		if ($this->step==2){
			$this->step_title="Adauga Adresa";
			if ($this->action=="next"){
				$this->redirect($this->getBaseName()."?step=".($this->step+1));
			}
			if ($this->action=="back"){
				$this->redirect($this->getBaseName()."?step=".($this->step-1));
			}				
			$this->setAdress();
		}		
		if ($this->step==3){
			//$this->setBodyTag('<body onload="WizardOnMapLoad()" onunload="GUnload()">');
			//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));
			$this->step_title="Adauga Pozitia";
			if ($this->action=="next"){
				$this->redirect($this->getBaseName()."?step=".($this->step+1));
			}
			if ($this->action=="back"){
				$this->redirect($this->getBaseName()."?step=".($this->step-1));
			}				
			$this->setMap();
		}
		if ($this->step==4){
			$this->step_title="Vaideaza!";
			if ($this->action=="next"){
				if (User::getValidationCode()==$this->validationcode){
					$this->currentphoto->save();
					if (!empty($this->currentphoto->file)){
						Photo::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/t".$this->currentphoto->file, 100);
						Photo::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/s".$this->currentphoto->file, 300);
					}
					User::delCurrentPhoto();
					$this->redirect("index.php?id=".$this->currentphoto->id);
				}
			}
			if ($this->action=="back"){
				$this->redirect($this->getBaseName()."?step=".($this->step-1));
			}				
			$this->setValidation();
		}				
	}
	function show($html=""){
		$out='<div id="container">';
		$out.=$html;
		$out.='</div>';
		MainWebPage::show($out);
	}
	function setWizardPage($html){
		$out=' <div id="form_container" class="form_container">';
		$out.=' <div id="form" class="form">';
		$out.='  <form id="frmImage" name="frmImage" method="POST" action="'.$this->getBaseName().'?step='.$this->step.'" enctype="multipart/form-data">';
		$out.='  <input id="action" name="action" type="hidden">';
		$out.='  <div id="form_header" class="form_header">';
		$out.='   <div class="form_header_title">'.$this->step_title.'</div>';
		$out.='   <div class="form_header_steps">Pasul '.$this->step.' din '.$this->steps.'</div>';
		$out.='   <div style="clear: both;"></div>';
		$out.='  </div>';
		$out.='  <div id="formcontrols">';
		$out.=$html;
		$out.='  </div>';
		$out.=' <div class="form_footer">';
		$out.='  <div class="form_footer_cancel">';
		$out.='   <input name="cancel" type="button" class="button" value="Anuleaza" onclick="javascript:WizardNavButtonOnClick(\'cancel\');">';
		$out.='  </div>';
		$out.='  <div class="form_footer_backnext">';
		if ($this->step!=1){
			$out.='  <input name="back" type="button" class="button" value="<< Inapoi" onclick="javascript:WizardNavButtonOnClick(\'back\');">';
		}
		if ($this->step==4){
			$out.='   <input name="next" type="button" class="button" value="Publica" onclick="javascript:WizardNavButtonOnClick(\'next\');">';
		} else {
			$out.='   <input name="next" type="button" class="button" value="Mai depaparte >>" onclick="javascript:WizardNavButtonOnClick(\'next\');">';
		}
		$out.='  </div>';
		$out.='  <div style="clear: both;"></div>';
		$out.=' </div>';
		$out.='</form>';
		$out.='</div>';
		$out.='</div>';
		return $out;
	}

	function setAdress(){
		$out='';
		
		$out.='<fieldset>';
		$out.='<legend>Adresa:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentphoto->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentphoto->raion_id,$this->currentphoto->localitate_id).'</td>';
		$out.='</tr>';
		$out.='</table>';	
		$out.='</fieldset>'; 

		$this->show($this->setWizardPage($out));
	}
	function getAdress(){
		$out='';
		$out.='<fieldset id="fieldset-address-view">';
		$out.='<legend>Adresa:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td<td class="property-name" style="width: 25%;">Nr. Casa:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->casa_nr.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td<td class="property-name" style="width: 25%;">Nr. Scara:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->scara_nr.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td<td class="property-name" style="width: 25%;">Nr. Apartament:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->apartament_nr.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Strada:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->strada.'</td<td></td><td></td></tr>';	
		$out.='</table>'; 
		$out.='</fieldset>'; 		
		
		$out.='<fieldset id="fieldset-address-notes-view">';
		$out.='<legend>Note la Adresa:</legend>';
		$out.='<table class="property-table" align="center">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->noteadresa.'</td></tr>';
		$out.='</table>';		
		$out.='</fieldset>'; 		
		
		return $out;
	}
	function setMap(){
		$out='';
		$out.='<div class="form_row">';
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->currentphoto->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->currentphoto->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->currentphoto->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->currentphoto->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->currentphoto->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->currentphoto->lng.'"/>';
		$out.='<div id="map"></div>';
		$out.='</div>';
		$this->show($this->setWizardPage($out));
	}
	function setFile(){
		$out='';
		$out.='<fieldset id="fieldset-files">';
		$out.='<legend>Imagini:</legend>';
		$out.='<table id="files">';
		$out.='<tr><td>Imaginea:</td><td><input type="file" id="file" name="file" ></td><td>Atentie! Doar fisiere (jpg,jpeg,gif,png) pina la 1MB altfel fisierele vor fi filtrare.</td></tr>';
		$out.='<tr><td>Titlu:</td><td><input type="input" id="title" name="title" value="'.$this->currentphoto->title.'"></td><td>Titlul imaginei</td></tr>';
		$out.='<tr><td>Data:</td><td><input type="input" id="data" name="data" value="'.$this->currentphoto->data.'"></td><td>Data cind imaginea a fost creata</td></tr>';		
		$out.='<tr><td>Nota:</td><td><textarea id="note" name="note" >'.$this->currentphoto->note.'</textarea></td><td>Mai mult despre imagine</td></tr>';
		$out.='</table>';			 
		$out.='</fieldset>';
		$this->show($this->setWizardPage($out));	 
	}
	function setValidation(){
		$out='';
		$out.='<fieldset id="fieldset-files">';
		$out.='<legend>Valideaza imaginea:</legend>';
		$out.='<table id="files">';
		$out.='<tr><td>Introdu codul din imagine:</td><td><input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"></td></tr>';
		$out.='</table>';			 
		$out.='</fieldset>';
		$this->show($this->setWizardPage($out));	 
	}	

	function getRaionDropDown($raionid){
		$r=new Raion();
		$rs=$r->getAll("","`municipiu` desc,`order`,`name`");
		$out="<select id=\"raion_id\" name=\"raion_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnDropDownChange()\">";
		if (!is_null($rs)){
			foreach($rs as $rr){
				$name=($rr->municipiu==1)?"m. ".$rr->name:"r. ".$rr->name;
				if ($rr->id==$raionid){
					$out.= "<option value=".$rr->id." selected>".$name."</option>";
				} else {
					$out.= "<option value=".$rr->id.">".$name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}	
	function getLocationDropDown($raionid,$localitateid){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$raionid,"`order`,`oras` desc,`name`");
		$out="<select id=\"localitate_id\" name=\"localitate_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnDropDownChange()\">";
		if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
			$out.= "<option value=\"0\">Nu Conteaza</option>";
		}
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->id==$localitateid){
					$out.= "<option value=".$ll->id." selected>".$ll->tip." ".$ll->name."</option>";
				} else {
					$out.= "<option value=".$ll->id.">".$ll->tip." ".$ll->name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}	
	function getSectorDropDown($localitateid,$sectorid){
		$s=new Sector();
		$ss=$s->getAll("localitate_id=".$localitateid,"`name`");
		$out="<select id=\"sector_id\" name=\"sector_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnSectorDropDownChange()\">";
		if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
			if ($sectorid==0){
				$out.= "<option value=\"0\" selected>Nu Conteaza</option>";
			} else {
				$out.= "<option value=\"0\">Nu Conteaza</option>";
			}
		}		
		if (!is_null($ss)){
			foreach($ss as $s){
				if ($s->id==$sectorid){
					$out.= "<option value=".$s->id." selected>".$s->name."</option>";
				} else {
					$out.= "<option value=".$s->id.">".$s->name."</option>";
				}
			}
		
			$out.= "<option value=\"0\" disabled=\"disabled\">---</option>";
			$out.= "<option value=\"-1\">Altul...</option>";
			if (($this->currentproperty->scop_id==1)||($this->currentproperty->scop_id==2)){
				if ($sectorid==0){
					$out.= "<option value=\"0\" selected>Nu Exista</option>";
				} else {
					$out.= "<option value=\"0\">Nu Exista</option>";
				}
			}
		}
		$out.="</select>";
		$out.="<input type=\"hidden\" id=\"sector_new\" name=\"sector_new\">";
		return $out;
	}
}
$n=new AddImageWebPage();
?>
