<?php
require_once(__DIR__ . '/../main/loader.php');
 
class PropertyWebPage extends MainWebPage {
	public $step=1;
	public $steps=8;
	public $step_title="";
	public $currentproperty;
	public $currentcontact;
	public $currentfiles;
	//public $scopid=1;
	//public $tipimobilid=1;
	public $errormessage="";
	function __construct(){
		parent::__construct();
		$this->setCSS("styles/property.css");
		//$this->setJavascript("js/scripts.js");

		//check if user is login
		if (!User::isAuthenticated()){
			$this->redirect($this->getUrl(Config::$accountssite."/index.php"));
		}	

		$t="ANUNTURI CHIRII DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);

		$this->currentproperty=User::getCurrentProperty();
		$this->currentcontact=User::getCurrentContact();
		$this->currentfiles=User::getCurrentFiles();

		if ($this->currentproperty->scop_id==1){
			$this->currentproperty->scop_id=2;
		}

		if ((isset($this->id))){
			$p=new Property();
			$p->loadById($this->id);

			if (User::getCurrentUser()->id==0){
					$this->redirect($this->getUrl('property.php','id='.$this->id));
			}

			if (($p->user_id!=User::getCurrentUser()->id)){
				if (User::getCurrentUser()->role_id!=2){
					$this->redirect($this->getUrl('property.php','id='.$this->id));
				}
			}

			$c=new Contact();
			$c->loadById($p->contact_id);
			$i=new Image();
			$is=$i->getAll("reftypeid=".$p->id);

			$fs=User::getCurrentFiles();
			for ($i = 0; $i <= 4; $i++) {
				if (isset($is[$i])){
					$fs[$i]=$is[$i];
					$fs[$i]->error=0;
				}
			}
			User::setCurrentProperty($p);
			User::setCurrentContact($c);
			User::setCurrentFiles($fs);

			$this->currentproperty=User::getCurrentProperty();
			$this->currentcontact=User::getCurrentContact();
			$this->currentfiles=User::getCurrentFiles();
			
		}			

		//store properties temporarily
		$tmpraionid=$this->currentproperty->raion_id;
		$tmplocalitateid=$this->currentproperty->localitate_id;

		foreach ($_POST as $field => $value) {
			if ($this->currentproperty->isMember($field)){
				$this->currentproperty->$field=$this->getParamValue($value);
			}
			if ($this->currentcontact->isMember($field)){
				if ($field!="note"){
					$this->currentcontact->$field=$this->getParamValue($value);
				}
			}
			if ($field=="notecontact"){
				$this->currentcontact->note=$this->notecontact;
			}
		}
		if (User::getCurrentProperty()->raion_id!=$this->currentproperty->raion_id){
			$this->currentproperty->centerlat=$this->currentproperty->getRaion()->lat;
			$this->currentproperty->centerlng=$this->currentproperty->getRaion()->lng;
		}
		if (User::getCurrentProperty()->localitate_id!=$this->currentproperty->localitate_id){
			$this->currentproperty->centerlat=$this->currentproperty->getLocation()->lat;
			$this->currentproperty->centerlng=$this->currentproperty->getLocation()->lng;
			$this->currentproperty->zoom=14;
		}		
			
		//add new sector
		if (isset($this->sector_new)){
			$s=new Sector();
			$s->localitate_id=$this->localitate_id;
			$s->name=$this->sector_new;
			$s->order=999;	
			$s->save();
			$this->currentproperty->sector_id=$s->id;
			
		}
		
		///manage added files
//		if((is_array($_FILES))&&(sizeof($_FILES)!=0)){
//		  	 $filescounter=0;
//		  	 for ($i = 0; $i <= 4; $i++) {
//		  	 	if ($_POST[status][$i]!=1){
//		  	 		//delete previously add file if added
//		  	 		
//		  	 		if (isset($this->currentfiles[$i]->filepath)){
//		  	 				unlink(Config::$filespath."/".$this->currentfiles[$i]->filepath);
//		  	 				if ((isset($this->currentfiles[$i]->id))){
//		  	 					$this->currentfiles[$i]->delete();
//		  	 					unlink(Config::$filespath."/t".$this->currentfiles[$i]->filepath);
//		  	 				}
//		  	 				$f=new Image();
//		  	 				$this->currentfiles[$i]=$f;
//		  	 		}
//		  	 		
//		  	 		//add files
//		  	 		$this->currentfiles[$i]->filename=$_FILES["file"]["name"][$filescounter];
//		  	 		$this->currentfiles[$i]->name=$_FILES["file"]["name"][$filescounter];
//		  	 		$this->currentfiles[$i]->type=$_FILES["file"]["type"][$filescounter];
//		  	 		$this->currentfiles[$i]->size=$_FILES["file"]["size"][$filescounter];
//		  	 		$this->currentfiles[$i]->tmp_name=$_FILES["file"]["tmp_name"][$filescounter];
//		  	 		if ($_FILES["file"]["error"][$filescounter]!=0){
//		  	 			$this->currentfiles[$i]->error=$_FILES["file"]["error"][$filescounter];
//		  	 		}elseif (!array_key_exists($_FILES["file"]["type"][$filescounter], Config::$file_types)){
//		  	 			$this->currentfiles[$i]->error=8;
//		  	 		}elseif($_FILES["file"]["size"][$filescounter]>Config::$file_size){
//		  	 			$this->currentfiles[$i]->error=1;
//		  	 		}else {
//		  	 			$this->currentfiles[$i]->error=$_FILES["file"]["error"][$filescounter];
//		  	 			$this->currentfiles[$i]->new_name=System::getRandomFileName($this->currentfiles[$i]->name);
//		  	 			$this->currentfiles[$i]->filepath=$this->currentfiles[$i]->new_name;
//        				move_uploaded_file($this->currentfiles[$i]->tmp_name, Config::$filespath."/".$this->currentfiles[$i]->new_name);
//		  	 		}
//		  	 		$filescounter=$filescounter+1;
//		  	 	}
//		  	}
//		}
	
		if($tmpraionid!=$this->currentproperty->raion_id){
			$this->currentproperty->localitate_id=Location::getTopFirstLocationByRaionId($this->currentproperty->raion_id)->id;
			$this->currentproperty->sector_id=Sector::getTopFirstSectorByLocalitateId($this->currentproperty->localitate_id)->id;
		}
		if($tmplocalitateid!=$this->currentproperty->localitate_id){
			$this->currentproperty->sector_id=Sector::getTopFirstSectorByLocalitateId($this->currentproperty->localitate_id)->id;
		}
			
		//if (isset($this->scop_id)){
		//	if (($this->scop_id==1)||($this->scop_id==3)){
		//		$this->currentproperty->scop_id=$this->scop_id;
		//	}				
		//}		

		User::setCurrentProperty($this->currentproperty);
		User::setCurrentContact($this->currentcontact);
		User::setCurrentFiles($this->currentfiles);
			
		//Set the wizard steps
		if (($this->currentproperty->scop_id==1)||($this->currentproperty->scop_id==2)){
			$this->steps=8;	
		} else {
			$this->steps=4;	
		}

		$this->create();
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
		$this->steptitle="Adauga Anunt - Tipul Anuntului";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=details"));
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->setTipAnunt());	
		$this->show();
	}	
	function actionDetails(){
		$this->step=2;
		$this->steptitle="Adauga ".$this->currentproperty->getScop()->name." - Detalii";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				if ($this->currentproperty->scop_id==2){
					$this->redirect($this->getUrl("add.php","action=adress"));
				} else {
					$this->redirect($this->getUrl("add.php","action=contacts"));
				}
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php"));
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->setDetails());	
		$this->show();
	}
	function actionAdress(){
		$this->step=3;
		$this->steptitle="Adauga ".$this->currentproperty->getScop()->name." - Adresa";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=map"));
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php","action=details"));
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		//$this->setCenterContainer($this->setAdress());	
		$this->setCenterContainer($this->getWizardPage($this->setAdress($this->currentproperty)));
		$this->show();
	}			
	function actionMap(){
		//$this->setBodyTag('<body onload="WizardOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=3&amp;key=".Config::getMapKey($this->getServerName()));

		$this->step=4;
		$this->steptitle="Adauga ".$this->currentproperty->getScop()->name." - Pozitia pe Harta";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=files"));
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php","action=adress"));
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->getWizardPage($this->setMap($this->currentproperty)));	
		$this->show();
	}
	function actionFiles(){

		$this->step=5;
		$this->steptitle="Adauga ".$this->currentproperty->getScop()->name." - Imagini";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=video"));
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php","action=map"));
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		//$this->setCenterContainer($this->setFiles());
		$this->setCenterContainer($this->getWizardPage($this->setFiles($this->currentfiles)));
		$this->show();
	}
	function actionVideo(){
		$this->step=6;
		$this->steptitle="Adauga ".$this->currentproperty->getScop()->name." - Video de pe YouTube";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=contacts"));
		}
		if (isset($_POST["prev"])){
				$this->redirect($this->getUrl("add.php","action=files"));
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->setVideo());	
		$this->show();
	}	
	function actionContacts(){
		if ($this->currentproperty->scop_id==2){
			$this->step=7;
		} else {
			$this->step=3;
		}
		$this->steptitle="Adauga ".$this->currentproperty->getScop()->name." - Contacte";
		$this->setTitle($this->steptitle);
		if (isset($_POST["next"])){
				$this->redirect($this->getUrl("add.php","action=validation"));
		}
		if (isset($_POST["prev"])){
				if ($this->currentproperty->scop_id==2){
					$this->redirect($this->getUrl("add.php","action=video"));
				} else {
					$this->redirect($this->getUrl("add.php","action=details"));
				}
		}
		if (isset($_POST["cancel"])){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->getWizardPage($this->setContacts($this->currentcontact)));
		$this->show();
	}
	function actionValidation(){
		if ($this->currentproperty->scop_id==2){
			$this->step=8;
		} else {
			$this->step=4;
		}
		$this->steptitle="Adauga ".$this->currentproperty->getScop()->name." - Valideaza";
		$this->setTitle($this->steptitle);
		if (isset($_POST["save"])){
			if (User::getValidationCode()==$this->validationcode){
				//save contact
				$c=User::getCurrentContact();
				$c->save();
				//save property
				$p=User::getCurrentProperty();
				$p->contact_id=$c->id;
				$p->status=0;
				$p->save();
				//save images
				$fs=User::getCurrentFiles();
				foreach ($fs as $f){
					$f->reftype='c';
					$f->reftypeid=$p->id;
					if ($f->imagename!=''){
						if (($f->id==NULL)&&($f->error==0)){
							Logger::setLogs("id=".$f->id);
							$f->save();
						}
					}
				}
				//delete property from cache
				User::delCurrentProperty();
				User::delCurrentContact();
				User::delCurrentFiles();				
				//redirect to view saved property
				$this->redirect("property.php?id=".$p->id);
				
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
		User::delCurrentProperty();
		User::delCurrentContact();
		User::delCurrentFiles();
		$this->redirect($this->getUrl("index.php"));
	}
	function actionDelete(){
		$this->step=1;
		$this->steps=1;
		$this->steptitle="Sterge Anunt";
		$this->setTitle($this->steptitle);
		if (isset($_POST["da"])){
			$this->currentproperty=User::getCurrentProperty();
			$this->currentcontact=User::getCurrentContact();
			$this->currentfiles=User::getCurrentFiles();
			$this->currentproperty->delete();
			$this->currentcontact->delete();
			foreach ($this->currentfiles as $f){
				$f->delete();
			}
			$this->redirect($this->getUrl("index.php"));
		}
		if (isset($_POST["nu"])){
				$this->redirect($this->getUrl("index.php"));
		}			
		$this->setCenterContainer($this->setDeleteAnunt());	
		$this->show();
	}
	function actionRepublish(){
		$this->step=1;
		$this->steps=1;
		$this->steptitle="RePublica Anunt !";
		$this->setTitle($this->steptitle);
		if (isset($_POST["da"])){
			$this->currentproperty=User::getCurrentProperty();
			$this->currentproperty->data=System::getCurentDateTime();
			$this->currentproperty->save();
			$this->redirect("property.php?id=".$this->currentproperty->id);
		}
		if (isset($_POST["nu"])){
				$this->redirect($this->getUrl("index.php"));
		}			
		$this->setCenterContainer($this->setRepublishAnunt());	
		$this->show();
	}			
	function setDeleteAnunt(){
		$out='';
		$out.='<table><tr>';
		$out.='<td><h2>Doresti sa stergi acest Anunt ?</h2></td>';    		
		$out.='</tr></table>';	
		return $this->getQuestionPage($out);	 
	}
	function setRepublishAnunt(){
		$out='';
		$out.='<table><tr>';
		$out.='<td><h2>Doresti sa republici acest Anunt cu data de azi ?</h2></td>';    		
		$out.='</tr></table>';	
		return $this->getQuestionPage($out);	 
	}										
	function setTipAnunt(){
		$out='';
		//$out.='<fieldset id="fieldset-tipanunt">';
		//$out.='<legend>Tip Anunt, Tip Imobil:</legend>';		
		$out.='<table><tr>';
		$out.='<td>Tip Anunt:</td>';
		$out.='<td>'.$this->getScopDropDown($this->currentproperty->scop_id).'</td>';    
		$out.='</tr><tr>';
		$out.='<td>Tip Imobil:</td>';
		$out.='<td>'.$this->getTipImobilDropDown($this->currentproperty->scop_id,$this->currentproperty->tipimobil_id).'</td>';	
		//$out.='</tr><tr>';
		//$out.='<td>SubTip Imobil:</td>';
		//$out.='<td>'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';		
		$out.='</tr></table>';	
		//$out.='</fieldset>'; 
		return $this->getWizardPage($out);	 
	}
	function setDetails(){
		$out='';

		if ($this->currentproperty->tipimobil_id==21){				
			$out.=$this->setOfertaChirieApartament();	
		}
		if ($this->currentproperty->tipimobil_id==22){					
			$out.=$this->setOfertaChirieCasaVila();	
		}
		if ($this->currentproperty->tipimobil_id==24){						
			$out.=$this->setOfertaChirieComercial();	
		}

		if ($this->currentproperty->tipimobil_id==41){
			$out.=$this->setCerereChirieApartament();	
		}
		if ($this->currentproperty->tipimobil_id==42){
			$out.=$this->setCerereChirieCasaVila();	
		}
		if ($this->currentproperty->tipimobil_id==44){
			$out.=$this->setCerereChirieComercial();	
		}		
		return $this->getWizardPage($out);	 
	}

	function setOfertaChirieApartament(){
		$out='';
		//$out.='<fieldset>';
		//$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Tip Apartament:</td>';
		$out.='<td style="width: 25%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Anul constructiei:</td>';
		$out.='<td style="width: 25%;">'.$this->getAn($this->currentproperty->an).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Suprafata totala(m2):</td>';
		$out.='<td style="width: 25%;"><input type="text" id="aria_totala" name="aria_totala" value="'.$this->currentproperty->aria_totala.'"></td>';
		$out.='<td class="property-name" style="width: 25%;">Model:</td>';
		$out.='<td style="width: 25%;">'.$this->getModel($this->currentproperty->model_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Suprafata locuibila(m2):</td>';
		$out.='<td style="width: 25%;"><input type="text" id="aria_locuibila" name="aria_locuibila" class="input" value="'.$this->currentproperty->aria_locuibila.'"></td>';
		$out.='<td class="property-name" style="width: 25%;">Tip Constructie:</td>';
		$out.='<td style="width: 25%;">'.$this->getTipConstructie($this->currentproperty->tipconstructie_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Etaj/Etaje:</td>';
		$out.='<td style="width: 25%;">';
		$out.='<input type="text" id="etaj" name="etaj" class="input" value="'.$this->currentproperty->etaj.'">';		
		$out.='din';
		$out.='<input type="text" id="totaletaje" name="totaletaje" class="input" value="'.$this->currentproperty->totaletaje.'">';
		$out.='</td>';		
		$out.='<td class="property-name" style="width: 25%;">Stare:</td>';
		$out.='<td style="width: 25%;">'.$this->getStare($this->currentproperty->stare_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Balcoane:</td>';
		$out.='<td style="width: 25%;"><input type="text" id="balcoane" name="balcoane" value="'.$this->currentproperty->balcoane.'"></td>';
		$out.='<td class="property-name" style="width: 25%;">Garaj:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("garaj",$this->currentproperty->garaj).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 20%;">Pret:</td>';
		$out.='<td style="width: 30%;"><div>';
		$out.='<input id="pret" type="text" name="pret" value="'.$this->currentproperty->pret.'">';		
		$out.=$this->getValuta($this->currentproperty->valuta_id);
		$out.='/';
		$out.=$this->getMasura($this->currentproperty->tipimobil_id,$this->currentproperty->masura_id);
		$out.='</div></td>';  		
		$out.='<td class="property-name" style="width: 25%;">Subsol:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("subsol",$this->currentproperty->subsol).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Pret negociabil:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("negociabil",$this->currentproperty->negociabil).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Incalzire Autonoma:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("incalzire",$this->currentproperty->incalzire).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td style="width: 25%;"></td>';
		$out.='<td style="width: 25%;"></td>';
		$out.='<td class="property-name" style="width: 25%;">Telefon:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("telefon",$this->currentproperty->telefon).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td style="width: 25%;"></td>';
		$out.='<td style="width: 25%;"></td>';
		$out.='<td class="property-name" style="width: 25%;">Gaz:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("gaz",$this->currentproperty->gaz).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		//$out.='</fieldset>'; 		
		
		//$out.='<fieldset>';
		//$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td>Alte Note:</td>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		//$out.='</fieldset>'; 
		
		return $out;
	}

	function setOfertaChirieCasaVila(){
		$out='';
		//$out.='<fieldset>';
		//$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Casa/Vila:</td>';
		$out.='<td style="width: 25%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Anul constructiei:</td>';
		$out.='<td style="width: 25%;">'.$this->getAn($this->currentproperty->an).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Suprafata totala(m2):</td>';
		$out.='<td style="width: 25%;"><input type="text" id="aria_totala" name="aria_totala" class="input" value="'.$this->currentproperty->aria_totala.'"></td>';
		$out.='<td class="property-name" style="width: 25%;">Tip Constructie:</td>';
		$out.='<td style="width: 25%;">'.$this->getTipConstructie($this->currentproperty->tipconstructie_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Suprafata lotului aferent:</td>';
		$out.='<td style="width: 25%;"><input type="text" id="aria_lot" name="aria_lot" class="input" value="'.$this->currentproperty->aria_lot.'">'.$this->getAriaMasura($this->currentproperty->aria_masura_id).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Stare:</td>';
		$out.='<td style="width: 25%;">'.$this->getStare($this->currentproperty->stare_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 20%;">Pret:</td>';
		$out.='<td style="width: 30%;">';
		$out.='<input id="pret" type="text" name="pret" value="'.$this->currentproperty->pret.'">';		
		$out.=$this->getValuta($this->currentproperty->valuta_id);
		$out.='/';
		$out.=$this->getMasura($this->currentproperty->tipimobil_id,$this->currentproperty->masura_id);
		$out.='</td>';
		$out.='<td class="property-name" style="width: 25%;">Electricitate:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("electricitate",$this->currentproperty->getElectricitate()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Pret negociabil:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("negociabil",$this->currentproperty->getNgociabil()).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Apeduct:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("apeduct",$this->currentproperty->getApeduct()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;"></td>';
		$out.='<td style="width: 25%;"></td>';		
		$out.='<td class="property-name" style="width: 25%;">Canalizare:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("canalizare",$this->currentproperty->getCanalizare()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Garaj:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("garaj",$this->currentproperty->getGaraj()).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Incalzire Autonoma:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("incalzire",$this->currentproperty->getIncalzire()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Subsol:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("subsol",$this->currentproperty->getSubsol()).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Gaz:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("gaz",$this->currentproperty->getGaz()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;"></td>';
		$out.='<td style="width: 25%;"></td>';
		$out.='<td class="property-name" style="width: 25%;">Telefon:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("telefon",$this->currentproperty->getTelefon()).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		//$out.='</fieldset>'; 		
		
		//$out.='<fieldset>';
		//$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td>Alte Note:</td>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		//$out.='</fieldset>'; 

		return $out;
	}

	function setOfertaChirieComercial(){
		$out='';
		//$out.='<fieldset>';
		//$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Tip Imobil Comercial:</td>';
		$out.='<td style="width: 25%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Electricitate:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("electricitate",$this->currentproperty->getElectricitate()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Suprafata totala(m2):</td>';
		$out.='<td style="width: 25%;"><input type="text" id="aria_totala" name="aria_totala" class="input" value="'.$this->currentproperty->aria_totala.'"></td>';
		$out.='<td class="property-name" style="width: 25%;">Apeduct:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("apeduct",$this->currentproperty->getApeduct()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Suprafata lotului aferent:</td>';
		$out.='<td style="width: 25%;"><input type="text" id="aria_lot" name="aria_lot" class="input" value="'.$this->currentproperty->aria_lot.'">'.$this->getAriaMasura($this->currentproperty->aria_masura_id).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Canalizare:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("canalizare",$this->currentproperty->getCanalizare()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 20%;">Pret:</td>';
		$out.='<td style="width: 30%;">';
		$out.='<input id="pret" type="text" name="pret" value="'.$this->currentproperty->pret.'">';		
		$out.=$this->getValuta($this->currentproperty->valuta_id);
		$out.='/';
		$out.=$this->getMasura($this->currentproperty->tipimobil_id,$this->currentproperty->masura_id);
		$out.='</td>';
		$out.='<td class="property-name" style="width: 25%;">Telefon:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("telefon",$this->currentproperty->getTelefon()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Pret negociabil:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("negociabil",$this->currentproperty->getNgociabil()).'</td>';		
		$out.='<td class="property-name" style="width: 25%;">Gaz:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("gaz",$this->currentproperty->getGaz()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;"></td>';
		$out.='<td style="width: 25%;"></td>';		
		$out.='<td class="property-name" style="width: 25%;"></td>';
		$out.='<td style="width: 25%;"></td>';
		$out.='</tr>';
		$out.='</table>'; 
		//$out.='</fieldset>'; 		
		
		//$out.='<fieldset>';
		//$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td>Alte Note:</td>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		//$out.='</fieldset>'; 
	
		return $out;
	}
	function setCerereChirieApartament(){
		$out='';
		//preferinte
		
		//$out.='<fieldset>';
		//$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Apartament preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';
		$out.='</tr>';	
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Pret preferat:</td>';
		$out.='<td style="width: 25%;"><div>';
		$out.='<input id="pret" type="text" name="pret" value="'.$this->currentproperty->pret.'">';		
		$out.=$this->getValuta($this->currentproperty->valuta_id);
		$out.='/';
		$out.=$this->getMasura($this->currentproperty->tipimobil_id,$this->currentproperty->masura_id);
		$out.='</div></td>';
		$out.='</tr>';
		$out.='</table>'; 
		//$out.='</fieldset>'; 		

		//$out.='<fieldset>';
		//$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul Preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat Preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector Preferat:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		//$out.='</fieldset>';

		//$out.='<fieldset>';
		//$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td>Alte Note:</td>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		//$out.='</fieldset>'; 	

		return $out;
	}

	function setCerereChirieCasaVila(){
		$out='';
		//preferinte
		//$out.='<fieldset>';
		//$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Casa preferata:</td>';
		$out.='<td style="width: 70%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';		
		$out.='<td class="property-name" style="width: 30%;">Suprafata preferata(m2):</td>';
		$out.='<td style="width: 70%;"><input type="text" id="aria_totala" name="aria_totala" value="'.$this->currentproperty->aria_totala.'"></td>';
		$out.='</tr>';	
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Pret preferat:</td>';
		$out.='<td style="width: 25%;"><div>';
		$out.='<input id="pret" type="text" name="pret" value="'.$this->currentproperty->pret.'">';		
		$out.=$this->getValuta($this->currentproperty->valuta_id);
		$out.='/';
		$out.=$this->getMasura($this->currentproperty->tipimobil_id,$this->currentproperty->masura_id);
		$out.='</div></td>';
		$out.='</tr>';
		$out.='</table>'; 
		//$out.='</fieldset>'; 		

		//$out.='<fieldset>';
		//$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul Preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat Preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector Preferat:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		//$out.='</fieldset>';

		//$out.='<fieldset>';
		//$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td>Alte Note:</td>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		//$out.='</fieldset>'; 	

		return $out;
	}

	function setCerereChirieComercial(){
		$out='';
		//preferinte
		//$out.='<fieldset>';
		//$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Comercial preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';		
		$out.='<td class="property-name" style="width: 30%;">Suprafata preferata(m2):</td>';
		$out.='<td style="width: 70%;"><input type="text" id="aria_lot" name="aria_lot" value="'.$this->currentproperty->aria_lot.'">'.$this->getAriaMasura($this->currentproperty->aria_masura_id).'</td>';
		
		$out.='</tr>';	
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Pret preferat:</td>';
		$out.='<td style="width: 25%;"><div>';
		$out.='<input id="pret" type="text" name="pret" value="'.$this->currentproperty->pret.'">';		
		$out.=$this->getValuta($this->currentproperty->valuta_id);
		$out.='/';
		$out.=$this->getMasura($this->currentproperty->tipimobil_id,$this->currentproperty->masura_id);
		$out.='</div></td>';
		$out.='</tr>';
		$out.='</table>'; 
		//$out.='</fieldset>'; 		
	
		//$out.='<fieldset>';
		//$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul Preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat Preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector Preferat:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		//$out.='</fieldset>';

		//$out.='<fieldset>';
		//$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td>Alte Note:</td>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		//$out.='</fieldset>'; 

		return $out;
	}

	function setAdress1(){
		$out='';
		
		//$out.='<fieldset>';
		//$out.='<legend>Adresa:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Sector:</td>';
		$out.='<td style="width: 70%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Strada:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="strada" name="strada" class="input" value="'.$this->currentproperty->strada.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Nr. Casa:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="casa_nr" name="casa_nr" class="input" value="'.$this->currentproperty->casa_nr.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Nr. Scara:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="scara_nr" name="scara_nr" class="input" value="'.$this->currentproperty->scara_nr.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Nr. Apartament:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="apartament_nr" name="apartament_nr" class="input" value="'.$this->currentproperty->apartament_nr.'"></td>';
		$out.='</tr>';
		//$out.='</table>';	
		//$out.='</fieldset>'; 
		
		//$out.='<fieldset>';
		//$out.='<legend>Note la Adresa:</legend>';
		//$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Note la Adresa:</td>';
		$out.='<td style="width: 70%;"><textarea id="noteadresa" name="noteadresa" class="text">'.$this->currentproperty->noteadresa.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		//$out.='</fieldset>'; 

		return $this->getWizardPage($out);	
	}
	function setMap1(){
		$out='';
		//$out.='<div class="form_row">';
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->currentproperty->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->currentproperty->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->currentproperty->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->currentproperty->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->currentproperty->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->currentproperty->lng.'"/>';
		$out.='<div id="map"></div>';
		//$out.='</div>';
		return $this->getWizardPage($out);
	}

	function setFiles1(){
		$out='';
		//$out.='<fieldset id="fieldset-files">';
		//$out.='<legend>Imagini:</legend>';
		$out.='<div id="msg" class="form_row">';
		$out.='Atentie! Doar fisiere (jpg,jpeg,gif,png) pina la 1MB altfel fisierele vor fi filtrare.';	
		$out.='</div>';
		$out.='<div id="filestable" class="form_row">'; 	  
		$out.=$this->getFiles($this->currentfiles);
		$out.='</div>';
		$out.='<div id="filestable" class="form_row" style="text-align: right;">'; 		
		$out.='<input name="back" type="button" class="button" value="Upload" onclick="javascript:WizardUploadButtonOnClick();">';
		$out.='</div>';		
		//$out.='</fieldset>';
		return $this->getWizardPage($out);	 
	}
	function getFiles1($files){
		$output="<table id=\"files\" border=1 style=\"width:100%;\" width=\"100%\">";
		if ((sizeof($files)!=0)){
		    for ($i = 0; $i <= 4; $i++) {
		    	if (isset($files[$i]->error)){
			    	if ($files[$i]->error==UPLOAD_ERR_OK){
			        	$output.="<tr><td style=\"text-align: right;\">Imaginea ".($i+1).":<input type=\"hidden\" id=\"fileid[]\" name=\"fileid[]\" value=\"$i\"><input type=\"hidden\" id=\"status[]\" name=\"status[]\" value=\"1\"></td><td style=\"text-align: left;\"> [".$files[$i]->filename."]</td><td style=\"text-align: left;\"><a onclick=\"ReplaceRow(this.parentNode.parentNode.rowIndex)\">sterge</a></td></tr>";
			    	}else {
			    		$output.="<tr><td style=\"text-align: right;\">Imaginea ".($i+1).":<input type=\"hidden\" id=\"fileid[]\" name=\"fileid[]\" value=\"$i\"><input type=\"hidden\" id=\"status[]\" name=\"status[]\" value=\"0\"></td><td style=\"text-align: left;\"><input type=\"file\" id=\"file[]\" name=\"file[]\" ></td><td style=\"text-align: left;\">".Image::getErrorMsg($files[$i]->filename,$files[$i]->error)."</td></tr>";
			    	}
		    	} else {
		    		$output.="<tr><td style=\"text-align: right;\">Imaginea ".($i+1).":<input type=\"hidden\" id=\"fileid[]\" name=\"fileid[]\" value=\"$i\"><input type=\"hidden\" id=\"status[]\" name=\"status[]\" value=\"0\"></td><td style=\"text-align: left;\"><input type=\"file\" id=\"file[]\" name=\"file[]\" ></td><td></td></tr>";
		    	}
			}
		}
		$output.="</table>";
		return $output;
	}
	function setVideo(){
		$out='';
		//$out.='<fieldset id="fieldset-files">';
		//$out.='<legend>Imagini:</legend>';
		$out.='<div id="msg" class="form_row">';
		$out.='Puneti adresa url de pe YouTube daca aveti.';	
		$out.='</div>';
		$out.='<div id="filestable" class="form_row" style="text-align: right">'; 		
		$out.='<input name="youtubeurl" type="text" style="width:100%"  value="'.$this->currentproperty->youtubeurl.'">';
		$out.='</div>';		
		//$out.='</fieldset>';
		return $this->getWizardPage($out);	 
	}
	function setContacts1(){
		$out='';

		//$out.='<fieldset>';
		//$out.='<legend>Contacte:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Nume:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="name" name="name" value="'.$this->currentcontact->name.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Url:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="url" name="url" value="'.$this->currentcontact->url.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Fix:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="phone" name="phone" value="'.$this->currentcontact->phone.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Mobil:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="mobile" name="mobile" value="'.$this->currentcontact->mobile.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Email:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="email" name="email" value="'.$this->currentcontact->email.'"></td>';
		$out.='</tr>';
		//$out.='</table>';	
		//$out.='</fieldset>'; 
		
		//$out.='<fieldset>';
		//$out.='<legend>Note la Contacte:</legend>';
		//$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Note la contacte:</td>';
		$out.='<td style="width: 70%;"><textarea id="notecontact" name="notecontact">'.$this->currentcontact->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		//$out.='</fieldset>'; 

		//$out.='<fieldset>';
		//$out.='<legend>Valideaza:</legend>';		
		//$out.='<table class="property-table" align="center" style="width: 100%;">';
		//$out.='<tr>';
		//$out.='<td class="property-name" style="width: 30%;">Introdu codul din imagine:</td>';
		//$out.='<td style="width: 70%;"><input type="text" id="validationcode" name="validationcode"><img src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"> ! Obligator</td>';
		//$out.='</tr>';
		//$out.='</table>';

		//$out.='</fieldset>';
		return $this->getWizardPage($out);	 
	}	

	function setValidation(){

		$out=' <table>';
		$out.=' <tr><td>Introdu codul din imagine:</td><td><input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"></td></tr>';	
		$out.=' </table>';	
		$out.='<div class="error">';
		$out.=$this->errormessage;
		$out.='</div>';		
		return $this->getWizardPage($out);
	}		

	function getScopDropDown($scopid){
		$s=new Scop;
		$ss=$s->getAll("id in (2,4)","`order`");
		$out='<select id="scop_id" name="scop_id" size="1" onchange="javascript:WizardOnDropDownChange()">';

		if (!is_null($ss)){
			foreach($ss as $s){
				if ($s->id==$scopid){
					$out.= "<option value=".$s->id." selected>".$s->name." (".$s->note.")"."</option>";
				} else {
					$out.= "<option value=".$s->id.">".$s->name." (".$s->note.")"."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}
	function getTipImobilDropDown($scopid,$tipimobilid){
		$t=new TipImobil;
		$ts=$t->getAll("scop_id='$scopid'","`order`");
		$out='<select id="tipimobil_id" name="tipimobil_id" size="1" onchange="javascript:WizardOnDropDownChange()">';

		if (!is_null($ts)){
			foreach($ts as $t){
				if ($t->id==$tipimobilid){
					$out.= "<option value=".$t->id." selected>".$t->name."</option>";
				} else {
					$out.= "<option value=".$t->id.">".$t->name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}
	function getSubTipImobilDropDown($tipimobilid,$subtipimobilid){
		$st=new SubTipImobil;
		$sts=$st->getAll("tipimobil_id='$tipimobilid'","`order`");
		$out="<select id=\"subtipimobil_id\" name=\"subtipimobil_id\" class=\"select\" size=\"1\">";

		if (!is_null($sts)){
			foreach($sts as $st){
				if ($st->id==$subtipimobilid){
					$out.= "<option value=".$st->id." selected>".$st->name."</option>";
				} else {
					$out.= "<option value=".$st->id.">".$st->name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}
	//Fill Valuta with data from DB
	function getValuta($valutaid){
		$v= new Valuta();
		$vs=$v->getAll("","`order`");
		$out="<select id=\"valuta_id\" name=\"valuta_id\" class=\"select\" size=\"1\">";
		foreach($vs as $v){
			if($v->id==$valutaid){
				$out.= "<option value=".$v->id." selected>".$v->name."</option>";
			}else{
			$out.= "<option value=\"".$v->id."\">".$v->name."</option>";
			}		
		}
		$out.="</select>";
	return $out;
	}
	//Fill Aria Masura with data from DB
	function getAriaMasura($ariamasuraid){
	$ariamasura= new AriaMasura();
	$ams=$ariamasura->getAll();
	$out="<select id=\"aria_masura_id\" name=\"aria_masura_id\" class=\"select\" size=\"1\">";
	foreach($ams as $m){
		 if($m->id==$ariamasuraid){
		  $out.= "<option value=".$m->id." selected>".$m->name."</option>";
		 }else{
		  $out.= "<option value=\"".$m->id."\">".$m->name."</option>";
		 }			
	}
	$out.="</select>";
	return $out;
	}	
	//Fill Masura with data from DB
	function getMasura($tipimobilid,$masuraid){
		$masura= new Masura();
		$ms=$masura->getAll("tipimobil_id='$tipimobilid'","`order`");
		$out="<select id=\"masura_id\" name=\"masura_id\" class=\"select\" size=\"1\">";
		//print_r($ms);
		foreach($ms as $m){
			 if($m->id==$masuraid){
			  $out.= "<option value=".$m->id." selected>".$m->name."</option>";
			 }else{
			  $out.= "<option value=\"".$m->id."\">".$m->name."</option>";
			 }			
		}
		$out.="</select>";
		return $out;
	}
	function getCheckBox($name, $value){
		$output="";
		if ($value==0){
			$output.="<input type=\"checkbox\" name=\"".$name."cb\" value=\"0\" onchange=\"javascript:WizardOnCheckBoxChange('$name')\">";
		} else {
			$output.="<input type=\"checkbox\" name=\"".$name."cb\" value=\"1\" checked=\"checked\" onchange=\"javascript:WizardOnCheckBoxChange('$name')\">";
		}
		$output.="<input type=\"hidden\" id=\"$name\" name=\"$name\" value=\"$value\">";
		return  $output;
	}
	function getAn($an){
		$oldyear=1900;
		$curentyear=date("Y");
		$an_result="<select id=\"an\" name=\"an\" class=\"select\" size=\"1\">";
		$an_result.= "<option value=\"0\">- - -</option>";
		while($oldyear<=$curentyear)
		{
		 if($curentyear==$an)
		 {
		  $an_result.= "<option value=".$curentyear." selected>".$curentyear."</option>";
		 }
		 else
		 {
		  $an_result.= "<option value=\"".$curentyear."\">".$curentyear."</option>";
		 }
		 $curentyear=$curentyear-1;
		}
		$an_result.="</select>";
		return $an_result;
	}
	function getModel($modelid){
		$m= new Model();
		$ms=$m->getAll("","`order`");
		$out="<select id=\"model_id\" name=\"model_id\" class=\"select\" size=\"1\">";
		$out.= "<option value=\"0\">- - -</option>";
		foreach ($ms as $m){
			 if($m->id==$modelid){
			  $out.= "<option value=".$m->id." selected>".$m->name."</option>";
			 }
			 else
			 {
			  $out.= "<option value=\"".$m->id."\">".$m->name."</option>";
			 }			
		}
		$out.="</select>";
		return $out;
	}
	//Fill TipConstructie with data from DB
	function getTipConstructie($tipconstructie_id){
		$t= new TipConstructie();
		$ts=$t->getAll("","`order`");
		$out="<select id=\"tipconstructie_id\" name=\"tipconstructie_id\" class=\"select\" size=\"1\">";
		$out.= "<option value=\"0\">- - -</option>";
		foreach($ts as $t){
			if($t->id==$tipconstructie_id){
			$out.= "<option value=".$t->id." selected>".$t->name."</option>";
			}else{
			$out.= "<option value=\"".$t->id."\">".$t->name."</option>";
			}			
		}
		$out.="</select>";
		return  $out;
	}
	//Fill Starea combobox with data from DB
	function getStare($stareid){
		$s= new Stare();
		$ss=$s->getAll("","`order`");
		$out="<select id=\"stare_id\" name=\"stare_id\" class=\"select\" size=\"1\">";
		$out.= "<option value=\"0\">- - -</option>";
		foreach($ss as $s){
			if($s->id==$stareid){
			$out.= "<option value=".$s->id." selected>".$s->name."</option>";
			}else{
			$out.= "<option value=\"".$s->id."\">".$s->name."</option>";
			}			
		}
		$out.="</select>";
		return  $out;
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
$n=new PropertyWebPage();
?>
