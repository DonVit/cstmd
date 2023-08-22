<?php
require_once(__DIR__ . '/../main/loader.php');
 
class AddImageWebPage extends MainWebPage {
	public $step=1;
	public $steps=4;
	public $steptitle="";
	public $currentphoto;	
	public $errormessage="";
	
	function __construct(){
		parent::__construct();
		
		//check if user is login
		// if (!User::isAuthenticated()){
		// 	$this->redirect($this->getUrl(Config::$accountssite."/index.php"));
		// }

		$this->setJavascript("js/scripts.js");
		
		$this->setTitle('Adauga Foto');
		$this->setLogoTitle('Adauga Foto');
		if (isset($this->id)){
			$p=new Photo();
			$p->loadById($this->id);
			
			if (User::getCurrentUser()->id==0){
					$this->redirect($this->getUrl('index.php','id='.$this->id));
			}

			if (($p->user_id!=User::getCurrentUser()->id)){
				if (User::getCurrentUser()->role_id!=2){
					$this->redirect($this->getUrl('index.php','id='.$this->id));
				}
			}
			
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
			$exifinfo=exif_read_data('files/'.$this->currentphoto->file, 0, true);
			//$this->currentphoto->exif=$this->getExifInfo($exifinfo);
			$this->currentphoto->data = Photo::get_datetimeoriginal($exifinfo);
			$latlng = Photo::get_location($exifinfo);
			$this->currentphoto->centerlat = $latlng['latitude'];
			$this->currentphoto->centerlng = $latlng['longitude'];
			$this->currentphoto->lat = $latlng['latitude'];
			$this->currentphoto->lng = $latlng['longitude'];
			print_r($this->currentphoto);
		}
		User::setCurrentPhoto($this->currentphoto);
		//Logger::setLogs("current photo before=".$this->currentphoto);
		$this->create();
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
		$this->setCenterContainer($this->setFile());
		$this->show();
	}
	function actionAdress(){
		$this->step=2;
		$this->steptitle="Adauga Foto - Indica Adresa";
		$this->setTitle($this->steptitle);		
		if ($_POST) {
			if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=map"));
			}
			if (isset($_POST["prev"])){
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
				if (User::getValidationCode()==$_POST["validationcode"]){
					if (!isset($this->currentphoto->id)){
						$this->currentphoto->save();
					} else {
						$this->currentphoto->save();
					}
					User::delCurrentPhoto();
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
		if (!isset($this->id)){
			if (!empty($this->currentphoto->file)){
					unlink("files/".$this->currentphoto->file);
					unlink("files/t".$this->currentphoto->file);
					unlink("files/s".$this->currentphoto->file);
			}
		}
		User::delCurrentPhoto();
		
		$this->redirect($this->getUrl("index.php"));
	}
	function actionDelete(){
		$this->step=1;
		$this->steps=1;
		$this->steptitle="Sterge Anunt";
		$this->setTitle($this->steptitle);
		if (isset($_POST)){
			if ($_POST["da"]){
				$this->currentphoto=User::getCurrentPhoto();
				$this->currentphoto->delete();
				User::delCurrentPhoto();
				$this->redirect($this->getUrl(Config::$accountssite."/index.php","action=Fotos"));
			}
			if ($_POST["nu"]){
				User::delCurrentPhoto();	
				$this->redirect($this->getUrl(Config::$accountssite."/index.php","action=Fotos"));
			}
		}		
		$this->setCenterContainer($this->setDeleteAnunt());	
		$this->show();
	}
	function show($out=''){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="center" class="container center" style="width:1000px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	
	function setAdress($out=''){
		$out.='<table style="height:100px;width:100%">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Tara:</td>';
		$out.='<td style="width: 70%;">'.Country::getCountryDropDown($this->currentphoto->country_id).'</td>';
		$out.='</tr>';
		$c=new Country();
		$c->loadById($this->currentphoto->country_id);
		$t=$c->ISO;
		if ($c->ISO=='MD') {
			$out.='<tr>';
			$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
			$out.='<td style="width: 70%;">'.Raion::getRaionDropDown($this->currentphoto->raion_id).'</td>';
			$out.='</tr>';
			$out.='<tr>';
			$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
			$out.='<td style="width: 70%;">'.Location::getLocationDropDown($this->currentphoto->raion_id,$this->currentphoto->localitate_id).'</td>';
			$out.='</tr>';
		}
		$out.='</table>';
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
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->currentphoto->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->currentphoto->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->currentphoto->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->currentphoto->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" value="'.$this->currentphoto->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng" value="'.$this->currentphoto->lng.'"/>';
		$out.='<div id="map"></div>';
		$out.='</div>';
		return $this->getWizardPage($out);
	}			
	function setFile(){
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
		return $this->getWizardPage($out);
	}	
	function _setFile1(){
		$out='';
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
		return $out;
	}
	function setValidation(){
		$out=' <table style="height:100px;width:100%">';
		$out.=' <tr><td>Introdu codul din imagine:</td><td><input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"></td></tr>';	
		$out.=' </table>';	
		return $this->getWizardPage($out);
	}	
	function setDeleteAnunt(){
		$out='';
		$out.='<table><tr>';
		$out.='<td><h2>Doresti sa stergi acest Anunt ?</h2></td>';    		
		$out.='</tr></table>';	
		return $this->getQuestionPage($out);	 
	}
}
$n=new AddImageWebPage();
?>
