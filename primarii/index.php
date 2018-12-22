<?php
require_once(__DIR__ . '/../main/loader.php');

class IndexLocationsWebPage extends MainWebPage {	
	function __construct(){
		parent::__construct();
		$this->setLogoTitle("PRIMARIILE DIN REPUBLICA MOLDOVA");		
		$this->create();		
	}
	function actionDefault(){
		$this->setTitle("Lista primariilor dupa Raioane si Municipii");
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));	
		$this->setCenterContainer($this->getGroupBoxH2("Lista primariilor dupa Raioane si Municipii",Raion::getRaionsListForPrimarii($this)));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Primarie:",$this->getSearchPrimarie()));
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
		$this->setTitle('Primariile din '.$this->raion->getFullNameDescription());	
		$this->setCenterContainer($this->getRaion());
		// $this->setCenterContainer($this->getPopulationInTime());
		$c='<a name="5"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'x',$r->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenuRaion()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Primarie:",$this->getSearchPrimarie()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setLeftContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));
		$this->show();
	}
	function actionViewPrimarie(){

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
			Logger::setLogs($this->location);

		}
		Logger::setLogs($this->location);
		Logger::setLogs("point11".$this->location->parent_id);
		$l->count();
		$this->setTitle('Primarii Moldova: '.$this->location->getPrimariaName());
		$this->setLogoTitle(strtoupper($this->location->getPrimariaName()));
		$this->setCenterContainer($this->getPrimarie());
		$this->setCenterContainer($this->getPopulationInTime());
		$this->setCenterContainer($this->getPrimarieConsilieri());
		$this->setCenterContainer($this->showAlegeriPresidentiale161030Image());
		$this->setCenterContainer($this->showAlegeri141130Image());		
		$this->setCenterContainer($this->showElectoralPreferences());
		$this->setCenterContainer($this->getPrimariiInJur());
		$this->setCenterContainer($this->getLocalitatiDistance());	
		$this->setCenterContainer($this->getContacts($l));
		$this->setCenterContainer($this->getNewsTitles());
		//$this->setCenterContainer($this->getNews());
		
		$c='<a name="9"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'y',$l->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenuPrimarie()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Primarie:",$this->getSearchPrimarie()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
		$this->setRightContainer($this->getGroupBoxH3("Referinte Utile",$this->getLeftMenu()));
		$this->setLeftContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));		
		$this->show();
	}				

	function actionViewOrase($html="LocationsWebPageHTML"){
		$this->setTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($this->getConstants("IndexLocationsWebPageOraseTitle"),$this->getOrase()));
		$this->show();
	}
	function actionViewTopSusLocalitati($html="LocationsWebPageHTML"){
		$t="Lista a 50 cele mai sus amplasate localitati din Moldova";
		$this->setTitle($t);
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopSusLocalitati()));
		$this->show();
	}
	function actionViewTopJosLocalitati($html="LocationsWebPageHTML"){
		$t="Lista a 50 cele mai jos amplasate localitati din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopJosLocalitati()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopUpPopLocalitati($html="LocationsWebPageHTML"){
		$t="Lista a 50 cele mai populate localitati din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopUpPopLocalitati()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopDownPopLocalitati($html="LocationsWebPageHTML"){
		$t="Lista a 50 cele mai putin populate localitati din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopDownPopLocalitati()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiUcraineni($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Ucraineni din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiUcraineni()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiRusi($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Rusi din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiRusi()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiGagauzi($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Gagauzi din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiGagauzi()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiBulgari($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Bulgari din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiBulgari()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiEvrei($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Evrei din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiEvrei()));
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
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiPolonezi()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopLocalitatiTigani($html="LocationsWebPageHTML"){
		$t="Lista localitatilor populate cu Tigani din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopLocalitatiTigani()));
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
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#2").'">Primarii in componenata</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#3").'">Harta</a></li>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#4").'">Populatia</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#5").'">Forum/Comentarii</a></li>';
		$out.='</ul>';
		return $out;
	}
	function getMenuPrimarie(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$this->id."#2").'">Localitati in componenta</a></li>';	
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$this->id."#3").'">Date geografice</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$this->id."#4").'">Pozitia pe Harta</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$this->id."#5").'">Lista Consilierilor</a></li>';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$this->id."#6").'">Primarii in raza de 10 km</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$this->id."#7").'">Telefoane</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$this->id."#8").'">Stiri</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$this->id."#9").'">Forum/Comentarii</a></li>';
		$out.='</ul>';
		return $out;
	}		

	function getLeftMenu(){
		$out='<ul>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'" title="Populatia">Lista si numarul de Municipii</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'" >Lista primariilor dupa Raioane si Municipii</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php").'">'.$this->getConstants("IndexLocationsWebPageRaioaneTitle").'</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=vieworase").'" >'.$this->getConstants("IndexLocationsWebPageOraseTitle").'</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewtopsuslocalitati").'" >Lista a 50 cele mai sus amplasate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewtopjoslocalitati").'" >Lista a 50 cele mai jos amplasate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewtopuppoplocalitati").'" >Lista a 50 cele mai populate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewtopdownpoplocalitati").'" >Lista a 50 cele mai putin populate localitati din Moldova</a></li>';
		$out.='</ul>';
		return $out;		
	}
	function getRaion(){
		$out="";
		$ls=$this->raion->getPrimarii();
		
		$o1s='<a name="1"></a>'.$this->raion->getFullNameDescription();
		$rm=($this->raion->municipiu==1)?("Municipiului"):("Raionului");	
		$o1b=$this->raion->getFullNameDescription().' este situat la latitudinea '.$this->raion->getLatShort().' longitudinea '.$this->raion->getLngShort().' si altitudinea de '.$this->raion->elevation.' metri fata de nivelul marii. In componenta '.$rm.' intra '.count($ls).' Primarii. ';
		if (($this->raion->id!=500)&&($this->raion->id!=700)){
		    $o1b.='Conform recensamintului din anul 2014 popuplatia este de '.number_format($this->raion->getPopulation(), 0, ',', ' ').' locuitori.';
		}
		$out.=$this->getGroupBoxH3($o1s,$o1b);

		$o2s='<a name="2"></a> Primarii in componenta '.$this->raion->getFullNameDescription().':';
		$o2b=Raion::getPrimariiListByRaion($this, $this->raion->id);
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
	function getPrimarie(){
		$out="";
		$url=$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewraion&id=".$this->raion->id);
		$o1s='<a name="1"></a>'.$this->location->getPrimariaName().' - Scurta descriere:';
		$o1b=$this->location->getPrimariaName().' este  in  componenta <a href='.$url.'>'.$this->raion->getFullNameDescription().'</a> situata la latitudinea '.$this->location->getLatShort().' longitudinea '.$this->location->getLngShort().' si altitudinea de '.$this->location->elevation.' metri fata de nivelul marii.';
		
		$primar=$this->location->getPrimarName();
		$primarPartid=$this->location->getPrimarPartid();
		
		
		if (!is_null($primar)){
			$o1b.=' Primarul este '.$primar->prenume.' '.$primar->nume;	
		} 
		if ($primarPartid->partidcod=='CI'){
			$o1b.=' la alegeri fiind '.$primarPartid->partidname.'. ';
		} else {
			$o1b.=' din partea ('.$primarPartid->partidcod.' - '.$primarPartid->partidname.'). ';
		}
		
		if ($this->location->p>0){
			if ($this->location->isComuna()){
				$o1b.='Conform recensamintului din anul 2014 populatia comunei este de '.number_format($this->location->getPrimariePopulation(), 0, ',', ' ').' locuitori. ';
			} else {
				$o1b.='Conform recensamintului din anul 2014 populatia este de '.number_format($this->location->getPrimariePopulation(), 0, ',', ' ').' locuitori. ';
			}
		}
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
		
		$ls=$this->location->getChildLocationsAsResultSet();
		if (mysql_num_rows($ls)!=0){
			$o2s='';
			$o2b='';
			$o2s.='<a name="2"></a>'.$this->location->getPrimariaName().' - Localitati in componenta:';
			$o2b.='<div class="groupboxtable">';
		
			$table=new Table();
			$table->setDataSet($ls);
			$url=$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate&id=");
		
			$denumirelocalitati=function($row) use ($url){				
				return '<a href="'.$url.$row->id.'">'.$row->tip.' '.$row->name.'</a>';
			};

			$table->addField(new TableField(1, "Denumirea localitatii", "name", "",$denumirelocalitati));

			$o2b.=$table->show();
			$o2b.='</div>';
			$out.=$this->getGroupBoxH3($o2s,$o2b);
		}		
		
		$o3s='<a name="3"></a>'.$this->location->getPrimariaName()." - Date geografice:";
		$o3b='Latitudinea: '.$this->location->lat.'<br>';
		$o3b.='Longitudinea: '.$this->location->lng.'<br>';
		$o3b.='Altitudinea: '.$this->location->elevation.' m.<br>';
		$out.=$this->getGroupBoxH3($o3s,$o3b);	
		
		$o4s='<a name="4"></a>'.$this->location->getPrimariaName()." - Pozitia pe harta:";
		//$o4b=$this->getMap($this->location->lat,$this->location->lng,12,3,$this->location->lat,$this->location->lng, $this->location->getFullNameDescription());
		$this->location->zoom=12;
		$this->location->maptype=3;
		$this->location->centerlat=$this->location->lat;
		$this->location->centerlng=$this->location->lng;
		$o4b=$this->getMap($this->location);
		$out.=$this->getGroupBoxH3($o4s,$o4b);	
		return $out;
	}
	function getPrimarieConsilieri(){
		$out="";
	
		$ls=$this->location->getPrimarieConsilieri();
		if (mysql_num_rows($ls)!=0){
			$o2s='';
			$o2b='';
			$o2s.='<a name="5"></a>'.$this->location->getPrimariaName().' - Lista Consilierilor:';
			$o2b.='<div class="groupboxtable">';
		
			$table=new Table();
			$table->setDataSet($ls);
			$numeprenumevalue=function($row){
				return $row->prenume.' '.$row->nume;
			};
			$table->addField(new TableField(1, "Numele Cosilierui", "partid", "",$numeprenumevalue));
			$table->addField(new TableField(2, "Partidul", "partid", "text-align: center;",""));

			$o2b.=$table->show();
			$o2b.='</div>';
			$out.=$this->getGroupBoxH3($o2s,$o2b);		
		}
	
		$ls=$this->location->getPrimarieConsilieriPerPartid();
		$this->c=$this->location->getPrimarieConsilieriTotal();
	
		if (mysql_num_rows($ls)!=0){
			$o2s='';
			$o2b='';
			$o2s.='<a name="2"></a>'.$this->location->getPrimariaName().' - Lista Partidelor in Consiliu:';
			$o2b.='<div class="groupboxtable">';

			$chd='t:';
			$chdl='';
			$chl='';
			$chco='';
			$cnt=1;
			while($l = mysql_fetch_object($ls)){
					
			//foreach($ls as $l){
				if ($cnt==1){
					$chco.=$this->getPartidColor($l->partid);
					$chd.=round($l->c*100/$this->c,2);
					$chdl.=$l->partid;
					$chl.=round($l->c*100/$this->c,2);
				} else {
					$chco.='|'.$this->getPartidColor($l->partid);
					$chd.=','.round($l->c*100/$this->c,2);
					$chdl.='|'.$l->partid;
					$chl.='%|'.round($l->c*100/$this->c,2);
						
				}
				//$o2b.='<tr><td>'.$l->partid.'</td><td>'.$l->c.'</td><td>'.round($l->c*100/$c,2).'</td></tr>';
				$cnt++;
			}
			
			$table=new Table();
			$table->setDataSet($ls);
			$table->setShowNrOrd(false);
			$table->addField(new TableField(1, "Partidul", "partid", "text-align: center;",""));
			$table->addField(new TableField(2, "Numarul de consilieri", "c", "text-align: center;",""));
			$percentvalue=function($row){
				return round($row->c*100/$this->c,2);
			};
			$table->addField(new TableField(3, "Procent %", "c", "text-align: center;",$percentvalue));						
			$o2b.=$table->show();
			$o2b.='</div>';
					
			
				
		
			//$chco='224499|008000|FF0000|FF9900|AA0033|7777CC|80C65A|AB8F3C|AD4949';
			//$chco='224499|008000|FF0000|FF9900';
			//$chd='t:'.$moldovenip.','.$ucrainenip.','.$rusip.','.$gagauzip.','.$bulgarip.','.$evreip.','.$polonezip.','.$tiganip.','.$altelep;
			//$chdl='Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Tigani|Altele';
			//$chl=$moldovenip.'%|'.$ucrainenip.'%|'.$rusip.'%|'.$gagauzip.'%|'.$bulgarip.'%|'.$evreip.'%|'.$polonezip.'%|'.$tiganip.'%|'.$altelep;
			$chtt='Repartizarea Partidelor in Consiliu - '.$this->location->getPrimariaName();
			$o2b.='<br>';
			//$o2b.='Componenta pe nationalitati:';
			//$o2b.='<br>';
			$o2b.='<div style="text-align:center">';
			$o2b.='<img src="https://chart.apis.google.com/chart?chf=bg,s,eeeeee&chs=580x260&cht=pc&chco='.$chco.'&chd='.$chd.'&chdl='.$chdl.'&chl='.$chl.'&chma=5,5,5,5&chtt='.$chtt.'" width="580" height="260" alt="'.$chtt.'" />';
			$o2b.='</div>';		
		
			$out.=$this->getGroupBoxH3($o2s,$o2b);
		}
		return $out;
	}
	function getPartidColor($partid){
			$rtcl="008000";
						
			$partidcolors=array("BLP"=>"C22E1D", "BEPPEM"=>"111E8F", "CI"=>"A52A2A", "MPA"=>"9C0918", "MSPR"=>"B39C57", "PCNM"=>"CF0000", "PCRM"=>"FF0000", "PDM"=>"224499", "PL"=>"ADD8E6", "PLDM"=>"008000", "PNL"=>"FFFF32", "PPPN"=>"120C10", "PPPVE"=>"3E7306", "PPRM"=>"9656A3", "PR"=>"BD2A2A", "PRM"=>"1F93D1", "PSM"=>"CC020C", "PSRM"=>"ED1C23");
			foreach($partidcolors as $p=>$c){
				if ($partid==$p){
					$rtcl=$c;
					break;
				}
			}
			return $rtcl;
	}

	function getLocalitatiInJur(){
		$out="";
		
		$ls=$this->location->getLocationsInRadius();
		if (count($ls)!=0){				
			$o2s='';
			$o2b='';
			$o2s.='<a name="5"></a>'.$this->location->getFullNameDescription().' - Localitati in raza de 10 km:';		
			$l=new Location();
			foreach($ls as $loc){
				$l->loadById($loc->id);
				$lurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$rurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->getRaion()->id);
				$o2b.=round($loc->distance).' km - distanța directă pînă la <a href="'.$lurl.'">'.$l->getFullNameDescription().'</a> din <a href="'.$rurl.'">'.$l->getRaion()->getFullNameDescription().'</a> - <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$distantesite."/index.php","from=".$this->location->id."&to=".$loc->id).'">vezi pe harta</a><br>';
				//$o2b.='<a href="?lc='.$l->id.'">'.$l->getFullName().'</a><br>';	
			}
			$out.=$this->getGroupBoxH3($o2s,$o2b);		
		}			

		return $out;
	}
	function getPrimariiInJur(){
		$out="";
	
		$ls=$this->location->getPrimariiInRadius();
		if (count($ls)!=0){
			$o2s='';
			$o2b='';
			$o2s.='<a name="6"></a>'.$this->location->getPrimariaName().' - Primarii in raza de 10 km:';
			$l=new Location();
			foreach($ls as $loc){
				$l->loadById($loc->id);
				$lurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$l->id);
				$rurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->getRaion()->id);
				$o2b.=round($loc->distance).' km - distanța directă pînă la <a href="'.$lurl.'">'.$l->getPrimariaName().'</a> din <a href="'.$rurl.'">'.$l->getRaion()->getFullNameDescription().'</a> - <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$distantesite."/index.php","from=".$this->location->id."&to=".$loc->id).'">vezi pe harta</a><br>';
				//$o2b.='<a href="?lc='.$l->id.'">'.$l->getFullName().'</a><br>';
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
			$o2s.='<a name="5"></a>'.$this->location->getPrimariaName().' - Distanțe:';		
			$l=new Location();
			foreach($ls as $loc){
				$l->loadById($loc->to_id);
				$furl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$loc->from_id);
				$turl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$loc->to_id);
				//$turl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->getRaion()->id);
				//$o2b.=round($loc->distance).' km - distanța directă pînă la <a href="'.$turl.'">'.$loc->to_name.'</a> din <a href="'.$rurl.'">'.$l->getRaion()->getFullNameDescription().'<br></a>';
				$o2b.='Distanța directă pîna în <a href="'.$turl.'">'.$l->getFullName().'</a> este de - '.round($loc->distance).' km <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$distantesite."/index.php","from=".$loc->from_id."&to=".$loc->to_id).'">vezi pe harta</a><br>';
				//$o2b.='<a>test</a><br>';	
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
		
		if (($this->location->p>0)){				
			$o2s='<a name="7"></a>'.$this->location->getFullNameDescription().' - Populatia (Conform recensamintului din anul 2004):';		
			$o2b='Locuitori - '.number_format($this->location->p, 0, ',', ' ').' din care:<br>';
			//$o2b.='Din care:<br>';
			$o2b.='Barbati - '.number_format($this->location->m, 0, ',', ' ').'<br>';
			$o2b.='Femei - '.number_format($this->location->f, 0, ',', ' ').'<br>';	
			$popnat=new Population();
			$popnat->loadById($this->location->id);
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
			$o2b.='<div class="groupboxtable">';
			$o2b.='<table style="width:100%;">';			
			$o2b.='<tr><th>Nationalitate</th><th>Numar de Locuitori</th><th>% de Locuitori</th></tr>';
			$o2b.='<tr><td>Moldoveni/Romani</td><td style="text-align:center;">'.$moldoveni.'</td><td style="text-align:center;">'.$moldovenip.'</td></tr>';
			$o2b.='<tr><td>Ucraineni</td><td style="text-align:center;">'.$ucraineni.'</td><td style="text-align:center;">'.$ucrainenip.'</td></tr>';
			$o2b.='<tr><td>Rusi</td><td style="text-align:center;">'.$rusi.'</td><td style="text-align:center;">'.$rusip.'</td></tr>';
			$o2b.='<tr><td>Gagauzi</td><td style="text-align:center;">'.$gagauzi.'</td><td style="text-align:center;">'.$gagauzip.'</td></tr>';
			$o2b.='<tr><td>Bulgari</td><td style="text-align:center;">'.$bulgari.'</td><td style="text-align:center;">'.$bulgarip.'</td></tr>';
			$o2b.='<tr><td>Evrei</td><td style="text-align:center;">'.$evrei.'</td><td style="text-align:center;">'.$evreip.'</td></tr>';
			$o2b.='<tr><td>Polonezi</td><td style="text-align:center;">'.$polonezi.'</td><td style="text-align:center;">'.$polonezip.'</td></tr>';
			$o2b.='<tr><td>Tigani</td><td style="text-align:center;">'.$tigani.'</td><td style="text-align:center;">'.$tiganip.'</td></tr>';		
			$o2b.='<tr><td>Altele</td><td style="text-align:center;">'.$altele.'</td><td style="text-align:center;">'.$altelep.'</td></tr>';
			$o2b.='</table>';
			$o2b.='</div>';
			$chco='008000|224499|FF0000|FF9900|AA0033|7777CC|80C65A|AB8F3C|AD4949';
			$chd='t:'.$moldovenip.','.$ucrainenip.','.$rusip.','.$gagauzip.','.$bulgarip.','.$evreip.','.$polonezip.','.$tiganip.','.$altelep;
			$chdl='Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Tigani|Altele';
			$chl=$moldovenip.'%|'.$ucrainenip.'%|'.$rusip.'%|'.$gagauzip.'%|'.$bulgarip.'%|'.$evreip.'%|'.$polonezip.'%|'.$tiganip.'%|'.$altelep;
			$chtt='Nationalitati '.$this->location->getFullNameDescription();
			$o2b.='<br>';
			//$o2b.='Componenta pe nationalitati:';
			//$o2b.='<br>';
			$o2b.='<div style="text-align:center">';
			$o2b.='<img src="https://chart.apis.google.com/chart?chf=bg,s,eeeeee&chs=580x260&cht=pc&chco='.$chco.'&chd='.$chd.'&chdl='.$chdl.'&chl='.$chl.'&chma=5,5,5,5&chtt='.$chtt.'" width="580" height="260" alt="'.$chtt.'" />';
			$o2b.='</div>';
			
			//https://chart.apis.google.com/chart?chs=400x225&cht=p&chd=t:90.25,0.73,1,0.31,1,0,0,6.29,0.42&chdl=Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Tigani|Altele&chl=90.25%25|0.73%25|1%25|0.31%25|1%25|0%25|0%25|6.29%25|0.42%25&chtt=Nationalitati+in+satul+XXXX
			
		
		}
		$out.=$this->getGroupBoxH3($o2s,$o2b);					
		return $out;
	}
	function getRaionPopulation(){
		$out="";
		$pn=new RecensamintPopulation();
		$pno=RecensamintPrimarie::getPopulationBy(2014, $this->raion->id);
		$popnat=$pno[0];
		if (!$popnat->total==""){
			$total=$popnat->total;		
			$o2s='<a name="4"></a>'.$this->raion->getFullNameDescription().' - Populatia:';		
			$o2b='Conform recensamintului din anul 2004 populatia este de - '.number_format($total, 0, ',', ' ').' <br>';
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
			$o2b.='<div class="groupboxtable">';
			$o2b.='<table style="width:100%;">';			
			$o2b.='<tr><th>Nationalitate</th><th>Numar de Locuitori</th><th>% de Locuitori</th></tr>';
			$o2b.='<tr><td>Moldoveni/Romani</td><td style="text-align:center;">'.$moldoveni.'</td><td style="text-align:center;">'.$moldovenip.'</td></tr>';
			$o2b.='<tr><td>Ucraineni</td><td style="text-align:center;">'.$ucraineni.'</td><td style="text-align:center;">'.$ucrainenip.'</td></tr>';
			$o2b.='<tr><td>Rusi</td><td style="text-align:center;">'.$rusi.'</td><td style="text-align:center;">'.$rusip.'</td></tr>';
			$o2b.='<tr><td>Gagauzi</td><td style="text-align:center;">'.$gagauzi.'</td><td style="text-align:center;">'.$gagauzip.'</td></tr>';
			$o2b.='<tr><td>Bulgari</td><td style="text-align:center;">'.$bulgari.'</td><td style="text-align:center;">'.$bulgarip.'</td></tr>';
			$o2b.='<tr><td>Evrei</td><td style="text-align:center;">'.$evrei.'</td><td style="text-align:center;">'.$evreip.'</td></tr>';
			$o2b.='<tr><td>Polonezi</td><td style="text-align:center;">'.$polonezi.'</td><td style="text-align:center;">'.$polonezip.'</td></tr>';
			$o2b.='<tr><td>Tigani</td><td style="text-align:center;">'.$tigani.'</td><td style="text-align:center;">'.$tiganip.'</td></tr>';		
			$o2b.='<tr><td>Altele</td><td style="text-align:center;">'.$altele.'</td><td style="text-align:center;">'.$altelep.'</td></tr>';
			$o2b.='</table>';
			$o2b.='</div>';
			$chco='008000|224499|FF0000|FF9900|AA0033|7777CC|80C65A|AB8F3C|AD4949';
			$chd='t:'.$moldovenip.','.$ucrainenip.','.$rusip.','.$gagauzip.','.$bulgarip.','.$evreip.','.$polonezip.','.$tiganip.','.$altelep;
			$chdl='Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Tigani|Altele';
			$chl=$moldovenip.'%|'.$ucrainenip.'%|'.$rusip.'%|'.$gagauzip.'%|'.$bulgarip.'%|'.$evreip.'%|'.$polonezip.'%|'.$tiganip.'%|'.$altelep;
			$chtt='Nationalitati '.$this->raion->getFullNameDescription();
			$o2b.='<br>';
			//$o2b.='Componenta pe nationalitati:';
			//$o2b.='<br>';
			$o2b.='<div style="text-align:center">';
			$o2b.='<img src="https://chart.apis.google.com/chart?chf=bg,s,eeeeee&chs=580x260&cht=pc&chco='.$chco.'&chd='.$chd.'&chdl='.$chdl.'&chl='.$chl.'&chma=5,5,5,5&chtt='.$chtt.'" width="580" height="260" alt="'.$chtt.'" />';
			$o2b.='</div>';
			
			//https://chart.apis.google.com/chart?chs=400x225&cht=p&chd=t:90.25,0.73,1,0.31,1,0,0,6.29,0.42&chdl=Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Tigani|Altele&chl=90.25%25|0.73%25|1%25|0.31%25|1%25|0%25|0%25|6.29%25|0.42%25&chtt=Nationalitati+in+satul+XXXX
			
			
			//}
			$out.=$this->getGroupBoxH3($o2s,$o2b);
		}					
		return $out;
	}	
	function getNews(){
		$out="";			
		$o2s='<a name="8"></a>'.$this->location->getPrimariaName().' - Ultimile Stiri:';		
		$n=new News();	
		$o2b=$n->getNewsByLocalitate($this->location->id);	
		$link=$this->getUrlWithSpecialCharsConverted(Config::$newssite."/index.php","action=viewlocalitate&localitate=".$this->location->id);
		$o2f=$this->getFooterWithLink($link);	
		return $this->getGroupBoxH3($o2s,$o2b,$o2f);
	}
	function getNewsTitles(){
		$out="";			
		$o2s='<a name="8"></a>'.$this->location->getPrimariaName().' - Ultimile Titluri de Stiri:';		
		$fi=new FeedItem();
		$o2b=$fi->getNewsByPrimarie($this->location->id);		
		$link=$this->getUrlWithSpecialCharsConverted(Config::$feedssite."/index.php","action=primarie&id=".$this->location->id);
		$o2f=$this->getFooterWithLink($link);	
		return $this->getGroupBoxH3($o2s,$o2b,$o2f);
	}	
	function getPhotos(){
		$out="";			
		$o2s='<a name="9"></a>'.$this->location->getFullNameDescription().' - Foto/Imagini in raza de 10 km:';		
		$p=new Photo();		
		$o2b=$p->getPhotosByLatLng($this->location->lat,$this->location->lng);	
		return $this->getGroupBoxH3($o2s,$o2b);
	}
	function getRaionPhotos(){
		$out="";
		$o2s='<a name="9"></a>'.$this->raion->getFullNameDescription().' - Foto/Imagini in raza de 10 km:';
		$p=new Photo();
		$o2b=$p->getPhotosByLatLng($this->raion->lat,$this->raion->lng);
		return $this->getGroupBoxH3($o2s,$o2b);
	}								
	function getMap1($m){

		$this->setBodyTag('<body onload="MapViewOnMapLoad()">');
		$this->setJavascript("https://maps.google.com/maps/api/js?sensor=false");

		//$this->setBodyTag('<body onload="MapViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("https://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));


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
		//$out.='<input name="map_title" type="hidden" id="map_title" readonly="true" class="inptdisabled" value="" />';
		//$out.='<input name="map_description" type="hidden" id="map_description" readonly="true" class="inptdisabled" value="" />';
		$out.='<div id="map" style="width: 100%;"></div>';
		return $out;
	}		
	function getGSMMap($m){

		//$this->setBodyTag('<body onload="MapViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("https://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));

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
		//$out.='<input name="map_title" type="hidden" id="map_title" readonly="true" class="inptdisabled" value="" />';
		//$out.='<input name="map_description" type="hidden" id="map_description" readonly="true" class="inptdisabled" value="" />';
		$out.='<div id="gsmmap" style="width: 100%;"></div>';
		return $out;
	}	

	function getContacts($l){
	
		$out='';		
		$out.='<div class="groupboxtable">';
		$out.=$l->getContacts();
		$out.='</div>';
		
		$out.='<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$telefoanesite."/index.php","action=viewprefixbylocation&id=".$l->id).'">Mai multe contacte vezi aici</a>';
	
		return $this->getGroupBoxH2("<a name=\"7\"></a>Contacte, Telefoane a celor mai importante institutii sociale",$out);
	}				
	function showElectoralPreferences(){
		if (($this->location->raion_id!=500)&&($this->location->raion_id!=700)){
			$this->setJavascript("https://www.google.com/jsapi");
			$o2s='<a name="6"></a>Preferintele Electorale pe parcursul anilor ale locuitorilor din '.$this->location->getPrimariaName();
			$o2b=Alegeri::getResultsByPrimarie($this->location->raion_id,$this->location->id);
			$o2f="Sursa: www.cec.md";
			if (empty($o2b)){
				return "";
			} else {
				return $this->getGroupBoxH3($o2s,$o2b,$o2f);
			}
		}
	}
	function showAlegeri141130Image(){
		
		$o2s='<a name="6"></a>Alegerile Parlamentare din 30 noiembrie 2014 - Rezultatele din '.$this->location->getPrimariaName();
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
			$o2s='<a name="7"></a>Alegerile Prezidentiale din 30 octombrie 2016 - Rezultatele din '.$this->location->getPrimariaName();
			
			$o2b='<b>Imagini Sursa:</b>';
			$o2b.=Alegeri::getPresidentialImageUrlByPrimarie($this->location->raion_id,$this->location->id);
			$o2f="Sursa: www.cec.md";
			if (empty($o2b)){
				return "";
			} else {
				return $this->getGroupBoxH3($o2s,$o2b,$o2f);
			}
		}
	}
	function getPopulationInTime(){
		$out='';
		$o2s='';
		$o2b='';
		$p=new RecensamintPrimarie();
		$popnatall=RecensamintPrimarie::getPopulationBy(0, 0, $this->location->id);
		if (count($popnatall)>1){

					$o2s=$this->location->getFullNameDescription().' - Evolutia numarului de locuitori in ultimii 100 ani:';

					$o2b.=RecensamintPrimarie::getPopulationInTimeVeiwByPrimarie($this, $this->location->id);

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
}
$n=new IndexLocationsWebPage();
?>
