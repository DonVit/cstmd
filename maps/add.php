<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class AddMapWebPage extends MainWebPage {
	public $step=1;
	public $steps=3;
	public $steptitle="";
	public $currentmap;	
	public $errormessage="";
	
	function __construct(){
		parent::__construct();
		//$this->setJavascript("js/scripts.js");
		//$this->setCSS("style/styles.css");

		//check if user is login
		//if (!User::isAuthenticated()){
		//	$this->redirect($this->getUrl(Config::$accountssite."/index.php"));
		//}			
		
		$this->setTitle('Adauga Punct pe Harta');
		$this->setLogoTitle('Adauga Punct pe Harta');
		if (isset($this->id)){
			$m=new Map();
			$m->loadById($this->id);
			User::setCurrentMap($m);
		}		
		$this->currentmap=User::getCurrentMap();
		foreach ($_POST as $field => $value) {
			if ($this->currentmap->isMember($field)){
				//to change ceterlat and centerlng when location changed
				if (($field=='raion_id')&&($this->currentmap->raion_id!=$value)){
					$r=new Raion();
					$r->loadById($value);
					$this->currentmap->setRaion($r);					
				}
				$this->currentmap->$field=$this->getParamValue($value);
			}
		}		
		User::setCurrentMap($this->currentmap);
		//Logger::setLogs("current photo before=".$this->currentmap);
		//echo "1-".User::getValidationCode();	
		$this->create();
		//echo "1-".User::getValidationCode();
	}

	function actionDefault(){
		$this->step=1;
		$this->steptitle="Adauga Punct pe Harta - Titlul, Descriere ";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=map"));
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}
		$this->setCenterContainer($this->setDetails());	
		$this->show();
	}
	function actionMap(){
		//$this->setBodyTag('<body onload="WizardOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));
		$this->step=2;
		$this->steptitle="Adauga Punct pe Harta - Indica Pozitia pe Harta";
		$this->setTitle($this->steptitle);
		if ($_POST) {
			if ($_POST["next"]){
				$this->redirect($this->getUrl("add.php","action=validation"));
			}
			if ($_POST["prev"]){
				$this->redirect($this->getUrl("add.php"));
			}
			if ($_POST["cancel"]){
				$this->redirect($this->getUrl("add.php","action=cancel"));
			}							
		}
		$this->setCenterContainer($this->getWizardPage($this->setMap($this->currentmap)));	
		$this->show();		
	}	
	function actionValidation(){
		$this->step=3;
		$this->steptitle="Adauga Punct pe Harta - Introdu codul din imagine";
		$this->setTitle($this->steptitle);
		if ($_POST) {
			if ($_POST["save"]){
				//echo $_POST["validationcode"]."-".User::getValidationCode();
				if (User::getValidationCode()==$_POST["validationcode"]){
					$this->currentmap->save();
					User::delCurrentMap();
					$this->redirect($this->getUrl("index.php","action=viewmap&id=".$this->currentmap->id));
				}
			}
			if ($_POST["prev"]){
				$this->redirect($this->getUrl("add.php","action=map"));
			}
			if ($_POST["cancel"]){
				$this->redirect($this->getUrl("add.php","action=cancel"));
			}							
		}
		$this->setCenterContainer($this->setValidation());	
		$this->show();			
	}
	function actionCancel(){
		User::delCurrentMap();
		$this->redirect($this->getUrl("index.php"));
	}		
	function show($out=''){
		$out="";
		$out.='<div id="container">';
		//$out.='<div id="left" class="container left" style="width:98px;">';
		//$out.=$this->getLeftContainer();
		//$out.='</div>';		
		$out.='<div id="center" class="container center" style="width:1000px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		//$out.='<div id="right" class="container right" style="width:98px;">';
		//$out.=$this->getRightContainer();
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}

	function setDetails(){

		$out=' <table style="height:100px;width:100%">';
		$out.='<tr><td>Titlu:</td><td><input type="input" id="title" name="title" style="width:600px;" value="'.$this->currentmap->title.'"></td></tr>';		
		$out.='<tr><td>Descriere:</td><td><textarea id="description" name="description" style="width:600px;height:100px;">'.$this->currentmap->description.'</textarea></td></tr>';	
		$out.='</table>';	

		return $this->getWizardPage($out);
	}
	function setMap1(){

		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->currentmap->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->currentmap->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->currentmap->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->currentmap->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" value="'.$this->currentmap->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng" value="'.$this->currentmap->lng.'"/>';
		$out.='<div id="map"></div>';

		return $this->getWizardPage($out);
	}			
	
	function setValidation(){

		$out=' <table style="height:100px;width:100%">';
		$out.=' <tr><td>Introdu codul din imagine pentru validare:</td><td><input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"></td></tr>';	
		$out.=' </table>';	

		return $this->getWizardPage($out);
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
		//if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
		//	$out.= "<option value=\"0\">Nu Conteaza</option>";
		//}
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
$n=new AddMapWebPage();
?>
