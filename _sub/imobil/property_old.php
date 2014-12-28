<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class PropertyWebPage extends MainWebPage {
	public $mod="view";
	public $step=1;
	public $steps=6;
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
		$this->setJavascript("js/scripts.js");

		$t="ANUNŢURI IMOBILIARE DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);

		if (!isset($this->id)){
			$this->id=0;
		}
		if (!isset($this->mod)){
			$this->mod="view";
		}
		if (($this->id!=0)&&($this->mod=="view")){
			$p=new Property();
			$p->loadById($this->id);
						
			$this->setDescription($p->getShortDescription());

			$c=new Contact();
			$c->loadById($p->contact_id);
			$i=new Image();
			$is=$i->getAll("imobil_id=".$p->id);

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

			$this->getPropertyPage("");
			
			User::delCurrentProperty();
			User::delCurrentContact();
			User::delCurrentFiles();
			//$this->getOfertaImobilApartament();
		}else{
			$this->currentproperty=User::getCurrentProperty();
			$this->currentcontact=User::getCurrentContact();
			$this->currentfiles=User::getCurrentFiles();

			if ($this->mod=="upd"){
				$p=new Property();
				$p->loadById($this->id);

				if (User::getCurrentUser()->id==0){
						$this->redirect(Config::$imobilsite.'/property.php?id='.$this->id);
				}

				if (($p->user_id!=User::getCurrentUser()->id)){
					if (User::getCurrentUser()->role_id!=2){
						$this->redirect(Config::$imobilsite.'/property.php?id='.$this->id);
					}
				}

				$c=new Contact();
				$c->loadById($p->contact_id);
				$i=new Image();
				$is=$i->getAll("imobil_id=".$p->id);
	
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
			  if((is_array($_FILES))&&(sizeof($_FILES)!=0)){
			  	 $filescounter=0;
			  	 for ($i = 0; $i <= 4; $i++) {
			  	 	if ($_POST[status][$i]!=1){
			  	 		//delete previously add file if added
			  	 		
			  	 		if (isset($this->currentfiles[$i]->filepath)){
			  	 				unlink(Config::$filespath."/".$this->currentfiles[$i]->filepath);
			  	 				if ((isset($this->currentfiles[$i]->id))){
			  	 					$this->currentfiles[$i]->delete();
			  	 					unlink(Config::$filespath."/t".$this->currentfiles[$i]->filepath);
			  	 				}
			  	 				$f=new Image();
			  	 				$this->currentfiles[$i]=$f;
			  	 		}
			  	 		
			  	 		//add files
			  	 		$this->currentfiles[$i]->filename=$_FILES["file"]["name"][$filescounter];
			  	 		$this->currentfiles[$i]->name=$_FILES["file"]["name"][$filescounter];
			  	 		$this->currentfiles[$i]->type=$_FILES["file"]["type"][$filescounter];
			  	 		$this->currentfiles[$i]->size=$_FILES["file"]["size"][$filescounter];
			  	 		$this->currentfiles[$i]->tmp_name=$_FILES["file"]["tmp_name"][$filescounter];
			  	 		if ($_FILES["file"]["error"][$filescounter]!=0){
			  	 			$this->currentfiles[$i]->error=$_FILES["file"]["error"][$filescounter];
			  	 		}elseif (!array_key_exists($_FILES["file"]["type"][$filescounter], Config::$file_types)){
			  	 			$this->currentfiles[$i]->error=8;
			  	 		}elseif($_FILES["file"]["size"][$filescounter]>Config::$file_size){
			  	 			$this->currentfiles[$i]->error=1;
			  	 		}else {
			  	 			$this->currentfiles[$i]->error=$_FILES["file"]["error"][$filescounter];
			  	 			$this->currentfiles[$i]->new_name=System::getRandomFileName($this->currentfiles[$i]->name);
			  	 			$this->currentfiles[$i]->filepath=$this->currentfiles[$i]->new_name;
	        				move_uploaded_file($this->currentfiles[$i]->tmp_name, Config::$filespath."/".$this->currentfiles[$i]->new_name);
			  	 		}
			  	 		$filescounter=$filescounter+1;
			  	 	}
			  	}
			  }
	
			if($tmpraionid!=$this->currentproperty->raion_id){
				$this->currentproperty->localitate_id=Location::getTopFirstLocationByRaionId($this->currentproperty->raion_id)->id;
				$this->currentproperty->sector_id=Sector::getTopFirstSectorByLocalitateId($this->currentproperty->localitate_id)->id;
			}
			if($tmplocalitateid!=$this->currentproperty->localitate_id){
				$this->currentproperty->sector_id=Sector::getTopFirstSectorByLocalitateId($this->currentproperty->localitate_id)->id;
			}
			
			if (isset($this->scop_id)){
				if (($this->scop_id==1)||($this->scop_id==3)){
					$this->currentproperty->scop_id=$this->scop_id;
				}				
			}		

			User::setCurrentProperty($this->currentproperty);
			User::setCurrentContact($this->currentcontact);
			User::setCurrentFiles($this->currentfiles);
			
			//Logger::setLogs($this->currentproperty);
			//Logger::setLogs($this->currentcontact);
			
			//Logger::setLogs($this);
			//Logger::setLogs($this->currentfiles);
			//Logger::setLogs($_FILES);
			//print_r($this->currentfiles);
			//print_r($_FILES);
			
			//Set the wizard steps
			if (($this->currentproperty->scop_id==1)||($this->currentproperty->scop_id==2)){
				$this->steps=6;	
			} else {
				$this->steps=3;	
			}
			//Cancel the wizard
			if ($this->action=="cancel"){
				User::delCurrentProperty();
				User::delCurrentContact();
				User::delCurrentFiles();
				$this->redirect("index.php");
			}
			//Step Tip Anunt 
			if ($this->step==1){
				$this->step_title="Alege Tipul Anuntului";
				if ($this->action=="next"){
					$this->redirect("property.php?step=".($this->step+1));
				}
				$this->setTipAnunt();
			}
			$s=new Scop();
			$s->loadById($this->currentproperty->scop_id);
			$t=new TipImobil();
			$t->loadById($this->currentproperty->tipimobil_id);
			//$this->setTitle($s->name."-".$s->note."-".$t->name);
			$this->step_title=$s->name." - ".$s->note." ".$t->name.": Adauga ";
			//$this->setLogoTitle($s->name."-".$s->note."-".$t->name);
	
			//Oferta Imobil,Chirii	
			if (($this->currentproperty->scop_id==1)||($this->currentproperty->scop_id==2)){
				if ($this->step==2){		
					if ($this->action=="next"){
						$this->redirect("property.php?step=".($this->step+1));
					}
					if ($this->action=="back"){
						$this->redirect("property.php?step=".($this->step-1));
					}
					if ($this->currentproperty->tipimobil_id==11){
						$this->step_title=$this->step_title."Detalii";					
						$this->setOfertaImobilApartament();	
					}
					if ($this->currentproperty->tipimobil_id==12){	
						$this->step_title=$this->step_title."Detalii";				
						$this->setOfertaImobilCasaVila();	
					}
					if ($this->currentproperty->tipimobil_id==13){	
						$this->step_title=$this->step_title."Detalii";
						$this->setOfertaImobilTeren();	
					}
					if ($this->currentproperty->tipimobil_id==14){
						$this->step_title=$this->step_title."Detalii";	
						$this->setOfertaImobilComercial();	
					}
					if ($this->currentproperty->tipimobil_id==21){	
						$this->step_title=$this->step_title."Detalii";			
						$this->setOfertaChirieApartament();	
					}
					if ($this->currentproperty->tipimobil_id==22){
						$this->step_title=$this->step_title."Detalii";					
						$this->setOfertaChirieCasaVila();	
					}
					if ($this->currentproperty->tipimobil_id==24){
						$this->step_title=$this->step_title."Detalii";						
						$this->setOfertaChirieComercial();	
					}
				}
				if ($this->step==3){
					$this->step_title=$this->step_title."Adresa";						
					if ($this->action=="next"){
						$this->redirect("property.php?step=".($this->step+1));
					}
					if ($this->action=="back"){
						$this->redirect("property.php?step=".($this->step-1));
					}
					$this->setAdress();
				}
				if ($this->step==4){
					//$this->setBodyTag('<body onload="WizardOnMapLoad()" onunload="GUnload()">');
					//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=3&amp;key=".Config::getMapKey($this->getServerName()));
					//$this->setTitle("Pozitia pe harta a Imobilului, faceti click pe harta pentru a inregistra locatia.");
					$this->step_title=$this->step_title."Pozitia pe harta";
					//$this->step_title="Pozitia pe harta a Imobilului, faceti click pe harta pentru a inregistra locatia.";
					//$this->setLogoTitle("Pozitia pe harta a Imobilului, faceti click pe harta pentru a inregistra locatia.");			
		
					if ($this->action=="next"){
						$this->redirect("property.php?step=".($this->step+1));
					}
					if ($this->action=="back"){
						$this->redirect("property.php?step=".($this->step-1));
					}
					$this->setMap();
				}
				if ($this->step==5){
					$this->step_title=$this->step_title."Imagini/Plan/Proiect";
					//$this->setLogoTitle("Imagini/Plan/Proiect");			
					if ($this->action=="next"){
						$this->redirect("property.php?step=".($this->step+1));
					}
					if ($this->action=="back"){
						$this->redirect("property.php?step=".($this->step-1));
					}
					$this->setFiles();
				}
				if ($this->step==6){
					$this->step_title=$this->step_title."Date de contact";
					//$this->setLogoTitle("Date de contact".User::getValidationCode());		
					
					if ($this->action=="next"){
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
								$f->imobil_id=$p->id;
								if ($f->filename!=''){
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
					if ($this->action=="back"){
						$this->redirect("property.php?step=".($this->step-1));
					}
					$this->setContacts();
				}
			//Cerere Imobil, Chirii
			} else {
				if ($this->step==2){		
					//User::setCurrentProperty($this->currentproperty);
					if ($this->action=="next"){
						$this->redirect("property.php?step=".($this->step+1));
					}
					if ($this->action=="back"){
						$this->redirect("property.php?step=".($this->step-1));
					}
					if ($this->currentproperty->tipimobil_id==31){
						//$this->setTitle("Detalii despre apartament");
						//$this->setLogoTitle("Detalii despre apartament");
						$this->step_title=$this->step_title."Detalii Preferate";					
						$this->setCerereImobilApartament();	
					}
					if ($this->currentproperty->tipimobil_id==32){
						$this->setTitle("Detalii despre casa");
						$this->setLogoTitle("Detalii despre casa");	
						$this->step_title=$this->step_title."Detalii";				
						$this->setCerereImobilCasaVila();	
					}
					if ($this->currentproperty->tipimobil_id==33){
						$this->setTitle("Detalii despre teren");
						$this->setLogoTitle("Detalii despre teren");	
						$this->step_title=$this->step_title."Detalii";
						$this->setCerereImobilTeren();	
					}
					if ($this->currentproperty->tipimobil_id==34){
						$this->setTitle("Detalii despre comercial");
						$this->setLogoTitle("Detalii despre comercial");
						$this->step_title=$this->step_title."Detalii";	
						$this->setCerereImobilComercial();	
					}
					if ($this->currentproperty->tipimobil_id==41){
						$this->setTitle("Detalii despre apartament");
						$this->setLogoTitle("Detalii despre apartament");		
						$this->step_title=$this->step_title."Detalii Preferate";			
						$this->setCerereChirieApartament();	
					}
					if ($this->currentproperty->tipimobil_id==42){
						$this->setTitle("Detalii despre apartament");
						$this->setLogoTitle("Detalii despre apartament");
						$this->step_title=$this->step_title."Detalii Preferate";				
						$this->setCerereChirieCasaVila();	
					}
					if ($this->currentproperty->tipimobil_id==44){
						$this->setTitle("Detalii despre apartament");
						$this->setLogoTitle("Detalii despre apartament");
						$this->step_title=$this->step_title."Detalii Preferate";					
						$this->setCerereChirieComercial();	
					}
				}
				if ($this->step==3){
	
					$this->step_title=$this->step_title."Date de contact";
					$this->setLogoTitle("Date de contact");		
					
					//Exception 
					//$this->currentcontact->note=$this->notecontact;	
					//User::setCurrentContact($this->currentcontact);
					if ($this->action=="next"){
						if (User::getValidationCode()==$this->validationcode){
							//save property
							$c=User::getCurrentContact();
							$p=User::getCurrentProperty();
							
							//save contact
							$c->save();
							//save property
							//$p->contact_id=$c->id;
							$p->contact_id=$c->id;
							$p->status=0;
							$p->save();
							//$fs=User::getCurrentFiles();
							//foreach ($fs as $f){
							//	$f->imobil_id=$p->id;
							//	if ($f->filename!=""){
							//		$f->save();
							//	}
							//}
							//delete property from cache
							User::delCurrentProperty();
							User::delCurrentContact();
							User::delCurrentFiles();				
							//redirect to view saved property
							$this->redirect("property.php?id=".$p->id);
						}
					}
					if ($this->action=="back"){
						$this->redirect("property.php?step=".($this->step-1));
					}
					$this->setContacts();
				}			
			}
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
		$out.='  <form id="frmProperty" name="frmProperty" method="POST" action="property.php?step='.$this->step.'" enctype="multipart/form-data">';
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
		if (($this->step==6)||(($this->step==3)&&(($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)))){
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
	function getPropertyHeader(){
		$out='<div id="propertyheader" class="groupbox" style="margin-bottom:2px;padding:10px;">';
		$out.='<div style="float:left;"><a href="property.php" class="link_button">Adauga Anunt</a></div>';
		$out.='<div style="float:right;">';
		if ($this->currentproperty->getPrevious()!=0){
			$out.='<a href="property.php?id='.$this->currentproperty->getPrevious().'" class="link_button" style="margin-right:10px;"><< Oferta Similara</a>';
		}
		if ($this->currentproperty->getNext()!=0){
			$out.='<a href="property.php?id='.$this->currentproperty->getNext().'" class="link_button">Oferta Similara >></a>';
		}
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';	
		$out.='</div>';		
		return $out;	
	}
	function getPropertyPage($html){
		//$this->setBodyTag('<body onload="WizardOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));
		//$this->setJavascript("js/prototype.js");
		//$this->setJavascript("js/scriptaculous.js?load=effects,builder");
		//$this->setJavascript("js/lightbox.js");
		//$this->setCSS("styles/lightbox.css");
		$this->setCSS(Config::$mainsite."/common/style/lightbox.css");
		//$this->setJavascript(Config::$mainsite."/common/js/builder.js");
		//$this->setJavascript(Config::$mainsite."/common/js/effects.js");

		$this->setJavascript(Config::$mainsite."/common/js/prototype.js");
		$this->setJavascript(Config::$mainsite."/common/js/scriptaculous.js?load=effects,builder");
		$this->setJavascript(Config::$mainsite."/common/js/lightbox.js");

		$this->setTitle($this->currentproperty->getShortDescription());
		$this->setKeywords($this->currentproperty->getKeywords());
		
		$out=' <div id="form_container" class="form_container">';
		$out.=$this->getPropertyHeader();
		$out.='<div id="form" class="form">';				
		$out.='<div id="property-view-title">';
		$out.=$this->currentproperty->getShortDescription();
		$out.='</div>';
		$out.='<div id="property-view-date" style="text-align:right">';
		$out.='Data publicarii:'.$this->currentproperty->getData();
		$out.='</div>';				
		if ($this->currentproperty->getScop()->id==1){
			if ($this->currentproperty->getTipImobil()->id==11){
				$out.=$this->getOfertaImobilApartament();
			}
			if ($this->currentproperty->getTipImobil()->id==12){
				$out.=$this->getOfertaImobilCasaVila();
			}
			if ($this->currentproperty->getTipImobil()->id==13){
				$out.=$this->getOfertaImobilTeren();
			}
			if ($this->currentproperty->getTipImobil()->id==14){
				$out.=$this->getOfertaImobilComercial();
			}
			$out.=$this->getAdress();
			$out.=$this->getMap();
			$out.=$this->getFilesView();
		}
		if ($this->currentproperty->getScop()->id==2){
			if ($this->currentproperty->getTipImobil()->id==21){
				$out.=$this->getOfertaChirieApartament();
			}
			if ($this->currentproperty->getTipImobil()->id==22){
				$out.=$this->getOfertaChirieCasaVila();
			}
			if ($this->currentproperty->getTipImobil()->id==24){
				$out.=$this->getOfertaChirieComercial();
			}
			$out.=$this->getAdress();
			$out.=$this->getMap();
			$out.=$this->getFilesView();			
		}
		if ($this->currentproperty->getScop()->id==3){
			if ($this->currentproperty->getTipImobil()->id==31){
				$out.=$this->getCerereImobilApartament();
			}
			if ($this->currentproperty->getTipImobil()->id==32){
				$out.=$this->getCerereImobilCasaVila();
			}
			if ($this->currentproperty->getTipImobil()->id==33){
				$out.=$this->getCerereImobilTeren();
			}
			if ($this->currentproperty->getTipImobil()->id==34){
				$out.=$this->getCerereImobilComercial();
			}
		}
		if ($this->currentproperty->getScop()->id==4){
			if ($this->currentproperty->getTipImobil()->id==41){
				$out.=$this->getCerereChirieApartament();
			}
			if ($this->currentproperty->getTipImobil()->id==42){
				$out.=$this->getCerereChirieCasaVila();
			}
			if ($this->currentproperty->getTipImobil()->id==44){
				$out.=$this->getCerereChirieComercial();
			}			
		}		
		$out.=$this->getContacts();
		$out.='</div>';
		$out.=$this->getPropertyHeader();
		$out.='</div>';
		$this->show($out);
	}
	
	function setTipAnunt(){
		$out='';
		$out.='<fieldset id="fieldset-tipanunt">';
		$out.='<legend>Tip Anunt, Tip Imobil:</legend>';		
		$out.='<div class="form_row">';
		$out.=' <label>Tip Anunt:</label>';
		$out.=$this->getScopDropDown($this->currentproperty->scop_id);			
		$out.='</div>';    
		$out.='<div class="form_row">';
		$out.=' <label>Tip Imobil:</label>';
		$out.=$this->getTipImobilDropDown($this->currentproperty->scop_id,$this->currentproperty->tipimobil_id);	
		$out.='</div>';	
		$out.='</fieldset>'; 
		$this->show($this->setWizardPage($out));	 
	}

	function setOfertaImobilApartament(){
		$out='';
		$out.='<fieldset>';
		$out.='<legend>Detalii:</legend>';		
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
		$out.='<td class="property-name" style="width: 25%;">Pret:</td>';
		$out.='<td style="width: 25%;"><div>';
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
		$out.='</fieldset>'; 		
		
		$out.='<fieldset>';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 

		$this->show($this->setWizardPage($out));
	}
	function getOfertaImobilApartament(){
		$out='';
		
		$out.='<fieldset id="fieldset-details-view">';
		$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Apartament:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Anul constructiei:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAn().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata totala(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Model:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getModel()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata locuibila(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLocuibila().'</td><td class="property-name" style="width: 25%;">Tip Constructie:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTipConstructie()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Etaj/Etaje:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getEtaj().'</td><td class="property-name" style="width: 25%;">Stare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getStare()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Balcoane:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getBalcoane().'</td><td class="property-name" style="width: 25%;">Garaj:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaraj().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Subsol:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubsol().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Incalzire Autonoma:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getIncalzire().'</td></tr>';
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';	
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	
		$out.='</table>'; 
		$out.='</fieldset>'; 		
		
		$out.='<fieldset id="fieldset-details-notes-view">';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 	

		return $out;
	}
	function setOfertaImobilCasaVila(){
		$out='';
		$out.='<fieldset>';
		$out.='<legend>Detalii:</legend>';		
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
		$out.='<td class="property-name" style="width: 25%;">Pret:</td>';
		$out.='<td style="width: 25%;">';
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
		$out.='</fieldset>'; 		
		
		$out.='<fieldset>';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 

		$this->show($this->setWizardPage($out));
	}
	function getOfertaImobilCasaVila(){
		$out='';
		
		$out.='<fieldset id="fieldset-details-view">';
		$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Casa/Vila:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Anul constructiei:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAn().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata totala(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Tip Constructie:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTipConstructie()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata lotului aferent:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td><td class="property-name" style="width: 25%;">Stare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getStare()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Electricitate:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getElectricitate().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Apeduct:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getApeduct().'</td></tr>';
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Canalizare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getCanalizare().'</td></tr>';	
		$out.='<tr><td class="property-name" style="width: 25%;">Garaj:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaraj().'</td><td class="property-name" style="width: 25%;">Incalzire Autonoma:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getIncalzire().'</td></tr>';	
		$out.='<tr><td class="property-name" style="width: 25%;">Subsol:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubsol().'</td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';	
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	

		$out.='</table>'; 
		$out.='</fieldset>'; 		
		
		$out.='<fieldset id="fieldset-details-notes-view">';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 				

		return $out;
	}
	function setOfertaImobilTeren(){
		$out='';

		$out.='<fieldset>';
		$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Tip Teren:</td>';
		$out.='<td style="width: 25%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Electricitate:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("electricitate",$this->currentproperty->getElectricitate()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Suprafata lotului:</td>';
		$out.='<td style="width: 25%;"><input type="text" id="aria_lot" name="aria_lot" class="input" value="'.$this->currentproperty->aria_lot.'">'.$this->getAriaMasura($this->currentproperty->aria_masura_id).'</td>';
		$out.='<td class="property-name" style="width: 25%;">Apeduct:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("apeduct",$this->currentproperty->getApeduct()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Bonitate(bal/ha):</td>';
		$out.='<td style="width: 25%;"><input type="text" id="bonitate" name="bonitate" value="'.$this->currentproperty->bonitate.'"></td>';
		$out.='<td class="property-name" style="width: 25%;">Canalizare:</td>';
		$out.='<td style="width: 25%;">'.$this->getCheckBox("canalizare",$this->currentproperty->getCanalizare()).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Pret:</td>';
		$out.='<td style="width: 25%;">';
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
		$out.='</fieldset>'; 		
		
		$out.='<fieldset>';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 
		
		$this->show($this->setWizardPage($out));
	}
	function getOfertaImobilTeren(){
		$out='';
		
		$out.='<fieldset id="fieldset-details-view">';
		$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Teren:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Electricitate:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getElectricitate().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td><td class="property-name" style="width: 25%;">Apeduct:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getApeduct().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Bonitate(bal/ha):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getBonitate().'</td><td class="property-name" style="width: 25%;">Canalizare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getCanalizare().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret negociabil:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	
		$out.='</table>'; 
		$out.='</fieldset>'; 		
		
		$out.='<fieldset id="fieldset-details-notes-view">';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 		
 
		return $out;
	}
	function setOfertaImobilComercial(){
		$out='';

		$out.='<fieldset>';
		$out.='<legend>Detalii:</legend>';		
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
		$out.='<td class="property-name" style="width: 25%;">Pret:</td>';
		$out.='<td style="width: 25%;">';
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
		$out.='</fieldset>'; 		
		
		$out.='<fieldset>';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 
	
		$this->show($this->setWizardPage($out));
	}
	function getOfertaImobilComercial(){
		$out='';
		
		$out.='<fieldset id="fieldset-details-view">';
		$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Comercial:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Electricitate:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getElectricitate().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Apeduct:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getApeduct().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata lotului aferent:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td><td class="property-name" style="width: 25%;">Canalizare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getCanalizare().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil::</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	
		$out.='</table>'; 
		$out.='</fieldset>'; 		
		
		$out.='<fieldset id="fieldset-details-notes-view">';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 		
 
		return $out;
	}
	function setOfertaChirieApartament(){
		$out='';
		$out.='<fieldset>';
		$out.='<legend>Detalii:</legend>';		
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
		$out.='<td class="property-name" style="width: 25%;">Pret:</td>';
		$out.='<td style="width: 25%;"><div>';
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
		$out.='</fieldset>'; 		
		
		$out.='<fieldset>';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 
		
		$this->show($this->setWizardPage($out));
	}
	function getOfertaChirieApartament(){
		$out='';
		
		$out.='<fieldset id="fieldset-details-view">';
		$out.='<legend>Detaliii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Apartament:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Anul constructiei:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAn().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata totala(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Model:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getModel()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata locuibila(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLocuibila().'</td><td class="property-name" style="width: 25%;">Tip Constructie:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTipConstructie()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Etaj/Etaje:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getEtaj().'</td><td class="property-name" style="width: 25%;">Stare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getStare()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Balcoane:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getBalcoane().'</td><td class="property-name" style="width: 25%;">Garaj:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaraj().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Subsol:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubsol().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Incalzire Autonoma:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getIncalzire().'</td></tr>';
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';	
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	
		$out.='</table>'; 
		$out.='</fieldset>'; 		
		
		$out.='<fieldset id="fieldset-details-notes-view">';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 	

		return $out;
	}
	function setOfertaChirieCasaVila(){
		$out='';
		$out.='<fieldset>';
		$out.='<legend>Detalii:</legend>';		
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
		$out.='<td class="property-name" style="width: 25%;">Pret:</td>';
		$out.='<td style="width: 25%;">';
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
		$out.='</fieldset>'; 		
		
		$out.='<fieldset>';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 

		$this->show($this->setWizardPage($out));
	}
	function getOfertaChirieCasaVila(){
		$out='';
		
		$out.='<fieldset id="fieldset-details-view">';
		$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Casa/Vila:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Anul constructiei:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAn().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata totala(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Tip Constructie:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTipConstructie()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata lotului aferent:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td><td class="property-name" style="width: 25%;">Stare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getStare()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Electricitate:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getElectricitate().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Apeduct:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getApeduct().'</td></tr>';
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Canalizare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getCanalizare().'</td></tr>';	
		$out.='<tr><td class="property-name" style="width: 25%;">Garaj:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaraj().'</td><td class="property-name" style="width: 25%;">Incalzire Autonoma:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getIncalzire().'</td></tr>';	
		$out.='<tr><td class="property-name" style="width: 25%;">Subsol:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubsol().'</td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';	
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	

		$out.='</table>'; 
		$out.='</fieldset>'; 		
		
		$out.='<fieldset id="fieldset-details-notes-view">';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 				

		return $out;
	}
	function setOfertaChirieComercial(){
		$out='';
		$out.='<fieldset>';
		$out.='<legend>Detalii:</legend>';		
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
		$out.='<td class="property-name" style="width: 25%;">Pret:</td>';
		$out.='<td style="width: 25%;">';
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
		$out.='</fieldset>'; 		
		
		$out.='<fieldset>';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 
	
		$this->show($this->setWizardPage($out));
	}
	function getOfertaChirieComercial(){
		$out='';
		
		$out.='<fieldset id="fieldset-details-view">';
		$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Comercial:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Electricitate:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getElectricitate().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Apeduct:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getApeduct().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata lotului aferent:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td><td class="property-name" style="width: 25%;">Canalizare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getCanalizare().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil::</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	
		$out.='</table>'; 
		$out.='</fieldset>'; 		
		
		$out.='<fieldset id="fieldset-details-notes-view">';
		$out.='<legend>Note la Detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 		
 
		return $out;
	}
	function setCerereImobilApartament(){
		$out='';
		//preferinte
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Apartament preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';		$out.='</tr>';
		$out.='<tr>';		
		$out.='<td class="property-name" style="width: 30%;">Suprafata preferata(m2):</td>';
		$out.='<td style="width: 70%;"><input type="text" id="aria_totala" name="aria_totala" value="'.$this->currentproperty->aria_totala.'"></td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Etajul preferat:</td>';
		$out.='<td style="width: 25%;"><input id="etaj" type="text" name="etaj" class="input" value="'.$this->currentproperty->etaj.'"></td>';
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
		$out.='</fieldset>'; 		

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		$out.='</fieldset>';

		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 	

		$this->show($this->setWizardPage($out));
	}
	function getCerereImobilApartament(){
		$out='';
		
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Apartament preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata preferata(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Etajul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getEtaj().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	
		
		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 	
		
		return $out;
	}	
	function setCerereImobilCasaVila(){
		$out='';
		//preferinte
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
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
		$out.='</fieldset>'; 		

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		$out.='</fieldset>';

		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 	

		$this->show($this->setWizardPage($out));
	}
	function getCerereImobilCasaVila(){
		$out='';
		
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Casa preferata:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata preferata(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	
		
		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 	
		
		return $out;
	}	
	function setCerereImobilTeren(){
		$out='';
		//preferinte
	
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Tip Teren:</td>';
		$out.='<td style="width: 70%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';		
		$out.='<td class="property-name" style="width: 30%;">Suprafata preferata:</td>';
		$out.='<td style="width: 70%;">';
		$out.='<input type="text" id="aria_lot" name="aria_lot" value="'.$this->currentproperty->aria_lot.'">';		
		$out.=$this->getAriaMasura($this->currentproperty->aria_masura_id);		
		$out.='</td>';	
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
		$out.='</fieldset>'; 		

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		$out.='</fieldset>';

		$out.='<fieldset>';
		$out.='<legend>Note la detalii:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 	

		$this->show($this->setWizardPage($out));
	}
	function getCerereImobilTeren(){
		$out='';
		
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip teren preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata preferata:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	
		
		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 	
		
		return $out;
	}	

	function setCerereImobilComercial(){
		$out='';
		//preferinte
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Comercial preferat:</td>';
		$out.='<td style="width: 70%;">'.$this->getSubTipImobilDropDown($this->currentproperty->tipimobil_id,$this->currentproperty->subtipimobil_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';		
		$out.='<td class="property-name" style="width: 30%;">Suprafata preferata:</td>';
		$out.='<td style="width: 70%;">';
		$out.='<input type="text" id="aria_lot" name="aria_lot" value="'.$this->currentproperty->aria_lot.'">';		
		$out.=$this->getAriaMasura($this->currentproperty->aria_masura_id);		
		$out.='</td>';	
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
		$out.='</fieldset>'; 		
	
		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		$out.='</fieldset>';

		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 

		$this->show($this->setWizardPage($out));
	}
	function getCerereImobilComercial(){
		$out='';
		
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Comercial preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata preferata:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	
		
		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 	
		
		return $out;
	}		

	function setCerereChirieApartament(){
		$out='';
		//preferinte
		
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
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
		$out.='</fieldset>'; 		

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		$out.='</fieldset>';

		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 	

		$this->show($this->setWizardPage($out));
	}
	function getCerereChirieApartament(){
		$out='';
		
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Apartament preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	
		
		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 	
		
		return $out;
	}	
	function setCerereChirieCasaVila(){
		$out='';
		//preferinte
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
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
		$out.='</fieldset>'; 		

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		$out.='</fieldset>';

		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 	

		$this->show($this->setWizardPage($out));
	}
	function getCerereChirieCasaVila(){
		$out='';
		
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Casa/Vila preferata:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata preferata(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	
		
		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 	
		
		return $out;
	}	
	function setCerereChirieComercial(){
		$out='';
		//preferinte
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
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
		$out.='</fieldset>'; 		
	
		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Municipiul/Raionul:</td>';
		$out.='<td style="width: 70%;">'.$this->getRaionDropDown($this->currentproperty->raion_id).'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Oras/Sat:</td>';
		$out.='<td style="width: 70%;">'.$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id).'</tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 25%;">Sector:</td>';
		$out.='<td style="width: 25%;">'.$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id).'</td>';
		$out.='</tr>';
		$out.='</table>'; 
		$out.='</fieldset>';

		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="note" name="note">'.$this->currentproperty->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 

		$this->show($this->setWizardPage($out));
	}
	function getCerereChirieComercial(){
		$out='';
		
		$out.='<fieldset>';
		$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Comercial preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata preferata:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	

		$out.='<fieldset>';
		$out.='<legend>Adresa Preferata:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='</table>'; 
		$out.='</fieldset>';	
		
		$out.='<fieldset>';
		$out.='<legend>Note:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 	
		
		return $out;
	}	
	function setAdress(){
		$out='';
		
		$out.='<fieldset>';
		$out.='<legend>Adresa:</legend>';		
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
		$out.='</table>';	
		$out.='</fieldset>'; 
		
		$out.='<fieldset>';
		$out.='<legend>Note la Adresa:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="noteadresa" name="noteadresa" class="text">'.$this->currentproperty->noteadresa.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 

		/*
		$out.='<fieldset id="fieldset-adresa">';
		$out.='<legend>Adresa:</legend>';
		$out.='<div class="form_row">';
		$out.='<label>Municipiul/Raionul:</label>';
		$out.=$this->getRaionDropDown($this->currentproperty->raion_id);	
		$out.='</div>';	       				
		$out.='<div class="form_row">';
		$out.='<label>Localitatea:</label>';
		$out.=$this->getLocationDropDown($this->currentproperty->raion_id,$this->currentproperty->localitate_id);			
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label>Sector:</label>';
		$out.=$this->getSectorDropDown($this->currentproperty->localitate_id,$this->currentproperty->sector_id);			
		$out.='</div>';  		    				
		$out.='<div class="form_row">';
		$out.='<label>Strada:</label>';
		$out.='<input type="text" id="strada" name="strada" class="input" value="'.$this->currentproperty->strada.'">';		
		$out.='</div>';  
		$out.='<div class="form_row">';
		$out.='<label>Nr. Casa:</label>';
		$out.='<input type="text" id="casa_nr" name="casa_nr" class="input" value="'.$this->currentproperty->casa_nr.'">';		
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label>Nr. Scara:</label>';
		$out.='<input type="text" id="scara_nr" name="scara_nr" class="input" value="'.$this->currentproperty->scara_nr.'">';		
		$out.='</div>';  
		$out.='<div class="form_row">';
		$out.='<label>Nr. Apartament:</label>';
		$out.='<input type="text" id="apartament_nr" name="apartament_nr" class="input" value="'.$this->currentproperty->apartament_nr.'">';		
		$out.='</div>';
		$out.='</fieldset>'; 
		$out.='<fieldset id="fieldset-notes">';
		$out.='<legend>Note:</legend>';
		$out.='<div class="form_row">';
		$out.='<label>Alte detalii la adresa:</label>';
		$out.='<textarea id="noteadresa" name="noteadresa" class="text">'.$this->currentproperty->noteadresa.'</textarea>';			
		$out.='</div>';  
		$out.='</fieldset>'; 
		*/
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
		
/*		
		$out.='<fieldset id="fieldset-address-view">';
		$out.='<legend>Adresa:</legend>';
		$out.='<dl>';
		$out.='<dt>Municipiul/Raionul:</dt>';
		$out.='<dd>'.$this->currentproperty->getRaion()->getFullName().'</dd>';	      				
		$out.='<dt>Localitatea:</dt>';
		$out.='<dd>'.$this->currentproperty->getLocation()->getFullName().'</dd>';			
		$out.='<dt>Sector:</dt>';
		$out.='<dd>'.$this->currentproperty->getSector()->name.'</dd>';			 		    				
		$out.='<dt>Strada:</dt>';
		$out.='<dd>'.$this->currentproperty->strada.'</dd>';
		$out.='</dl>';
		$out.='<dl>';		
		$out.='<dt>Nr. Casa:</dt>';
		$out.='<dd>'.$this->currentproperty->casa_nr.'</dd>';		
		$out.='<dt>Nr. Scara:</dt>';
		$out.='<dd>'.$this->currentproperty->scara_nr.'</dd>';		  
		$out.='<dt>Nr. Apartament:</dt>';
		$out.='<dd>'.$this->currentproperty->apartament_nr.'</dd>';	
		$out.='</dl>';		
		$out.='</fieldset>'; 
		$out.='<fieldset id="fieldset-address-notes-view">';
		$out.='<legend>Note la Adresa:</legend>';
		$out.='<dl>';
		$out.='<dd>'.$this->currentcontact->noteadresa.'</dd>';
		$out.='</dl>';					
		$out.='</fieldset>';
*/ 
		return $out;
	}
	function setMap(){
		$out='';
		$out.='<div class="form_row">';
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->currentproperty->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->currentproperty->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->currentproperty->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->currentproperty->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->currentproperty->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->currentproperty->lng.'"/>';
		$out.='<div id="map"></div>';
		$out.='</div>';
		$this->show($this->setWizardPage($out));
	}
	function getMap(){
		$out='';
		$out.='<fieldset id="fieldset-maps-view">';
		$out.='<legend>Pozitia pe Harta:</legend>';
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->currentproperty->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->currentproperty->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->currentproperty->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->currentproperty->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->currentproperty->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->currentproperty->lng.'"/>';
		$out.='<div id="map"></div>';
		$out.='</fieldset>';
		return $out;
	}
	function setFiles(){
		$out='';
		$out.='<fieldset id="fieldset-files">';
		$out.='<legend>Imagini:</legend>';
		$out.='<div id="msg" class="form_row">';
		$out.='Atentie! Doar fisiere (jpg,jpeg,gif,png) pina la 1MB altfel fisierele vor fi filtrare.';	
		$out.='</div>';
		$out.='<div id="filestable" class="form_row">'; 	  
		$out.=$this->getFiles($this->currentfiles);
		$out.='</div>';
		$out.='<div id="filestable" class="form_row" style="text-align: right;">'; 		
		$out.='<input name="back" type="button" class="button" value="Upload" onclick="javascript:WizardUploadButtonOnClick();">';
		$out.='</div>';		
		$out.='</fieldset>';
		$this->show($this->setWizardPage($out));	 
	}
	function getFiles($files){
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
	function getFilesView(){
		$out='';
		$out.='<fieldset id="fieldset-files-view">';
		$out.='<legend>Imagini:</legend>';
		//$files_result=$files->GetByImobilId($imobil_id);
		//$files_output="";
		//while($file = mysql_fetch_object($files_result)){
		if ((sizeof($this->currentfiles)!=0)){
		    for ($i = 0; $i <= 4; $i++) {
				//$output.="<tr><td>Imaginea ".($i+1).":<input type=\"hidden\" id=\"fileid[]\" name=\"fileid[]\" value=\"$i\"><input type=\"hidden\" id=\"status[]\" name=\"status[]\" value=\"1\"></td><td> [".$files[$i]->filename."] <a onclick=\"ReplaceRow(this.parentNode.parentNode.rowIndex)\">sterge</a></td></tr>";
				if (isset($this->currentfiles[$i]->filepath)){
					$out.="<a href=\"".Config::$filespath."/".$this->currentfiles[$i]->filepath."\" rel=\"lightbox[list]\"><img src=\"".Config::$filespath."/t".$this->currentfiles[$i]->filepath."\"></a>&nbsp";
				} else {
					$out.="<a href=\"".Config::$mainsite."/common/img/no_image_100x100.jpg\" ><img src=\"".Config::$mainsite."/common/img/no_image_100x100.jpg\"></a>&nbsp";
				}
			}
		}		

		
		//}
		//if ($files_output==""){
		//	$files_output="Nu exista";
		//}
		$out.='</fieldset>';
		return $out;	
	}
	function setContacts(){
		$out='';

		$out.='<fieldset>';
		$out.='<legend>Contacte:</legend>';		
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
		$out.='</table>';	
		$out.='</fieldset>'; 
		
		$out.='<fieldset>';
		$out.='<legend>Note la Contacte:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td style="width: 100%;"><textarea id="notecontact" name="notecontact">'.$this->currentcontact->note.'</textarea></td>';
		$out.='</tr>'; 			
		$out.='</table>'; 			
		$out.='</fieldset>'; 

		$out.='<fieldset>';
		$out.='<legend>Valideaza:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 30%;">Introdu codul din imagine:</td>';
		$out.='<td style="width: 70%;"><input type="text" id="validationcode" name="validationcode"><img src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"> ! Obligator</td>';
		$out.='</tr>';
		$out.='</table>';
		$out.='<div class="error">';
		$out.=$this->errormessage;
		$out.='</div>';		
		$out.='</fieldset>';
		/*
		$out.='<fieldset id="fieldset-contacts">';
		$out.='<legend>Contacte:</legend>';
		$out.='<div class="form_row">';
		$out.='<label>Nume:</label>';
		$out.='<input id="name" type="text" name="name" class="input" value="'.$this->currentcontact->name.'">';			
		$out.='</div>';    
		$out.='<div class="form_row">';
		$out.='<label>Url:</label>';
		$out.='<input type="text" name="url" class="input" value="'.$this->currentcontact->url.'">';	
		$out.='</div>';	    				
		$out.='<div class="form_row">';
		$out.='<label>Fix:</label>';
		$out.='<input type="text" name="phone" class="input" value="'.$this->currentcontact->phone.'">';		
		$out.='</div>';   		    				
		$out.='<div class="form_row">';
		$out.='<label>Mobil:</label>';
		$out.='<input type="text" name="mobile" class="input" value="'.$this->currentcontact->mobile.'">';		
		$out.='</div>';    
		$out.='<div class="form_row">';
		$out.='<label>Email:</label>';
		$out.='<input id="email" type="text" name="email" class="input" value="'.$this->currentcontact->email.'">';		
		$out.='</div>';
		//$out.='</fieldset>'; 
		//$out.='<fieldset id="fieldset-contacts-notes">';
		//$out.='<legend>Note:</legend>';
		$out.='<div class="form_row">';
		$out.='<label>Alte detalii:</label>';
		$out.='<textarea id="notecontact" name="notecontact" class="text">'.$this->currentcontact->note.'</textarea>';			
		$out.='</div>';  
		$out.='</fieldset>'; 
	
		$out.='<fieldset id="fieldset-contacts-validator">';
		$out.='<legend>Valideaza:</legend>';
		$out.='<div class="form_row">';
		$out.='<label>Introdu codul din imagine:</label>';	
		$out.='<input id="validationcode" type="text" name="validationcode" class="input"><img src="validationimage.php" style="vertical-align: middle"> ! Obligator';	
		$out.='</div>';
		$out.='<div class="error">';
		$out.=$this->errormessage;
		$out.='</div>';
		$out.='</fieldset>';
		*/
		$this->show($this->setWizardPage($out));	 
	}	

	function getContacts(){
		$out='';
		$out.='<fieldset id="fieldset-address-view">';
		$out.='<legend>Contacte:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Nume</td><td class="property-value" style="width: 25%;">'.$this->currentcontact->name.'</td<td class="property-name" style="width: 25%;">Fix:</td><td class="property-value" style="width: 25%;">'.$this->currentcontact->phone.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Url:</td><td class="property-value" style="width: 25%;">'.$this->currentcontact->url.'</td<td class="property-name" style="width: 25%;">Mobil:</td><td class="property-value" style="width: 25%;">'.$this->currentcontact->mobile.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Email:</td><td class="property-value" style="width: 25%;">'.$this->currentcontact->email.'</td<td></td><td></td></tr>';	
		$out.='</table>'; 
		$out.='</fieldset>'; 		
		
		$out.='<fieldset id="fieldset-contacts-notes-view">';
		$out.='<legend>Note la Contacte:</legend>';
		$out.='<table class="property-table">';
		$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentcontact->note.'</td></tr>';
		$out.='</table>'; 			
		$out.='</fieldset>'; 		
			
	
/*	
		$out.='<fieldset id="fieldset-contacts-view">';
		$out.='<legend>Contacte:</legend>';
		$out.='<dl>';
		$out.='<dt>Nume:</dt>';
		$out.='<dd>'.$this->currentcontact->name.'</dd>';
		$out.='<dt>Url:</dt>';
		$out.='<dd>'.$this->currentcontact->url.'</dd>';
		$out.='<dt>Fix:</dt>';
		$out.='<dd>'.$this->currentcontact->fix.'d</dd>';
		$out.='<dt>Mobil:</dt>';
		$out.='<dd>'.$this->currentcontact->mobile.'</dd>';
		$out.='<dt>Email:</dt>';
		$out.='<dd>'.$this->currentcontact->email.'</dd>';
		$out.='</dl>';
		$out.='</fieldset>'; 
		$out.='<fieldset id="fieldset-contacts-notes-view">';
		$out.='<legend>Note la Contacte:</legend>';
		$out.='<dl>';
		$out.='<dd>'.$this->currentcontact->note.'</dd>';
		$out.='</dl>';
		$out.='</fieldset>'; 
*/
		return $out;	 
	}	
	

	function getScopDropDown($scopid){
		$s=new Scop;
		$ss=$s->getAll("id in (1,3)","`order`");
		$out="<select id=\"scop_id\" name=\"scop_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnDropDownChange()\">";

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
		$out="<select id=\"tipimobil_id\" name=\"tipimobil_id\" class=\"select\" size=\"1\">";

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
