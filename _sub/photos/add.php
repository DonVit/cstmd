<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class AddImageWebPage extends MainWebPage {
	public $step=1;
	public $steps=4;
	public $steptitle="";
	public $currentphoto;	
	public $errormessage="";
	
	function __construct(){
		parent::__construct();
		$this->setJavascript("js/scripts.js");
		//$this->setCSS("style/styles.css");
		$this->setTitle('Adauga Foto');
		$this->setLogoTitle('Adauga Foto');
		if (isset($this->id)){
			$p=new Photo();
			$p->loadById($this->id);
			User::setCurrentPhoto($p);
		}		
		$this->currentphoto=User::getCurrentPhoto();
		foreach ($_POST as $field => $value) {
			if ($this->currentphoto->isMember($field)){
				//to change ceterlat and centerlng when location changed
				if (($field=='raion_id')&&($this->currentphoto->raion_id!=$value)){
					$r=new Raion();
					$r->loadById($value);
					$this->currentphoto->setRaion($r);					
				}
				$this->currentphoto->$field=$this->getParamValue($value);
			}
		}
		if (!empty($_FILES["file"]["name"])){
			$newfilename=System::getRandomFileName($_FILES["file"]["name"]);
			$this->currentphoto->file=$newfilename;
			move_uploaded_file($_FILES["file"]["tmp_name"], "files/".$this->currentphoto->file);
			//Image::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/t".$this->currentphoto->file, 100);
			//Image::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/s".$this->currentphoto->file, 300);
			Photo::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/t".$this->currentphoto->file, 100);
			Photo::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/s".$this->currentphoto->file, 300);

			//unlink("files/".$this->currentphoto->file)
			$e=exif_read_data('files/'.$this->currentphoto->file);
			//foreach ($e as $name=>$val) {
        	//		$eis.="$name: $val<br />\n";
			//}
			//$this->currentphoto->note=$this->currentphoto->note."<br>".$eis;
			$this->currentphoto->data = $e['DateTimeOriginal'];		
		}			
		User::setCurrentPhoto($this->currentphoto);
		//Logger::setLogs("current photo before=".$this->currentphoto);
		//echo "1-".User::getValidationCode();	
		$this->create();
		//echo "1-".User::getValidationCode();
	}
	function actionDefault(){
		$this->step=1;
		$this->steptitle="Adauga Foto - Indica Fisierul";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=adress"));
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		//$this->setCenterContainer($this->getGroupBoxH3("",$this->setFile(),""));
		$this->setCenterContainer($this->setFile());	
		$this->show();
	}
	function actionAdress(){
		$this->step=2;
		$this->steptitle="Adauga Foto - Indica Adresa";
		$this->setTitle($this->steptitle);		
		if ($_POST) {
			if (isset($_POST["next"])){
				//$r=new Raion();
				//$r->loadById($this->currentphoto->raion_id);
				//$this->currentphoto->setRaion($r);
				//User::setCurrentPhoto($this->currentphoto);
				$this->redirect($this->getUrl("add.php","action=map"));
			}
			if (isset($_POST["prev"])){
				//$r=new Raion();
				//$r->loadById($this->currentphoto->raion_id);
				//$this->currentphoto->setRaion($r);
				//User::setCurrentPhoto($this->currentphoto);
				$this->redirect($this->getUrl("add.php"));
			}				
			if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
			}
		}
		$this->setCenterContainer($this->setAdress());	
		$this->show();
	}
	function actionMap(){
		//$this->setBodyTag('<body onload="WizardOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));
		$this->step=3;
		$this->steptitle="Adauga Foto - Indica Pozitia pe Harta";
		$this->setTitle($this->steptitle);
		if ($_POST) {
			if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=validation"));
			}
			if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php","action=adress"));
			}
			if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
			}							
		}
		$this->setCenterContainer($this->getWizardPage($this->setMap($this->currentphoto)));	
		$this->show();		
	}	
	function actionValidation(){
		$this->step=4;
		$this->steptitle="Adauga Foto - Introdu codul din imagine";
		$this->setTitle($this->steptitle);
		if ($_POST) {
			if ($_POST["save"]){
				//echo $_POST["validationcode"]."-".User::getValidationCode();
				if (User::getValidationCode()==$_POST["validationcode"]){
					//echo "2";
					if (!isset($this->currentphoto->id)){
						$this->currentphoto->save();
						//if (!empty($this->currentphoto->file)){
						//	Photo::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/t".$this->currentphoto->file, 100);
						//	Photo::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/s".$this->currentphoto->file, 300);
						//}
					} else {
						$this->currentphoto->save();
						//if (!file_exists("files/t".$this->currentphoto->file)){
						//	Photo::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/t".$this->currentphoto->file, 100);
						//	Photo::makeIcons_MergeCenter("files/".$this->currentphoto->file, "files/s".$this->currentphoto->file, 300);
						//	User::delCurrentPhoto();
						//}
					}
					User::delCurrentPhoto();
					//$this->redirect("index.php?id=".$this->currentphoto->id);
					$this->redirect($this->getUrl("index.php","action=viewimage&id=".$this->currentphoto->id));
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
		if (!empty($this->currentphoto->file)){
				unlink("files/".$this->currentphoto->file);
				unlink("files/t".$this->currentphoto->file);
				unlink("files/s".$this->currentphoto->file);
		}
		User::delCurrentPhoto();
		
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
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	
	function setAdress($out=''){
		//$out.='<div id="form" class="font-size: 16px;">';
		//form start
		//$out.='<form id="frmImage" name="frmImage" method="POST" enctype="multipart/form-data">';
		//header
		//$out.=' <div id="form_header" style="padding:10px;border-bottom:2px solid #777;">';
		//$out.='  <div style="float:left">';	
		//$out.=$this->steptitle;
		//$out.='  </div>';
		//$out.='  <div style="float:right">';
		//$out.='  Pasul '.$this->step.' din '.$this->steps;		
		//$out.='  </div>';
		//$out.='  <div style="clear: both;"></div>';				
		//$out.=' </div>';
		//body
		//$out.=' <div id="formbody" style="padding:10px;">';
		$out.='<table style="height:100px;width:100%">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentphoto->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentphoto->raion_id,$this->currentphoto->localitate_id).'</td>';
		$out.='</tr>';
		$out.='</table>';
		//$out.='  </div>';
		//footer
		//$out.=' <div id="form_footer" style="padding:10px;border-top:2px solid #777;">';
		//$out.='  <div style="float:left">';		
		//$out.='   <input name="cancel" type="submit" class="button" value="Anuleaza">';
		//$out.='  </div>';
		//$out.='  <div style="float:right">';				
		//$out.='   <input name="prev" type="submit" class="button" value="<< Inapoi">';
		//$out.='   <input name="next" type="submit" class="button" value="Inainte >>">';
		//$out.='  </div>';
		//$out.='  <div style="clear: both;"></div>';						
		//$out.=' </div>';
		//$out.='</form>';
		//$out.='</div>';
		return $this->getWizardPage($out);
	}		
	function _setMap(){
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
	function setMap1(){
		//$out.='<div id="form" class="font-size: 16px;">';
		//$out.='<form id="frmImage" name="frmImage" method="POST" enctype="multipart/form-data">';
		//$out.=' <input id="action" name="action" type="hidden">';
		//header
		//$out.=' <div id="form_header" style="padding:10px;border-bottom:2px solid #777;">';
		//$out.='  <div style="float:left">';	
		//$out.=$this->steptitle;
		//$out.='  </div>';
		//$out.='  <div style="float:right">';
		//$out.='  Pasul '.$this->step.' din '.$this->steps;		
		//$out.='  </div>';
		//$out.='  <div style="clear: both;"></div>';				
		//$out.=' </div>';
		//body
		//$out.=' <div id="formcontrols" style="padding:10px;">';
		//$out.='<div class="form_row">';
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->currentphoto->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->currentphoto->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->currentphoto->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->currentphoto->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" value="'.$this->currentphoto->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng" value="'.$this->currentphoto->lng.'"/>';
		$out.='<div id="map"></div>';
		$out.='</div>';
		//$out.='  </div>';
		//footer
		//$out.=' <div id="form_footer" style="padding:10px;border-top:2px solid #777;">';
		//$out.='  <div style="float:left">';		
		//$out.='   <input name="cancel" type="submit" class="button" value="Anuleaza">';
		//$out.='  </div>';
		//$out.='  <div style="float:right">';				
		//$out.='   <input name="prev" type="submit" class="button" value="<< Inapoi">';
		//$out.='   <input name="next" type="submit" class="button" value="Inainte >>">';
		//$out.='  </div>';
		//$out.='  <div style="clear: both;"></div>';						
		//$out.=' </div>';
		//$out.='</form>';
		//$out.='</div>';
		return $this->getWizardPage($out);
	}			
	function setFile(){
		///$out.='<div id="form" class="font-size: 16px;">';
		///$out.='<form id="frmImage" name="frmImage" method="POST" enctype="multipart/form-data">';
		//$out.='<form action="http://travel-maps.commondatastorage.googleapis.com" method="post" enctype="multipart/form-data">';		
		//$out.=' <input id="action" name="action" type="hidden">';
		//header
		///$out.=' <div id="form_header" style="padding:10px;border-bottom:2px solid #777;">';
		///$out.='  <div style="float:left">';	
		///$out.=$this->steptitle;
		///$out.='  </div>';
		///$out.='  <div style="float:right">';
		///$out.='  Pasul '.$this->step.' din '.$this->steps;		
		///$out.='  </div>';
		///$out.='  <div style="clear: both;"></div>';				
		///$out.=' </div>';
		//body
		//$out.=' <div id="formcontrols" style="padding:10px;">';
		$out=' <table style="height:100px;width:100%">';
		if (isset($this->currentphoto->file)){
			if (isset($this->currentphoto->id)){
				$out.='<tr><td>Imaginea:</td><td><img style="width:150px;" src="files/t'.$this->currentphoto->file.'"></td><td></td></tr>';
			}else{
				$out.='<tr><td>Imaginea:</td><td><img style="width:150px;" src="files/'.$this->currentphoto->file.'"></td><td></td></tr>';
			}		
		}
		$out.='<tr><td>Imaginea:</td><td><input type="file" id="file" name="file" style="width:400px;"></td><td>Atentie! Doar fisiere (jpg,jpeg,gif,png) pina la 1MB altfel fisierele vor fi filtrare.</td></tr>';
		$out.='<tr><td>Titlu:</td><td><input type="input" id="title" name="title" style="width:400px;" value="'.$this->currentphoto->title.'"></td><td>Titlul imaginei</td></tr>';
		$out.='<tr><td>Data:</td><td><input type="input" id="data" name="data" value="'.$this->currentphoto->data.'"></td><td>Data cind imaginea a fost creata</td></tr>';		
		$out.='<tr><td>Nota:</td><td><textarea id="note" name="note" style="width:400px;height:60px;">'.$this->currentphoto->note.'</textarea></td><td>Mai mult despre imagine</td></tr>';	
		$out.='</table>';	
		//$out.='  </div>';
		//footer
		///$out.=' <div id="form_footer" style="padding:10px;border-top:2px solid #777;">';
		///$out.='  <div style="float:left">';		
		///$out.='   <input name="cancel" type="submit" class="button" value="Anuleaza">';
		///$out.='  </div>';
		///$out.='  <div style="float:right">';				
		///$out.='   <input name="next" type="submit" class="button" value="Mai depaparte >>">';
		///$out.='  </div>';
		///$out.='  <div style="clear: both;"></div>';						
		///$out.=' </div>';
		///$out.='</form>';
		///$out.='</div>';
		return $this->getWizardPage($out);
	}	
	function _setFile1(){
		$out='';
		//$out.='<fieldset id="fieldset-files">';
		//$out.='<legend>Imagini:</legend>';
		$out.='<table id="files" border="1" width="100%">';
		if (isset($this->currentphoto->file)){
			if (isset($this->currentphoto->id)){
				$out.='<tr><td>Imaginea:</td><td><img style="width:150px;" src="files/t'.$this->currentphoto->file.'"></td><td></td></tr>';
			}else{
				$out.='<tr><td>Imaginea:</td><td><img style="width:150px;" src="files/'.$this->currentphoto->file.'"></td><td></td></tr>';
			}		
		}
		$out.='<tr><td>Imaginea:</td><td><input type="file" id="file" name="file" ></td><td>Atentie! Doar fisiere (jpg,jpeg,gif,png) pina la 1MB altfel fisierele vor fi filtrare.</td></tr>';
		$out.='<tr><td>Titlu:</td><td><input type="input" id="title" name="title" value="'.$this->currentphoto->title.'"></td><td>Titlul imaginei</td></tr>';
		$out.='<tr><td>Data:</td><td><input type="input" id="data" name="data" value="'.$this->currentphoto->data.'"></td><td>Data cind imaginea a fost creata</td></tr>';		
		$out.='<tr><td>Nota:</td><td><textarea id="note" name="note" >'.$this->currentphoto->note.'</textarea></td><td>Mai mult despre imagine</td></tr>';
		$out.='</table>';			 
		//$out.='</fieldset>';
		return $out;
		//$this->show($this->setWizardPage($out));	 
	}
	function setValidation(){
		//$out='<div id="form" class="font-size: 16px;">';
		//$out.='<form id="frmImage" name="frmImage" method="POST" enctype="multipart/form-data">';
		//header start
		//$out.=' <div id="form_header" style="padding:10px;border-bottom:2px solid #777;">';
		//$out.='  <div style="float:left">';	
		//$out.=$this->steptitle;
		//$out.='  </div>';
		//$out.='  <div style="float:right">';
		//$out.='  Pasul '.$this->step.' din '.$this->steps;		
		//$out.='  </div>';
		///$out.='  <div style="clear: both;"></div>';				
		//$out.=' </div>';
		//header end
		//body start
		//$out.=' <div id="formcontrols" style="padding:10px;">';
		$out=' <table style="height:100px;width:100%">';
		$out.=' <tr><td>Introdu codul din imagine:</td><td><input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"></td></tr>';	
		$out.=' </table>';	
		//$out.=' </div>';
		//body end
		//footer start
		//$out.=' <div id="form_footer" style="padding:10px;border-top:2px solid #777;">';
		//$out.='  <div style="float:left">';		
		//$out.='   <input name="cancel" type="submit" class="button" value="Anuleaza">';
		//$out.='  </div>';
		//$out.='  <div style="float:right">';				
		//$out.='   <input name="prev" type="submit" class="button" value="<< Inapoi">';
		//$out.='   <input name="save" type="submit" class="button" value="Salveaza >>">';
		//$out.='  </div>';
		//$out.='  <div style="clear: both;"></div>';						
		//$out.=' </div>';
		//footer end
		//$out.='</form>';
		//$out.='</div>';
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
$n=new AddImageWebPage();
?>
