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
		//$this->setJavascript("js/scripts.js");

		$t="ANUNŢURI IMOBILIARE DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);


		if (($this->id!=0)){
			$p=new Property();
			if ($p->loadById($this->id)){
				$p->count();
			}
						
			$this->setDescription($p->getShortDescription());

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

			//$this->getPropertyPage("");
			
			//$this->getOfertaImobilApartament();
		} else {
			$this->redirect($this->getUrl("index.php"));
		}
		$this->create();
		User::delCurrentProperty();
		User::delCurrentContact();
		User::delCurrentFiles();
			
	}
	function show($out=''){
		$out="";
		$out.='<div id="container">';
		//$out.='<div id="left" class="container left" style="width:198px;">';
		//$out.=$this->getLeftContainer();
		//$out.='</div>';		
		//$out.='<div id="center" class="container center" style="width:798px;">';
		$out.='<div id="center" class="container center" style="width:800px;float: none;margin: 0 auto;padding:20px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		//$out.='<div id="right" class="container right" style="width:800px;">';
		//$out.=$this->getCenterContainer();
		//$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}		
	function actionDefault(){
		//$this->setBodyTag('<body onload="WizardOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));
		//$this->setJavascript("js/prototype.js");
		//$this->setJavascript("js/scriptaculous.js?load=effects,builder");
		//$this->setJavascript("js/lightbox.js");
		//$this->setCSS("styles/lightbox.css");


		$this->setTitle($this->currentproperty->getShortDescription());
		$this->setKeywords($this->currentproperty->getKeywords());
				
		$this->setCenterContainer($this->getGroupBoxWizard($this->getPropertyTitle(),$this->getPropertyPage(""),""));	
		$this->setLeftContainer($this->getGroupBoxHtml("<h3>Detalii:</h3>",$this->getPropertyHeader(),""));	
		$this->setRightContainer("test2");	
		$this->show();
	}			
	function getPropertyHeader(){
		$out='<div id="propertyheader" class="groupbox" style="margin-bottom:2px;padding:10px;">';
		$out.='<div style="float:left;"><a href="add.php" class="link_button">Adauga Anunt</a></div>';
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
	function getPropertyTitle(){
		//$out='<div id="propertytitle" class="groupbox" style="margin-bottom:2px;padding:10px;">';

		$out='<div style="text-align:center;">';
		$out.=$this->currentproperty->getShortDescription();
		$out.='</div>';
		//$out.='<div style="text-align:right;">';
		//$out.='Data publicarii:'.$this->currentproperty->getData();
		//$out.='</div>';	
		//$out.='</div>';	
		return $out;	
		//return $this->getGroupBoxHtml("",$out,"<h3>Detalii1:</h3>");		
	}
	function getSystemDetails(){
		$out='<div>';		
		$out.='<div id="property-view-dateq" style="float:left">';
		$out.='Data publicarii: '.$this->currentproperty->getData();
		$out.='</div>';	
		$out.='<div id="property-view-dateq" style="float:right">';
		$out.='Vizualizari: '.$this->currentproperty->contor;
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';						
		$out.='</div>';
		return $this->getGroupBoxHtml("<h3>Alte date:</h3>",$out,"");	 
	}	
	function getPropertyPage($html){
		
		$out=' <div id="form_container" class="form_container">';
		//$out.=$this->getPropertyHeader();
		$out.='<div id="form" class="form">';				
		//$out.='<div id="property-view-title">';
		//$out.=$this->currentproperty->getShortDescription();
		//$out.='</div>';
		///$out.=$this->getPropertyTitle();
		//$out.='<div id="property-view-date" style="text-align:right">';
		//$out.='Data publicarii:'.$this->currentproperty->getData();
		//$out.='</div>';				
	
		$out.=$this->getPropertyDetails();
		$out.=$this->getPropertyFiles();		
		$out.=$this->getPropertyAdress();
		$out.=$this->getPropertyMap();
		$out.=$this->getVideo();
		$out.=$this->getPropertyContacts();
		$out.=$this->getSystemDetails();
		$out.='</div>';
		$out.=$this->getPropertyHeader();
		$out.='</div>';
		return $out;	 
	}
	function getPropertyDetails(){
		$out="";
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
			//$out.=$this->getAdress();
			//$out.=$this->getMap();
			//$out.=$this->getFilesView();
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
			//$out.=$this->getAdress();
			//$out.=$this->getMap();
			//$out.=$this->getGroupBoxHtml("<h3>Pozitia pe Harta:</h3>",$this->getMap(),"");
						
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
		return $this->getGroupBoxHtml("<h3>Detalii:</h3>",$out,"");			
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
	function getOfertaImobilApartament(){
		$out='';
		
		//$out.='<fieldset id="fieldset-details-view">';
		//$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Apartament:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Anul constructiei:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAn().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata totala(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Model:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getModel()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata locuibila(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLocuibila().'</td><td class="property-name" style="width: 25%;">Tip Constructie:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTipConstructie()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Etaj/Etaje:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getEtaj().'</td><td class="property-name" style="width: 25%;">Stare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getStare()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Balcoane:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getBalcoane().'</td><td class="property-name" style="width: 25%;">Garaj:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaraj().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Subsol:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubsol().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Incalzire Autonoma:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getIncalzire().'</td></tr>';
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';	
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	
		//$out.='</table>'; 
		//$out.='</fieldset>'; 		
		
		//$out.='<fieldset id="fieldset-details-notes-view">';
		//$out.='<legend>Note la Detalii:</legend>';
		//$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>'; 
		$out.='</table>';	
		//$out.='</fieldset>'; 	

		return $out;
	}
	function getOfertaImobilCasaVila(){
		$out='';
		
		//$out.='<fieldset id="fieldset-details-view">';
		//$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Casa/Vila:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Anul constructiei:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAn().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata totala(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Tip Constructie:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTipConstructie()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata lotului aferent:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td><td class="property-name" style="width: 25%;">Stare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getStare()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Electricitate:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getElectricitate().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Apeduct:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getApeduct().'</td></tr>';
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Canalizare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getCanalizare().'</td></tr>';	
		$out.='<tr><td class="property-name" style="width: 25%;">Garaj:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaraj().'</td><td class="property-name" style="width: 25%;">Incalzire Autonoma:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getIncalzire().'</td></tr>';	
		$out.='<tr><td class="property-name" style="width: 25%;">Subsol:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubsol().'</td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';	
		$out.='<tr><td></td><td></td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	

		//$out.='</table>'; 
		//$out.='</fieldset>'; 		
		
		//$out.='<fieldset id="fieldset-details-notes-view">';
		//$out.='<legend>Note la Detalii:</legend>';
		//$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		$out.='</table>'; 			
		//$out.='</fieldset>'; 				

		return $out;
	}
	function getOfertaImobilTeren(){
		$out='';
		
		//$out.='<fieldset id="fieldset-details-view">';
		//$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Teren:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Electricitate:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getElectricitate().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td><td class="property-name" style="width: 25%;">Apeduct:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getApeduct().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Bonitate(bal/ha):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getBonitate().'</td><td class="property-name" style="width: 25%;">Canalizare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getCanalizare().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret negociabil:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	
		//$out.='</table>'; 
		//$out.='</fieldset>'; 		
		
		//$out.='<fieldset id="fieldset-details-notes-view">';
		//$out.='<legend>Note la Detalii:</legend>';
		//$out.='<table class="property-table">';
		//$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';

		$out.='</table>'; 			
		//$out.='</fieldset>'; 		
 
		return $out;
	}
	function getOfertaImobilComercial(){
		$out='';
		
		//$out.='<fieldset id="fieldset-details-view">';
		//$out.='<legend>Detalii:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Comercial:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Electricitate:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getElectricitate().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Apeduct:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getApeduct().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata lotului aferent:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td><td class="property-name" style="width: 25%;">Canalizare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getCanalizare().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil::</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	
		//$out.='</table>'; 
		//$out.='</fieldset>'; 		
		
		//$out.='<fieldset id="fieldset-details-notes-view">';
		//$out.='<legend>Note la Detalii:</legend>';
		//$out.='<table class="property-table">';
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		
		//$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		//$out.='</fieldset>'; 		
 
		return $out;
	}

	function getCerereImobilApartament(){
		$out='';
		
		//$out.='<fieldset>';
		//$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr><td class="property-name" style="width: 50%;">Apartament preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Suprafata preferata(m2):</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getAriaTotala().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Etajul preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getEtaj().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Pret preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getPret().'</td></tr>';
		//$out.='</table>'; 
		//$out.='</fieldset>';	

		//$out.='<fieldset>';
		//$out.='<legend>Adresa Preferata:</legend>';		
		//$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 50%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		//$out.='</table>'; 
		//$out.='</fieldset>';	
		
		//$out.='<fieldset>';
		//$out.='<legend>Note:</legend>';
		//$out.='<table class="property-table">';
		$out.='<tr><td class="property-name" style="width: 50%;">Note:</td><td class="property-value" style="width: 25%;height:65px;" colspan="3">'.$this->currentproperty->note.'</td></tr>';
		$out.='</table>'; 			
		//$out.='</fieldset>'; 	
		
		return $out;
	}	

	function getCerereImobilCasaVila(){
		$out='';
		
		//$out.='<fieldset>';
		//$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr><td class="property-name" style="width: 50%;">Casa preferata:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Suprafata preferata(m2):</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getAriaTotala().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Pret preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getPret().'</td></tr>';
		//$out.='</table>'; 
		//$out.='</fieldset>';	

		//$out.='<fieldset>';
		//$out.='<legend>Adresa Preferata:</legend>';		
		//$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 50%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Oras/Sat preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Sector preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		//$out.='</table>'; 
		//$out.='</fieldset>';	
		
		//$out.='<fieldset>';
		//$out.='<legend>Note:</legend>';
		//$out.='<table class="property-table">';
		
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 50%;">Note:</td>';
		$out.='<td class="property-value" style="height:65px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		$out.='</table>'; 			
		//$out.='</fieldset>'; 	
		
		return $out;
	}	

	function getCerereImobilTeren(){
		$out='';
		
		//$out.='<fieldset>';
		//$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr><td class="property-name" style="width: 50%;">Tip teren preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Suprafata preferata:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getAriaLot().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Pret preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getPret().'</td></tr>';
		//$out.='</table>'; 
		//$out.='</fieldset>';	

		//$out.='<fieldset>';
		//.='<legend>Adresa Preferata:</legend>';		
		//$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 50%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Oras/Sat preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Sector preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		//$out.='</table>'; 
		//$out.='</fieldset>';	
		
		//$out.='<fieldset>';
		//$out.='<legend>Note:</legend>';
		//$out.='<table class="property-table">';
		//$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 50%;">Note:</td>';
		$out.='<td class="property-value" style="height:65px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';		
		$out.='</table>'; 			
		//$out.='</fieldset>'; 	
		
		return $out;
	}	


	function getCerereImobilComercial(){
		$out='';
		
		//$out.='<fieldset>';
		//$out.='<legend>Detalii Preferate:</legend>';		
		$out.='<table class="property-table" align="center" style="width: 100%;">';
		$out.='<tr><td class="property-name" style="width: 50%;">Tip Comercial preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Suprafata preferata:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getAriaLot().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Pret preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getPret().'</td></tr>';
		//$out.='</table>'; 
		//$out.='</fieldset>';	

		//$out.='<fieldset>';
		//$out.='<legend>Adresa Preferata:</legend>';		
		//$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 50%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Oras/Sat preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 50%;">Sector preferat:</td><td class="property-value" style="width: 50%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		//$out.='</table>'; 
		//$out.='</fieldset>';	
		
		//$out.='<fieldset>';
		//$out.='<legend>Note:</legend>';
		//$out.='<table class="property-table">';
		//$out.='<tr><td class="property-value" style="height:65px;">'.$this->currentproperty->note.'</td></tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="width: 50%;">Note:</td>';
		$out.='<td class="property-value" style="height:65px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		$out.='</table>'; 			
		//$out.='</fieldset>'; 	
		
		return $out;
	}		


	function getPropertyAdress(){
		$out='';
		if ($this->currentproperty->getScop()->id==1){		
			return $this->getGroupBoxHtml("<h3>Adresa:</h3>",$this->getAdress($this->currentproperty),"");
		} else {
			return $out;
		}
	}

	function getPropertyMap(){
		$out='';
		if ($this->currentproperty->getScop()->id==1){	
			return $this->getGroupBoxH3("Harta:",$this->getMap($this->currentproperty));
		} else {
			return $out;
		}		
	}
	function getVideo(){
		$out='';
		if ($this->currentproperty->getScop()->id==1){	
			if ($this->currentproperty->getYouTubeId()!=""){ 
				$out.='<div style="text-align: center;">';
				$out.='<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$this->currentproperty->getYouTubeId().'" frameborder="0" allowfullscreen></iframe>';
				$out.='</div>';
			} else {
				$out.='<div style="text-align: center;">';
				$out.='Video nu exista.';
				$out.='</div>';
				
			}
			return $this->getGroupBoxHtml("<h3>Video:</h3>",$out,"");
		} else {
			return $out;
		}		
	}	
	function getPropertyFiles(){
		$out='';
		if ($this->currentproperty->getScop()->id==1){		
			return $this->getGroupBoxH3("Imagini:",$this->getFiles());	
		} else {
			return $out;
		}			
	}
	function getFilesView1(){
		$out='';
		if ($this->currentproperty->getScop()->id==1){		
			//$out.='<fieldset id="fieldset-files-view">';
			//$out.='<legend>Imagini:</legend>';
			//$files_result=$files->GetByImobilId($imobil_id);
			//$files_output="";
			//while($file = mysql_fetch_object($files_result)){
			$out.='<table class="property-table" align="center" style="width: 100%;">';
			$out.='<tr><td style="text-align:center;">';
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
			$out.='</td></tr>';
			$out.='</table>';
			
			//}
			//if ($files_output==""){
			//	$files_output="Nu exista";
			//}
			//$out.='</fieldset>';
			return $this->getGroupBoxHtml("<h3>Imagini:</h3>",$out,"");	
		} else {
			return $out;
		}		
	}

	function getPropertyContacts(){	
		return $this->getGroupBoxH3("Contacte:",$this->getContacts($this->currentcontact));
	}	
}
$n=new PropertyWebPage();
?>
