<?php
require_once(__DIR__ . '/../main/loader.php');
 
class ChiriePropertyWebPage extends PropertyWebPage {
	public $mod="view";
	public $step=1;
	public $steps=6;
	public $step_title="";
	public $currentproperty;
	public $currentcontact;
	public $currentfiles;
	public $errormessage="";
	function __construct(){
		parent::__construct();
		$this->setCSS("styles/property.css");
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

			$this->create();
			User::delCurrentProperty();
			User::delCurrentContact();
			User::delCurrentFiles();
		} else {
			$this->redirect($this->getUrl("index.php"));
		}		
	}

	function show($out=''){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="center" class="container center" style="width:798px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right" class="container right" style="width:198px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}

	function actionDefault(){
		$this->setTitle($this->currentproperty->getShortDescription());
		$this->setKeywords($this->currentproperty->getKeywords());
		$this->setCenterContainer($this->getGroupBoxWizard($this->getPropertyTitle(),$this->getPropertyPage(""),""));	
		$this->setLeftContainer($this->getGroupBoxHtml("<h3>Detalii:</h3>",$this->getPropertyHeader(),""));	
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
		$out='<div style="text-align:center;">';
		$out.=$this->currentproperty->getShortDescription();
		$out.='</div>';
		return $out;		
	}
	function getSystemDetails(){
		$out='<div>';		
		$out.='<div id="property-view-dateq" style="float:left">';
		$out.='Data publicarii: '.$this->currentproperty->getData();
		$out.='</div>';	
		$out.='<div id="property-view-dateq" style="float:right">';
		$out.='Vizualizari: '.$this->currentproperty->contor;
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';						
		$out.='</div>';
		return $this->getGroupBoxHtml("<h3>Alte date:</h3>",$out,"");	 
	}	
	function getPropertyPage($html){
		$out=' <div id="form_container" class="form_container">';
		$out.='<div id="form" class="form">';
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
	function getOfertaChirieApartament(){
		$out='';
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
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		$out.='</table>'; 			

		return $out;
	}
	function getOfertaChirieCasaVila(){
		$out='';
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
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		$out.='</table>'; 			
			

		return $out;
	}
	function getOfertaChirieComercial(){
		$out='';
		$out.='<table class="property-table" align="center" style="width: 86%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Comercial:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td><td class="property-name" style="width: 25%;">Electricitate:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getElectricitate().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td><td class="property-name" style="width: 25%;">Apeduct:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getApeduct().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata lotului aferent:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td><td class="property-name" style="width: 25%;">Canalizare:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getCanalizare().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td><td class="property-name" style="width: 25%;">Telefon:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getTelefon().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Negociabil::</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getNgociabil().'</td><td class="property-name" style="width: 25%;">Gaz:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getGaz().'</td></tr>';	
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		$out.='</table>'; 			
		
 
		return $out;
	}
	function getCerereChirieApartament(){
		$out='';
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Apartament preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		$out.='</table>';	
		
		return $out;
	}	

	function getCerereChirieCasaVila(){
		$out='';
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Casa/Vila preferata:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata preferata(m2):</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaTotala().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		$out.='</table>';		
		
		return $out;
	}	

	function getCerereChirieComercial(){
		$out='';
		$out.='<table class="property-table" align="center" style="width: 50%;">';
		$out.='<tr><td class="property-name" style="width: 25%;">Tip Comercial preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSubTipImobil()->name.'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Suprafata preferata:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getAriaLot().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Pret preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getPret().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Municipiul/Raionul preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getRaion()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Oras/Sat preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getLocation()->getFullName().'</td></tr>';
		$out.='<tr><td class="property-name" style="width: 25%;">Sector preferat:</td><td class="property-value" style="width: 25%;">'.$this->currentproperty->getSector()->name.'</td></tr>';
		$out.='<tr>';
		$out.='<td class="property-name" style="height:25px;">Note:</td>';		
		$out.='<td class="property-value" style="height:25px;" colspan="3">'.$this->currentproperty->note.'</td>';
		$out.='</tr>';
		$out.='</table>';
		
		return $out;
	}
	function getPropertyAdress(){
		$out='';
		if ($this->currentproperty->getScop()->id==2){		
			return $this->getGroupBoxHtml("<h3>Adresa:</h3>",$this->getAdress($this->currentproperty),"");
		} else {
			return $out;
		}
	}
	function getPropertyMap(){
		$out='';
		if ($this->currentproperty->getScop()->id==2){	
			return $this->getGroupBoxH3("Harta:",$this->getOsm($this->currentproperty));
		} else {
			return $out;
		}		
	}	
	function getVideo(){
		$out='';
		if ($this->currentproperty->getScop()->id==2){	
			if ($this->currentproperty->getYouTubeId()!=""){ 
				$out.='<div style="text-align: center;">';
				$out.='<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$this->currentproperty->getYouTubeId().'" frameborder="0" allowfullscreen></iframe>';
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
		if ($this->currentproperty->getScop()->id==2){		
			return $this->getGroupBoxH3("Imagini:",$this->getFiles());	
		} else {
			return $out;
		}			
	}
}
$n=new ChiriePropertyWebPage();
?>
