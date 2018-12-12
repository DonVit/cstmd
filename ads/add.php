<?php
require_once(__DIR__ . '/../main/loader.php');
 
class AddCompanyWebPage extends MainWebPage {
	public $step=1;
	public $steps=6;
	public $step_title="";
	public $currentad;	

	public $errormessage="";

	function __construct(){
		parent::__construct();

		//check if user is login
		if (!User::isAuthenticated()){
			$this->redirect($this->getUrl(Config::$accountssite."/index.php"));
		}	
		
		$t="Adauga Ad";
		$this->setTitle($t);
		$this->setLogoTitle($t);

		//get company from session
		$this->currentcompany=User::getCurrentCompany();
		$this->currentfiles=User::getCurrentFiles();

		//store company properties temporarily
		$tmpraionid=$this->currentcompany->raion_id;

		//Logger::setLogs("current company before=".$this->currentcompany);
		foreach ($_POST as $field => $value) {
			if ($this->currentcompany->isMember($field)){
				$this->currentcompany->$field=$this->getParamValue($value);
			}
		}

		//Logger::setLogs("current photo after=".$this->currentcompany);
		if($tmpraionid!=$this->currentcompany->raion_id){
			$r=new Raion();
			$r->loadById($this->currentcompany->raion_id);
			$this->currentcompany->setRaion($r);
			$this->currentcompany->localitate_id=Location::getTopFirstLocationByRaionId($this->currentcompany->raion_id)->id;
		}		
		//set photo to session
		User::setCurrentCompany($this->currentcompany);
		//Logger::setLogs("current photo after=".$this->currentcompany);

		$this->create();
	}
	function _show1($html=""){
		$out='<div id="container">';
		$out.=$html;
		$out.='</div>';
		MainWebPage::show($out);
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
	function actionDefault(){
		$this->step=1;
		$this->steptitle="Adauga Companie - Detalii";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=image"));
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->setCompany());	
		$this->show();
	}
	function actionImage(){
		$this->step=2;
		$this->steptitle="Adauga Companie - Imagine/Logo";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=adress"));
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php"));
		}		
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}
		$this->setCenterContainer($this->getWizardPage($this->setFiles()));	
		$this->show();
	}				
	function actionAdress(){
		$this->step=3;
		$this->steptitle="Adauga Companie - Adresa";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=map"));
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php","action=image"));
		}		
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->getWizardPage($this->setAdress($this->currentcompany)));	
		$this->show();
	}
	function actionMap(){
		$this->step=4;
		$this->steptitle="Adauga Companie - Harta";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=contacts"));
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php","action=adress"));
		}		
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->getWizardPage($this->setMap($this->currentcompany)));	
		$this->show();
	}
	function actionContacts(){
		$this->step=5;
		$this->steptitle="Adauga Companie - Contacte";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=validation"));
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php","action=map"));
		}		
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}
		$this->setCenterContainer($this->getWizardPage($this->setContacts($this->currentcompany)));
		$this->show();
	}
	function actionValidation(){
		$this->step=6;
		$this->steptitle="Adauga Companie - Valideaza";
		$this->setTitle($this->steptitle);
		if (isset($_POST["save"])){
				if (User::getValidationCode()==$this->validationcode){
					$this->currentcompany->save();
					$fs=User::getCurrentFiles();
					foreach ($fs as $f){
						$f->reftype='f';
						$f->reftypeid=$this->currentcompany->id;
						if ($f->imagename!=''){
							if (($f->id==NULL)&&($f->error==0)){
								Logger::setLogs("id=".$f->id);
								$f->save();
							}
						}
					}
					//delete property from cache
					User::delCurrentFiles();					
					User::delCurrentCompany();
					$this->redirect($this->getUrl("index.php","action=viewcompany&id=".$this->currentcompany->id));
				}				
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php","action=contacts"));
		}		
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->setValidation());	
		$this->show();
	}	
	function actionCancel(){
		if (!empty($this->currentcompany->logo_file)){
				//unlink("files/".$this->currentphoto->file);
				//unlink("files/t".$this->currentphoto->file);
				unlink("files/".$this->currentcompany->logo_file);
		}
		User::delCurrentFiles();
		User::delCurrentCompany();
		
		$this->redirect($this->getUrl("index.php"));
	}										
	function actionDelete(){

		$this->steptitle="Sterge Compania!";
		$this->setTitle($this->steptitle);
		if (isset($_POST["da"])){
			$this->currentcompany->delete();
			$this->redirect($this->getUrl("index.php"));
		}
		if (isset($_POST["nu"])){
				$this->redirect($this->getUrl("index.php"));
		}			
		$this->setCenterContainer($this->setDeleteAnunt());	
		$this->show();
	}
	function setDeleteAnunt(){
		$out='';
		$out.='<table><tr>';
		$out.='<td><h2>Doresti sa stergi compania '.$this->currentcompany->name.' ?</h2></td>';    		
		$out.='</tr></table>';	
		return $this->getQuestionPage($out);	 
	}	
	function setCompany(){
		$out='';
		$out.='<table id="files">';
		$out.='<tr><td>Tip Companie:</td><td>'.CompanyType::getCompanyTypeDropDown($this->currentcompany->company_type_id).'</td></tr>';	
		$out.='<tr><td>Nume Companie:</td><td><input type="input" id="name" name="name" value="'.$this->currentcompany->name.'"  style="width:460px;"></td></tr>';			
		$out.='<tr><td>Descriere scurta:</td><td><textarea id="description" name="description" style="width:460px;height:40px;">'.$this->currentcompany->description.'</textarea></td></tr>';
		$out.='<tr><td>Descriere:</td><td><textarea id="text" name="text" style="width:460px;height:150px;">'.$this->currentcompany->text.'</textarea></td></tr>';		
		$out.='<tr><td>Cuvinte chei:</td><td><textarea id="keywords" name="keywords"  style="width:460px;height:40px;">'.$this->currentcompany->keywords.'</textarea></td></tr>';				
		$out.='</table>';
		return $this->getWizardPage($out);
	}
	function setImage(){
		$out='';
		$out.='<table>';
		if (isset($this->currentcompany->logo_file)){
			$out.='<tr><td>Imaginea:</td><td><img style="width:150px;" src="files/t'.$this->currentcompany->logo_file.'"></td><td></td></tr>';
		}
		$out.='<tr><td>Imagine/Logo:</td><td><input type="file" id="logo_file" name="logo_file" style="width:460px;"></td></tr>';
		$out.='<tr><td>Descriere:</td><td><textarea id="logo_description" name="logo_description" style="width:460px;height:40px;">'.$this->currentcompany->logo_description.'</textarea></td></tr>';
		$out.='</table>';
		return $this->getWizardPage($out);
	}	
	function setAdress1(){
		$out='';
		$out.='<table>';
		$out.='<tr>';
		$out.='<td>Municipiul/Raionul:</td>';
		$out.='<td>'.Raion::getRaionDropDown($this->currentcompany->raion_id,"width:460px;").'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td>Oras/Sat:</td>';
		$out.='<td>'.Location::getLocationDropDown($this->currentcompany->raion_id,$this->currentcompany->localitate_id,"width:460px;").'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td>Sector/Regiune:</td>';
		$out.='<td>'.Sector::getSectorDropDown($this->currentcompany->localitate_id,$this->currentcompany->sector_id,"width:460px;").'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td>Strada:</td>';
		$out.='<td><textarea id="address" name="address" style="width:460px;height:40px;">'.$this->currentcompany->address.'</textarea></td>';
		$out.='</tr>';			
		$out.='</table>';	

		return $this->getWizardPage($out);	
	}
	function setMap1(){
		$out='';
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->currentcompany->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->currentcompany->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->currentcompany->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->currentcompany->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->currentcompany->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->currentcompany->lng.'"/>';
		$out.='<div id="map"></div>';
		return $this->getWizardPage($out);
	}

	function setContact1(){
		$out='';
		$out.='<table>';
		$out.='<tr><td>Telefon 1:</td><td><input id="phone1" type="text" name="phone1" style="width:200px;" value="'.$this->currentcompany->phone1.'"></td></tr>';
		$out.='<tr><td>Telefon 2:</td><td><input id="phone2" type="text" name="phone2" style="width:200px;" value="'.$this->currentcompany->phone2.'"></td></tr>';
		$out.='<tr><td>Mobile:</td><td><input id="mobile" type="text" name="mobile" style="width:200px;" value="'.$this->currentcompany->mobile.'"></td></tr>';
		$out.='<tr><td>Fax:</td><td><input id="fax" type="text" name="fax" style="width:200px;" value="'.$this->currentcompany->fax.'"></td></tr>';
		$out.='<tr><td>WebSite:</td><td><input id="website" type="text" name="website" style="width:200px;" value="'.$this->currentcompany->website.'"></td></tr>';
		$out.='<tr><td>Email:</td><td><input id="email" type="text" name="email" style="width:200px;" value="'.$this->currentcompany->email.'"></td></tr>';												
		$out.='</table>';
		return $this->getWizardPage($out);
	}
	function setValidation(){
		$out=' <table>';
		$out.=' <tr><td>Introdu codul din imagine:</td><td><input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"></td></tr>';	
		$out.=' </table>';
		return $this->getWizardPage($out);
	}	
}
$n=new AddCompanyWebPage();
?>
