<?php
require_once(__DIR__ . '/../main/loader.php');

class IndexLocationsWebPage extends MainWebPage {

	function __construct(){
		parent::__construct();
		$this->setLogoTitle("LOCALITATI DIN REPUBLICA MOLDOVA");		
		$this->create();		
	}
	function actionDefault(){
		$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));	
		$this->setCenterContainer($this->getGroupBoxH2($this->getConstants("IndexLocationsWebPageRaioaneTitle"),Raion::getRaionsList($this)));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
		$this->setRightContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));
		$this->show();
	}
	function actionViewRaion(){
		if (isset($this->id)){
			$r=new Raion();
			$r->loadById($this->id);
			$this->raion=$r;		
		} else{
			$r=new Raion();
			$this->raion=$r;
			$this->raion->loadById(Raion::getTopFirstRaion()->id);
			$this->id=$this->raion->id;
		}
		$r->count();
		$this->setTitle($this->getConstants("IndexLocationsWebPageTitle").' '.$this->raion->getFullNameDescription());	
		$this->setCenterContainer($this->getRaion());
		$this->setCenterContainer($this->getRaionPopulation());
		$this->setCenterContainer($this->getRaionPhotos());
		//$this->setCenterContainer($this->getPanoramioFotos($r));
		$this->setCenterContainer($this->getImobilList($this->raion->id, 0));
		//$this->setCenterContainer($this->getRaionNews());
		$c='<a name="11"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'r',$r->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenuRaion()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
		$this->setLeftContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->show();
	}
	function actionViewLocalitate(){

		if (isset($this->id)){
			$l=new Location();
			$l->loadById($this->id);
			$this->location=$l;
			$r=new Raion();
			$r->loadById($this->location->raion_id);
			$this->raion=$r;					
		} else{
			$r=new Raion();
			$this->raion=$r;
			$this->raion->loadById(Raion::getTopFirstRaion()->id);
			$l=new Location();
			$l->loadById(Location::getTopFirstLocationByRaionId($this->raion->id)->id);
			$this->location=$l;
			$this->id=$this->location->id;
			Logger::setLogs(Location::getTopFirstLocationByRaionId($this->raion->id));
			Logger::setLogs($this->raion);
		}
		Logger::setLogs($this->location);		
		$l->count();
		$this->setTitle($this->getConstants("IndexLocationsWebPageTitle").' '.$this->location->getFullNameDescription().' din '.$this->location->getRaion()->getFullNameDescription());
		$this->setLogoTitle(strtoupper($this->location->getFullNameDescription()));
		$this->setCenterContainer($this->getLocalitate());
		$this->setCenterContainer($this->getPopulation());
		$this->setCenterContainer($this->getPopulationInTime());
		$this->setCenterContainer($this->showElectoralPreferences());
		$this->setCenterContainer($this->showAlegeriPresidentiale161030Image());		
		//$this->setCenterContainer($this->showAlegeri141130Image());	
		$this->setCenterContainer($this->getLocalitatiInJur());
		$this->setCenterContainer($this->getLocalitatiDistance());	
		$this->setCenterContainer($this->getPhotos());
		//$this->setCenterContainer($this->getPanoramioFotos($l));
		$this->setCenterContainer($this->getLocalitatiCuAcelasNume());	
		$this->setCenterContainer($this->getNewsTitles());		
		//$this->setCenterContainer($this->getNews());
		$this->setCenterContainer($this->getImobilList($this->raion->id, $this->location->id));		
		$this->setCenterContainer($this->getTopAgentiEconomici($l));
		$this->setCenterContainer($this->getTopActivitatiEconomiceL($l));	
		$this->setCenterContainer($this->getTopActivitatiEconomiceNL($l));		
		$this->setCenterContainer($this->getContacts($l));
		$this->setCenterContainer($this->getDictionar($l));
		$this->setCenterContainer($this->getTopNames($l));
		$this->setCenterContainer($this->getSystemDetails($l));		
		
		$c='<a name="16"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'l',$l->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenuLocalitate()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
		$this->setLeftContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Referinte Utile",$this->getLeftMenu()));		
		$this->show();
	}				

	function actionViewOrase($html="LocationsWebPageHTML"){
		$this->setTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
		$this->setRightContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));				
		$this->setCenterContainer($this->getGroupBoxH2($this->getConstants("IndexLocationsWebPageOraseTitle"),Location::getListaOrase($this)));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopSusLocalitati($html="LocationsWebPageHTML"){
		$t="Lista a 50 cele mai sus amplasate localitati din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		//$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopSusLocalitati()));
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopSusJosLocalitati($this, 'sus')));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopJosLocalitati($html="LocationsWebPageHTML"){
		$t="Lista a 50 cele mai jos amplasate localitati din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopSusJosLocalitati($this, 'jos')));
		$this->show();
	}
	function actionViewTopUpPopLocalitati($html="LocationsWebPageHTML"){
		$t="Lista a 50 cele mai populate localitati din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));			
		$this->setRightContainer($this->getGroupBoxH3("Despre Date",$this->getRecensamintInfo()));	
		//$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopUpPopLocalitati()));
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopUpDownPopLocalitati($this,'up')));
		$this->show();
	}
	function actionViewTopDownPopLocalitati($html="LocationsWebPageHTML"){
		$t="Lista a 50 cele mai putin populate localitati din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setRightContainer($this->getGroupBoxH3("Despre Date",$this->getRecensamintInfo()));	
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopUpDownPopLocalitati($this,'down')));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiUcraineni($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Ucraineni din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setRightContainer($this->getGroupBoxH3("Despre Date",$this->getRecensamintInfo()));	
		//$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopLocalitatiUcraineni($this)));
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopLocalitatiNationality($this, "ucraineni")));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiRusi($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Rusi din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setRightContainer($this->getGroupBoxH3("Despre Date",$this->getRecensamintInfo()));	
		//$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopLocalitatiRusi($this)));
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopLocalitatiNationality($this, "rusi")));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiGagauzi($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Gagauzi din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setRightContainer($this->getGroupBoxH3("Despre Date",$this->getRecensamintInfo()));	
		//$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiGagauzi()));
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopLocalitatiNationality($this, "gagauzi")));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiBulgari($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Bulgari din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setRightContainer($this->getGroupBoxH3("Despre Date",$this->getRecensamintInfo()));	
		//$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiBulgari()));
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopLocalitatiNationality($this, "bulgari")));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiEvrei($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Evrei din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setRightContainer($this->getGroupBoxH3("Despre Date",$this->getRecensamintInfo()));	
		//$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiEvrei()));
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopLocalitatiNationality($this, "evrei")));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiPolonezi($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Polonezi din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setRightContainer($this->getGroupBoxH3("Despre Date",$this->getRecensamintInfo()));	
		//$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiPolonezi()));
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopLocalitatiNationality($this, "polonezi")));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiTigani($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Romi/Tigani din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setRightContainer($this->getGroupBoxH3("Despre Date",$this->getRecensamintInfo()));	
		//$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiTigani()));
		$this->setCenterContainer($this->getGroupBoxH2($t,Location::getTopLocalitatiNationality($this, "tigani")));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}														
	function show($out=''){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="left" class="container left" style="width:198px;">';
		$out.=$this->getLeftContainer();
		$out.='</div>';		
		$out.='<div id="center" class="container center" style="width:600px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right" class="container right" style="width:198px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
		
	function getMenuRaion(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#2").'">Localitați în componenață</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#3").'">Harta</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#4").'">Populația</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#5").'">Imagini</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#9").'">Imobile</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#7").'">Stiri</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#11").'">Forum/Comentarii</a></li>';
		$out.='</ul>';
		return $out;
	}
	function getMenuLocalitate(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#2").'">Primăria</a></li>';	
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#3").'">Date geografice</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#4").'">Poziția pe Harta</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#5").'">Populația</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#6").'">Preferinte Electorale</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#7").'">Localitați in raza de 10 km</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#8").'">Imagini in raza de 10 km</a></li>';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#9").'">Știri</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#10").'">Imobile</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#11").'">Agenti Economici</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#12").'">Activitati Economice</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#13").'">Telefoane</a></li>';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#14").'">Nume de Familii</a></li>';				
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#15").'">Vizualizări</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#16").'">Forum/Comentarii</a></li>';
		
		$out.='</ul>';
		return $out;
	}		

	function getLeftMenu(){
		$out='<ul>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">'.$this->getConstants("IndexLocationsWebPageRaioaneTitle").'</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=vieworase").'" >'.$this->getConstants("IndexLocationsWebPageOraseTitle").'</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtopsuslocalitati").'" >Lista a 50 cele mai sus amplasate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtopjoslocalitati").'" >Lista a 50 cele mai jos amplasate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtopuppoplocalitati").'" >Lista a 50 cele mai populate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtopdownpoplocalitati").'" >Lista a 50 cele mai putin populate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtoplocalitatiucraineni").'" >Lista localitatilor populate cu Ucraineni din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtoplocalitatirusi").'" >Lista localitatilor populate cu Rusi in Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtoplocalitatigagauzi").'" >Lista localitatilor populate cu Gagauzi in Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtoplocalitatibulgari").'" >Lista localitatilor populate cu Bulgari in Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtoplocalitatievrei").'" >Lista localitatilor populate cu Evrei in Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtoplocalitatipolonezi").'" >Lista localitatilor populate cu Polonezi din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtoplocalitatitigani").'" >Lista localitatilor populate cu Romi/Tigani din Moldova</a></li>';	
		$out.='</ul>';
		return $out;		
	}
	function getRaion(){

		$out="";

		$ls=$this->raion->getLocations();
		
		$o1s='<a name="1"></a>'.$this->raion->getFullNameDescription();
		$rm=($this->raion->municipiu==1)?("Municipiului"):("Raionului");	
		$o1b=$this->raion->getFullNameDescription().' este situat la latitudinea '.$this->raion->getLatShort().' longitudinea '.$this->raion->getLngShort().' si altitudinea de '.$this->raion->elevation.' metri fata de nivelul marii. In componenta '.$rm.' intra '.count($ls).' localitati. ';
		if (($this->raion->id!=500)&&($this->raion->id!=700)){
			$o1b.='Conform recensamintului din anul 2004 popuplatia este de '.number_format($this->raion->getPopulation(), 0, ',', ' ').' locuitori.';
		}
		$out.=$this->getGroupBoxH3($o1s,$o1b);

		$o2s='<a name="2"></a>'.$this->getConstants("IndexLocationsWebPageRaionComponenta")." ".$this->raion->getFullNameDescription().':';	
		$o2b=Raion::getLocalitatiListByRaion($this, $this->raion->id);
		$out.=$this->getGroupBoxH3($o2s,$o2b);

		$o3s='<a name="3"></a>'.$this->getConstants("IndexLocationsWebPageRaionHarta")." ".$this->raion->getFullNameDescription();
		$this->raion->zoom=12;
		$this->raion->maptype=3;
		$this->raion->centerlat=$this->raion->lat;
		$this->raion->centerlng=$this->raion->lng;
		$o3b=$this->getMap($this->raion);
		$out.=$this->getGroupBoxH3($o3s,$o3b);

		return $out;
	}
	function getLocalitate(){
		$out="";
		$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->raion->id);
		$o1s='<a name="1"></a>'.$this->location->getFullNameDescription().' - Scurta descriere:';
		$o1b=$this->location->getFullNameDescription().' este o localitate in <a href='.$url.'>'.$this->raion->getFullNameDescription().'</a> situata la latitudinea '.$this->location->getLatShort().' longitudinea '.$this->location->getLngShort().' si altitudinea de '.$this->location->elevation.' metri fata de nivelul marii.';
		if ($this->location->parent_id==$this->location->raion_id){
			$o1b.=' Aceasta localitate este in administrarea <a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->location->raion_id).'">'.$this->location->getRaion()->getFullNameDescription().'</a>. ';	
		} else {
			$o1b.=' Aceasta localitate este in administrarea <a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->location->parent_id).'">'.$this->location->getParentLocation()->getFullName().'</a>. ';	
		}
		if ($this->location->p>0){
			$o1b.='Conform recensamintului din anul 2004 populatia este de '.number_format($this->location->p, 0, ',', ' ').' locuitori. ';
		}
		Logger::setLogs($this->location);
		$l1=new Location();
		$l1->loadById(101);
		$l2=new Location();
		$ls=$l2->getAll("raion_center=1 and raion_id=".$this->location->raion_id);		
		if ($this->location->id!=$ls[0]->id){
			$o1b.='Distanța directă pîna în '.$ls[0]->getFullName().' este de  '.$this->location->getDistanceToLocation($ls[0]->id).' km. ';
		}
		if ($this->location->id!=101){
			$o1b.='Distanța directă pîna în '.$l1->getFullName().' este de  '.$this->location->getDistanceToLocation($l1->id).' km. ';
		}
				
		$out.=$this->getGroupBoxH3($o1s,$o1b);
		$o2s='';
		$o2b='';
		$o2s.='<a name="2"></a>'.$this->location->getFullNameDescription().' - Primaria:';		
		$o2b.='Vezi <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$primariisite."/index.php","action=viewprimarie&id=".$this->location->getPrimarieLocation()->id).'">'.$this->location->getPrimarieLocation()->getPrimariaName().'</a> pentru mai multe informatii depsre primaria localitatii';
		$out.=$this->getGroupBoxH3($o2s,$o2b);		

		$o3s='<a name="3"></a>'.$this->location->getFullNameDescription()." - Date geografice";
		$o3b='Latitudinea: '.$this->location->lat.'<br>';
		$o3b.='Longitudinea: '.$this->location->lng.'<br>';
		$o3b.='Altitudinea: '.$this->location->elevation.' m.<br>';
		$out.=$this->getGroupBoxH3($o3s,$o3b);	
		
		$o4s='<a name="4"></a>'.$this->location->getFullNameDescription()." - Pozitia pe harta";
		$this->location->zoom=12;
		$this->location->maptype=3;
		$this->location->centerlat=$this->location->lat;
		$this->location->centerlng=$this->location->lng;
		$o4b=$this->getMap($this->location);
		$out.=$this->getGroupBoxH3($o4s,$o4b);	
		return $out;
	}
	function getLocalitatiInJur(){
		$out="";
		
		$ls=$this->location->getLocationsInRadius();
		if (count($ls)!=0){				
			$o2s='';
			$o2b='';
			$o2s.='<a name="7"></a>'.$this->location->getFullNameDescription().' - Localitati in raza de 10 km:';		
			$l=new Location();
			foreach($ls as $loc){
				$l->loadById($loc->id);
				$lurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$rurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->getRaion()->id);
				$o2b.=round($loc->distance).' km - distanța directă pînă la <a href="'.$lurl.'">'.$l->getFullNameDescription().'</a> din <a href="'.$rurl.'">'.$l->getRaion()->getFullNameDescription().'</a> - <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$distantesite."/index.php","from=".$this->location->id."&to=".$loc->id).'">vezi pe harta</a><br>';
			}
			$out.=$this->getGroupBoxH3($o2s,$o2b);		
		}			

		return $out;
	}
	function getLocalitatiDistance(){
		$out="";
		
		$ls=LocationDistance::getDistancesByLocation($this->location->id);
		if (count($ls)!=0){				
			$o2s='';
			$o2b='';
			$o2s.='<a name="5"></a>'.$this->location->getFullNameDescription().' - Distanțe:';		
			$l=new Location();
			foreach($ls as $loc){
				$l->loadById($loc->to_id);
				$furl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$loc->from_id);
				$turl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$loc->to_id);
				$o2b.='Distanța directă pîna în <a href="'.$turl.'">'.$l->getFullName().'</a> este de - '.round($loc->distance).' km <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$distantesite."/index.php","from=".$loc->from_id."&to=".$loc->to_id).'">vezi pe harta</a><br>';	
			}
			$out.=$this->getGroupBoxH3($o2s,$o2b);		
		}			

		return $out;
	}	
	function getLocalitatiCuAcelasNume(){
		$out="";
		
		$ls=$this->location->getLocationsWithSameName();
		if (count($ls)!=0){				
			$o2s='';
			$o2b='';			
			$o2s.='<a name="6"></a>'.$this->location->getFullNameDescription().' - Localitati cu aceleasi nume:';		
			$l=new Location();
			foreach($ls as $loc){
				$l->loadById($loc->id);
				$lurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$rurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->getRaion()->id);
				$o2b.='<a href="'.$lurl.'">'.$l->getFullNameDescription().'</a> din <a href="'.$rurl.'">'.$l->getRaion()->getFullNameDescription().'<br></a>';
				//$o2b.='<a href="?lc='.$l->id.'">'.$l->getFullName().'</a><br>';	
			}
			$out.=$this->getGroupBoxH3($o2s,$o2b);		
		}			

		return $out;
	}
	function getPopulation(){
		$out="";
		$o2s='';
		$o2b='';
		if (($this->location->p>0)){				
			$o2s='<a name="5"></a>'.$this->location->getFullNameDescription().' - Populatia (Conform recensamintului din anul 2004):';		
			$o2b='Locuitori - '.number_format($this->location->p, 0, ',', ' ').' din care:<br>';
			$o2b.='Barbati - '.number_format($this->location->m, 0, ',', ' ').'<br>';
			$o2b.='Femei - '.number_format($this->location->f, 0, ',', ' ').'<br>';	
			
			$p=new Population();
			$popnatall=$p->getAll("an=2004 and localitate_id=".$this->location->id);
			if  (count($popnatall)!=0){
				$popnat=$popnatall[0];
				$total=$popnat->total;
				$moldoveni=$popnat->moldoveni+$popnat->romani;
				$moldovenip=round($moldoveni*100/$total,2);
				$ucraineni=$popnat->ucraineni;
				$ucrainenip=round($ucraineni*100/$total,2);
				$rusi=$popnat->rusi;
				$rusip=round($rusi*100/$total,2);
				$gagauzi=$popnat->gagauzi;
				$gagauzip=round($gagauzi*100/$total,2);
				$bulgari=$popnat->bulgari;
				$bulgarip=round($bulgari*100/$total,2);
				$evrei=$popnat->evrei;
				$evreip=round($evrei*100/$total,2);
				$polonezi=$popnat->polonezi;
				$polonezip=round($polonezi*100/$total,2);
				$tigani=$popnat->tigani;
				$tiganip=round($tigani*100/$total,2);
				$altele=$popnat->altele;
				$altelep=round($altele*100/$total,2);
				
				$o2b.='<br>';
				$o2b.='Componenta pe nationalitati:';
				$o2b.='<br>';
				$o2b.=Population::getPopulationVeiwByLocalitate($this, $this->location->id);
				$chco='008000|224499|FF0000|FF9900|AA0033|7777CC|80C65A|AB8F3C|AD4949';
				$chd='t:'.$moldovenip.','.$ucrainenip.','.$rusip.','.$gagauzip.','.$bulgarip.','.$evreip.','.$polonezip.','.$tiganip.','.$altelep;
				$chdl='Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Romi/Tigani|Altele';
				$chl=$moldovenip.'%|'.$ucrainenip.'%|'.$rusip.'%|'.$gagauzip.'%|'.$bulgarip.'%|'.$evreip.'%|'.$polonezip.'%|'.$tiganip.'%|'.$altelep;
				$chtt='Nationalitati '.$this->location->getFullNameDescription();
				$o2b.='<br>';
				$o2b.='<div style="text-align:center">';
				$o2b.='<img src="https://chart.apis.google.com/chart?chf=bg,s,eeeeee&chs=580x260&cht=pc&chco='.$chco.'&chd='.$chd.'&chdl='.$chdl.'&chl='.$chl.'&chma=5,5,5,5&chtt='.$chtt.'" width="580" height="260" alt="'.$chtt.'" />';
				$o2b.='</div>';			
			}
		}
		$out.=$this->getGroupBoxH3($o2s,$o2b);					
		return $out;
	}
	
	function getRaionPopulation(){
		$out="";
		if (($this->raion->id!=500)&&($this->raion->id!=700)){
			$pn=new Population();
			$pno=$pn->getPopulationByRaion($this->raion->id);		
			$popnat=$pno[0];
			if (!$popnat->total==""){
				$total=$popnat->total;		
				$o2s='<a name="4"></a>'.$this->raion->getFullNameDescription().' - Populatia:';		
				$o2b='Conform recensamintului din anul 2004 populatia este de - '.number_format($total, 0, ',', ' ').' locuitori.<br>';
				$moldoveni=$popnat->moldoveni+$popnat->romani;
				$moldovenip=round($moldoveni*100/$total,2);
				$ucraineni=$popnat->ucraineni;
				$ucrainenip=round($ucraineni*100/$total,2);
				$rusi=$popnat->rusi;
				$rusip=round($rusi*100/$total,2);
				$gagauzi=$popnat->gagauzi;
				$gagauzip=round($gagauzi*100/$total,2);
				$bulgari=$popnat->bulgari;
				$bulgarip=round($bulgari*100/$total,2);
				$evrei=$popnat->evrei;
				$evreip=round($evrei*100/$total,2);
				$polonezi=$popnat->polonezi;
				$polonezip=round($polonezi*100/$total,2);
				$tigani=$popnat->tigani;
				$tiganip=round($tigani*100/$total,2);
				$altele=$popnat->altele;
				$altelep=round($altele*100/$total,2);
				
				$o2b.='<br>';
				$o2b.='Componenta pe nationalitati este:';
				$o2b.='<br>';
				$o2b.=Population::getPopulationVeiwByRaion($this,$this->raion->id);
				$chco='008000|224499|FF0000|FF9900|AA0033|7777CC|80C65A|AB8F3C|AD4949';
				$chd='t:'.$moldovenip.','.$ucrainenip.','.$rusip.','.$gagauzip.','.$bulgarip.','.$evreip.','.$polonezip.','.$tiganip.','.$altelep;
				$chdl='Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Romi/Tigani|Altele';
				$chl=$moldovenip.'%|'.$ucrainenip.'%|'.$rusip.'%|'.$gagauzip.'%|'.$bulgarip.'%|'.$evreip.'%|'.$polonezip.'%|'.$tiganip.'%|'.$altelep;
				$chtt='Nationalitati '.$this->raion->getFullNameDescription();
				$o2b.='<br>';
				$o2b.='<div style="text-align:center">';
				$o2b.='<img src="https://chart.apis.google.com/chart?chf=bg,s,eeeeee&chs=580x260&cht=pc&chco='.$chco.'&chd='.$chd.'&chdl='.$chdl.'&chl='.$chl.'&chma=5,5,5,5&chtt='.$chtt.'" width="580" height="260" alt="'.$chtt.'" />';
				$o2b.='</div>';
				$out.=$this->getGroupBoxH3($o2s,$o2b);
			}
		}					
		return $out;
	}	
	function getNews(){
		$out="";			
		$o2s='<a name="9"></a>'.$this->location->getFullNameDescription().' - Ultimele Stiri:';		
		$n=new News();
		$o2b=$n->getNewsByLocalitate($this->location->id);	
		$link=$this->getUrlWithSpecialCharsConverted(Config::$newssite."/index.php","action=viewlocalitate&localitate=".$this->location->id);
		$o2f=$this->getFooterWithLink($link);	
		return $this->getGroupBoxH3($o2s,$o2b,$o2f);
	}
	function getNewsTitles(){
		$out="";			
		$o2s='<a name="9"></a>'.$this->location->getFullNameDescription().' - Ultimele Titluri de Stiri:';		
		$fi=new FeedItem();
		$o2b=$fi->getNewsByLocalitate($this->location->id);	
		$link=$this->getUrlWithSpecialCharsConverted(Config::$feedssite."/index.php","action=localitate&id=".$this->location->id);
		$o2f=$this->getFooterWithLink($link);	
		return $this->getGroupBoxH3($o2s,$o2b,$o2f);
	}	
	function getRaionNews(){
		$out="";			
		$o2s='<a name="7"></a>'.$this->raion->getFullNameDescription().' - Ultimele Titluri de Stiri:';		
		$fi=new FeedItem();
		$o2b=$fi->getNewsByRaion($this->raion->id);	
		$link=$this->getUrlWithSpecialCharsConverted(Config::$feedssite."/index.php","action=raion&id=".$this->raion->id);
		$o2f=$this->getFooterWithLink($link);	
		return $this->getGroupBoxH3($o2s,$o2b,$o2f);
	}	
	function getPhotos(){
		$out="";			
		$o2s='<a name="8"></a>'.$this->location->getFullNameDescription().' - Foto/Imagini in raza de 10 km:';		
		$p=new Photo();		
		$o2b=$p->getPhotosByLatLng($this->location->lat,$this->location->lng);	
		return $this->getGroupBoxH3($o2s,$o2b);
	}
	function getRaionPhotos(){
		$out="";
		$o2s='<a name="5"></a>'.$this->raion->getFullNameDescription().' - Foto/Imagini in raza de 10 km:';
		$p=new Photo();
		$o2b=$p->getPhotosByLatLng($this->raion->lat,$this->raion->lng);
		return $this->getGroupBoxH3($o2s,$o2b);
	}								
	function getMap1($m){
		//$this->setBodyTag('<body onload="MapViewOnMapLoad()">');
		//$this->setJavascript("https://maps.google.com/maps/api/js?sensor=false");
		
		if ($m->lat==0){
			$m->maptype=3;
			$m->lat=0;
			$m->lng=0;			
		}	
		$out='<input id="centerlat" name="centerlat" type="hidden" value="'.$m->centerlat.'" />';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$m->centerlng.'" />';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$m->maptype.'" />';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$m->zoom.'" />';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$m->lat.'" />';
		$out.='<input name="lng" type="hidden" id="lng" readonly="true" class="inptdisabled" value="'.$m->lng.'" />';
		$out.='<div id="map" style="width: 100%;"></div>';
		return $out;
	}		
	function getGSMMap($m){
		if ($m->lat==0){
			$m->maptype=3;
			$m->lat=0;
			$m->lng=0;			
		}	
		$out='<input id="centerlat" name="centerlat" type="hidden" value="'.$m->centerlat.'" />';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$m->centerlng.'" />';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$m->maptype.'" />';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$m->zoom.'" />';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$m->lat.'" />';
		$out.='<input name="lng" type="hidden" id="lng" readonly="true" class="inptdisabled" value="'.$m->lng.'" />';
		$out.='<div id="gsmmap" style="width: 100%;"></div>';
		return $out;
	}
	function getSystemDetails($l){
		$out='<div>';
		$out.='<div id="property-view-dateq" style="float:left">';
		$out.='Data publicarii: '.$l->getData();
		$out.='</div>';
		$out.='<div id="property-view-dateq" style="float:right">';
		$out.='Vizualizari: '.$l->contor;
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $this->getGroupBoxH3('<a name="15"></a>Alte date:',$out);
	}
	function getImobilList($raionid,$locationid){
		$p=new PropertyView();
		$rs=PropertyView::getPropertiesSnapListView($this,0,0,0,0,0,$raionid,$locationid,0,1);
		return $this->getGroupBoxH3('<a name="10"></a>Cereri si Oferte de Imobile în raza de 10km:',$rs);
	}
	function getDictionar($l){
		$d=new Dictionar();
		$ds=$d->getAll("localitate_id=".$l->id);
		$out="";
		if (count($ds)){			
			$o1s='Descrierea din Dictionarul Geografic al Basarabiei an. 1904 de Zamfir Arbore';
			$o1b=$ds[0]->descriere;
			$o1f='<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$dictionarsite."/index.php").'">Mai mult despre dictionar vezi aici</a>';
			$out.=$this->getGroupBoxH3($o1s,$o1b,$o1f);
		}
		return $out;
	}
	function getTopNames($l){
		$n=new Nume();
		$ns=$n->getTopNamesByLocation($l->id);
		$out='';
		$out.='<div class="groupboxtable">';

		$table=new Table();
		$table->setDataSet($ns);
		$namelink=function($row){
			$url=$this->getUrlWithSpecialCharsConverted(Config::$numesite."/index.php","action=viewnume&id=".$row->id);					
			return '<a href="'.$url.'">'.$row->name.'</a>';
		};
		$table->addField(new TableField(1, "Nume de familie", "name", "center;width: 60%",$namelink));
		$table->addField(new TableField(2, "Numarul total de familii", "counter", "text-align: center;width: 30%",""));
		
		$out.=$table->show();
		
		
		$out.="</div>";		
		
		return $this->getGroupBoxH2("<a name=\"14\"></a>Top 50 Nume de Familii cele mai populare",$out);
	}
	function getContacts($l){
		$out='';		
		$out.='<div class="groupboxtable">';
		$out.=$l->getContacts();
		$out.='</div>';
		$out.='<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$telefoanesite."/index.php","action=viewprefixbylocation&id=".$this->id).'">Mai multe contacte vezi aici</a>';
	
		return $this->getGroupBoxH2("<a name=\"13\"></a>Contacte, Telefoane a celor mai importante institutii sociale",$out);
	}
	function getTopActivitatiEconomiceNL($l){
		
		$page=0;
		$rowsperpage=25;
		
		$p=new CoCaemNL();
		$ps=$p->getActivitatiByLocalitate($l->id,"3 desc",$page,$rowsperpage);
		$cnt=$p->getNumarActivitatiByLocalitate($l->id);
		
		$table=new Table();
		$table->setPagination(false);
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($rowsperpage);
		$table->setPage($page);		
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/companies.php','action=viewnotlicencedactivitycompaniesbylocalitate&lid='.$this->id.'&id='.$row->id).'">'.$row->denumire.'</a>';
		};
		
		$table->addField(new TableField(1, "Denumirea Activitatii", "denumire", "",$openlink));
		$table->addField(new TableField(2, "Total activitati", "contor", "text-align: center;",""));
		
		$out=$table->show();		
		
		$link=$this->getUrlWithSpecialCharsConverted(Config::$companiesite."/activities.php","action=viewnotlicencedactivitiesbylocation&id=".$this->location->id);
		$o2f=$this->getFooterWithLink($link);
		
		return $this->getGroupBoxH2("<a name=\"12\"></a>Top Activitati Economice Nelicentiate",$out,$o2f);
	}
	function getTopActivitatiEconomiceL($l){

		$page=0;
		$rowsperpage=25;
		
		$p=new CoCaemL();
		
		$ps=$p->getActivitatiByLocalitate($l->id,"3 desc",$page,$rowsperpage);
		$cnt=$p->getNumarActivitatiByLocalitate($l->id);
		
		$table=new Table();
		$table->setPagination(false);
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($rowsperpage);
		$table->setPage($page);
		
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/companies.php','action=viewlicencedactivitycompaniesbylocalitate&lid='.$this->id.'&id='.$row->id).'">'.$row->denumire.'</a>';
		};
		
		$table->addField(new TableField(1, "Denumirea Activitatii", "denumire", "",$openlink));
		$table->addField(new TableField(2, "Total activitati", "contor", "text-align: center;",""));
		
		$out=$table->show();
				
		$link=$this->getUrlWithSpecialCharsConverted(Config::$companiesite."/activities.php","action=viewactivitiesbylocation&id=".$this->location->id);
		$o2f=$this->getFooterWithLink($link);
	
		return $this->getGroupBoxH2("<a name=\"12\"></a>Top Activitati Economice Licentiate",$out,$o2f);
	}	
	function getTopAgentiEconomici($l){
		$page=0;
		$rowsperpage=25;
		$p=new CoCompany();
		$ps=$p->getCompaniesByLocalitate($l->id,"2 desc",$page,$rowsperpage);
		$cnt=$p->getNumarCompaniesByLocalitate($l->id);
		
		$table=new Table();
		$table->setPagination(false);
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($rowsperpage);
		$table->setPage($page);

		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/companies.php','action=viewcompany&lid='.$this->id.'&id='.$row->id).'">'.$row->nume_scurt.'</a>';
		};
		
		$table->addField(new TableField(1, "Denumirea Companiei", "nume_scurt", "",$openlink));
		$table->addField(new TableField(2, "Data inregistrarii", "data_inregistrarii", "text-align: center;",""));
		$statusvalue=function($row){
			if ($row->statutul=="") {
				return "Activa";
			} else {
				return "Lichidata";
			}
		};
		$table->addField(new TableField(3, "Statutul", "statutul", "text-align: center;",$statusvalue));
		$out=$table->show();	
		
		$link=$this->getUrlWithSpecialCharsConverted(Config::$companiesite."/activities.php","action=viewcompaniesbylocation&id=".$this->location->id);
		$o2f=$this->getFooterWithLink($link);
		
		return $this->getGroupBoxH2("<a name=\"11\"></a>Agenti Economici",$out,$o2f);
		
	}	
	
	function getPopulationInTime(){
		$out='';
		$o2s='';
		$o2b='';
		$p=new Population();
		$popnatall=$p->getAll("localitate_id=".$this->location->id,"an asc");
		if (count($popnatall)>1){
					
					$o2s=$this->location->getFullNameDescription().' - Evolutia numarului de locuitori in ultimii 100 ani:';
							
					$o2b.=Population::getPopulationInTimeVeiwByLocalitate($this, $this->location->id);					
					
					$o2b.='<div style="text-align:center">';
					
					$chxl0='';
					$chxl1='';
					$chxp0='';
					$chxp1='';
					$anmin=0;
					$anmax=0;					
					$popnatmin=0;
					$popnatmax=0;	
					$chd0='';
					$chd1='';
					foreach($popnatall as $popnat){
						$chxl0.=$popnat->an.'|';
						$chxp0.=','.$popnat->an;
						$chd0.=($chd0==''?'':',').$popnat->an;
						$chxl1.=$popnat->total.'+loc|';
						$chxp1.=','.$popnat->total;
						$chd1.=($chd1==''?'':',').$popnat->total;
						if ($anmin==0){
							$anmin=$popnat->an;
						}
						if ($popnat->an<$anmin){
							$anmin=$popnat->an;
						}												
						if ($popnat->an>$anmax){
							$anmax=$popnat->an;
						}
						if ($popnat->total>$popnatmax){
							$popnatmax=$popnat->total;
						}						
						
					}
					$popnatmax=System::getMaxValueRounded($popnatmax);
					$chxl='0:|'.$chxl0.'1:|'.$chxl1;
					$chxp='0'.$chxp0.'|1'.$chxp1;
					$chxr="0,$anmin,$anmax|1,$popnatmin,$popnatmax";
					$chds="$anmin,$anmax,$popnatmin,$popnatmax";
					$chd='t:'.$chd0.'|'.$chd1;

					$chtt='Evolutia numarului de locuitori in ultimii 100 ani in '.$this->location->getFullName();
					$o2b.='<img src="//chart.googleapis.com/chart?chf=bg,s,EEEEEE&chxl='.$chxl.'&chxp='.$chxp.'&chxr='.$chxr.'&chxs=0,000000,14,0,lt,000000|1,000000,14,0,lt,000000&chxt=x,y&chs=580x360&cht=lxy&chco=3D7930&chds='.$chds.'&chd='.$chd.'&chg=10,10,1,1&chls=3&chma=10,10,10,10&chm=B,C5D4B5BB,0,0,0,1&chtt='.$chtt.'&chts=000000,16" width="580" height="360" alt="" />';
					
					$o2b.='</div>';					
		}
		if ($o2s!=''){
			$out.=$this->getGroupBoxH3($o2s,$o2b);
		}
		return $out;
	}
	function showElectoralPreferences(){
		if (($this->location->raion_id!=500)&&($this->location->raion_id!=700)){
			$this->setJavascript("https://www.google.com/jsapi");
			$o2s='<a name="6"></a>Preferintele Electorale pe parcursul anilor ale locuitorilor din '.$this->location->getFullNameDescription();
			$o2b=Alegeri::getResultsByLocation($this->location->raion_id,$this->location->id);
			$o2f="Sursa: www.cec.md";
			if (empty($o2b)){
				return "";
			} else {
				return $this->getGroupBoxH3($o2s,$o2b,$o2f);
			}
		}
	}
	function showAlegeri141130Image(){
		
		$o2s='<a name="6"></a>Rezultatele Electorale ale locuitorilor din '.$this->location->getFullNameDescription();
		$o2b=Alegeri::getImageUrlByPrimarie($this->location->raion_id,$this->location->id);
		$o2f="Sursa: www.cec.md";
		if (empty($o2b)){
			return "";
		} else {
			return $this->getGroupBoxH3($o2s,$o2b,$o2f);
		}
	}
	function showAlegeriPresidentiale161030Image(){
		if (($this->location->raion_id!=500)&&($this->location->raion_id!=700)){
			$o2s='<a name="7"></a>Alegerile Prezidentiale din 30 octombrie 2016 - Rezultatele din '.$this->location->getFullName();
		
			$o2b='<b>Rezultate Tur 2:</b>';
			$o2b.=Alegeri::getAlegeriPresidentialeByLocaliateAndTur($this,$this->location->raion_id,$this->location->id,2);
			$o2b.='<b>Rezultate Tur 1:</b>';
			$o2b.=Alegeri::getAlegeriPresidentialeByLocaliateAndTur($this,$this->location->raion_id,$this->location->id,1);
			$o2f="Sursa: www.cec.md";
			if (empty($o2b)){
				return "";
			} else {
				return $this->getGroupBoxH3($o2s,$o2b,$o2f);
			}
		}
	}	
	function getRecensamintInfo(){
		$out='Datele sunt pe baza recensamintului din 2004';
		return $out;
	}
}
$n=new IndexLocationsWebPage();
?>
