<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');


class IndexLocationsWebPage extends MainWebPage {

	
	function __construct(){
		parent::__construct();
		//$this->setBodyTag('<body onload="SmallViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;hl=ro&amp;sensor=false&amp;key=".Config::getMapKey($this->getServerName()));
		//$this->setCSS("style/maps.css");
		//$this->setJavascript("js/scripts.js");
		$this->setLogoTitle("LOCALITATI DIN REPUBLICA MOLDOVA");		
		$this->create();		
	}
	function actionDefault(){
		//$this->setLogoTitle("Localitati din Republica Moldova");
		$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));	
		$this->setCenterContainer($this->getGroupBoxH2($this->getConstants("IndexLocationsWebPageRaioaneTitle"),$this->getMain()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
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
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageTitle").' '.$this->raion->getFullNameDescription());	
		$this->setCenterContainer($this->getRaion());
		$this->setCenterContainer($this->getRaionPopulation());
		$this->setCenterContainer($this->getRaionPhotos());
		$this->setCenterContainer($this->getPanoramioFotos($r));
		$this->setCenterContainer($this->getImobilList($this->raion->id, 0));			
		$c='<a name="11"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'r',$r->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenuRaion()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
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
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageTitle").' '.$this->location->getFullNameDescription());
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setCenterContainer($this->getLocalitate());
		$this->setCenterContainer($this->getPopulation());
		$this->setCenterContainer($this->getPopulationInTime());
		$this->setCenterContainer($this->showElectoralPreferences());
		//$this->setCenterContainer($this->showAlegeri141130Image());	
		$this->setCenterContainer($this->getLocalitatiInJur());
		$this->setCenterContainer($this->getLocalitatiDistance());	
		$this->setCenterContainer($this->getPhotos());
		$this->setCenterContainer($this->getPanoramioFotos($l));
		$this->setCenterContainer($this->getLocalitatiCuAcelasNume());	
		$this->setCenterContainer($this->getNews());		
		$this->setCenterContainer($this->getImobilList($this->raion->id, $this->location->id));
		$this->setCenterContainer($this->getContacts($l));
		$this->setCenterContainer($this->getDictionar($l));
		$this->setCenterContainer($this->getTopNames($l));
		$this->setCenterContainer($this->getSystemDetails($l));		
		
		$c='<a name="14"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'l',$l->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenuLocalitate()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
		$this->setRightContainer($this->getGroupBoxH3("Referinte Utile",$this->getLeftMenu()));		
		$this->show();
	}				

	function actionViewOrase($html="LocationsWebPageHTML"){
		$this->setTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($this->getConstants("IndexLocationsWebPageOraseTitle"),$this->getOrase()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
		$this->show();
	}
	function actionViewTopSusLocalitati($html="LocationsWebPageHTML"){
		$t="Lista a 50 cele mai sus amplasate localitati din Moldova";
		$this->setTitle($t);
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageOraseTitle"));
		//$this->setLogoTitle("Localitati din Republica Moldova");	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));				
		$this->setCenterContainer($this->getGroupBoxH2($t,$this->getTopSusLocalitati()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));
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
		$t="Lista localitatilor populate cu Romi/Tigani din Moldova";
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
		
	function getMain(){
		$r=new Raion();
		$rs=$r->getAll("","municipiu desc, `order`, name");
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;">';
		$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>".$this->getConstants("IndexLocationsWebPageRaioaneName")."</th></tr>";		
		if (count($rs)!=0){			
			$c=1;
			foreach($rs as $r){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$r->id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$r->getFullNameDescription().'</a></td></tr>';
				$c=$c+1;	
			}
		}
		$out.="</table>";
		$out.="</div>";		
		
		return $out;
	}
	function getMenuRaion(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#2").'">Localitați în componenață</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#3").'">Harta</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#4").'">Populația</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#5").'">Imagini</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$this->id."#9").'">Imobile</a></li>';
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
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#11").'">Telefoane</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#12").'">Nume de Familii</a></li>';				
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#13").'">Vizualizări</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$this->id."#14").'">Forum/Comentarii</a></li>';
		
		$out.='</ul>';
		return $out;
	}		
	function getOrase(){			
		$l=new Location();
		$ls=$l->getAll("oras=1","name");
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>".$this->getConstants("IndexLocationsWebPageRaioaneName")."</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->getFullNameDescription().'</a></td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopSusLocalitati(){			
		$l=new Location();
		$ls=$l->getAll("","elevation desc",0,50);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Altitudinea (m.)</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->getFullNameDescription().'</a> din <a href="'.$urlr.'">'.$l->getRaion()->getFullNameDescription().'</a></td><td style="text-align:center;">'.$l->elevation.'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopJosLocalitati(){			
		$l=new Location();
		$ls=$l->getAll("elevation>0","elevation",0,50);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Altitudinea (m.)</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->getFullNameDescription().'</a> din <a href="'.$urlr.'">'.$l->getRaion()->getFullNameDescription().'</a></td><td style="text-align:center;">'.$l->elevation.'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}		
	function getTopUpPopLocalitati(){			
		$l=new Location();
		$ls=$l->getAll("p>0","p desc",0,50);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				//$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->getFullNameDescription().'</a></td><td style="text-align:center;">'.number_format($l->p, 0, ',', ' ').'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopDownPopLocalitati(){			
		$l=new Location();
		$ls=$l->getAll("p>0","p",0,50);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->getFullNameDescription().'</a> din <a href="'.$urlr.'">'.$l->getRaion()->getFullNameDescription().'</a></td><td style="text-align:center;">'.number_format($l->p, 0, ',', ' ').'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopLocalitatiUcraineni(){
		$sql="select * from localitate l inner join popnat p on l.id=p.id where ucraineni>0 order by ucraineni desc";
		$l=new Location();
		$ls=$l->doSql($sql);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Nr. Ucraineni</th><th>% Ucraineni</th><th>Total Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->name.'</a></td><td style="text-align:center;">'.number_format($l->ucraineni, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format(($l->ucraineni/$l->p)*100, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format($l->p, 0, ',', ' ').'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopLocalitatiRusi(){			
		$sql="select * from localitate l inner join popnat p on l.id=p.id where rusi>0 order by rusi desc";
		$l=new Location();
		$ls=$l->doSql($sql);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Nr. Rusi</th><th>% Rusi</th><th>Total Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->name.'</a></td><td style="text-align:center;">'.number_format($l->rusi, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format(($l->rusi/$l->p)*100, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format($l->p, 0, ',', ' ').'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopLocalitatiGagauzi(){			
		$sql="select * from localitate l inner join popnat p on l.id=p.id where gagauzi>0 order by gagauzi desc";
		$l=new Location();
		$ls=$l->doSql($sql);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Nr. Gagauzi</th><th>% Gagauzi</th><th>Total Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->name.'</a></td><td style="text-align:center;">'.number_format($l->gagauzi, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format(($l->gagauzi/$l->p)*100, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format($l->p, 0, ',', ' ').'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopLocalitatiBulgari(){			
		$sql="select * from localitate l inner join popnat p on l.id=p.id where bulgari>0 order by bulgari desc";
		$l=new Location();
		$ls=$l->doSql($sql);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Nr. Bulgari</th><th>% Bulgari</th><th>Total Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->name.'</a></td><td style="text-align:center;">'.number_format($l->bulgari, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format(($l->bulgari/$l->p)*100, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format($l->p, 0, ',', ' ').'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopLocalitatiEvrei(){			
		$sql="select * from localitate l inner join popnat p on l.id=p.id where evrei>0 order by evrei desc";
		$l=new Location();
		$ls=$l->doSql($sql);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Nr. Evrei</th><th>% Evrei</th><th>Total Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->name.'</a></td><td style="text-align:center;">'.number_format($l->evrei, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format(($l->evrei/$l->p)*100, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format($l->p, 0, ',', ' ').'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopLocalitatiPolonezi(){			
		$sql="select * from localitate l inner join popnat p on l.id=p.id where polonezi>0 order by polonezi desc";
		$l=new Location();
		$ls=$l->doSql($sql);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Nr. Polonezi</th><th>% Polonezi</th><th>Total Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->name.'</a></td><td style="text-align:center;">'.number_format($l->polonezi, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format(($l->polonezi/$l->p)*100, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format($l->p, 0, ',', ' ').'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}
	function getTopLocalitatiTigani(){			
		$sql="select * from localitate l inner join popnat p on l.id=p.id where tigani>0 order by tigani desc";
		$l=new Location();
		$ls=$l->doSql($sql);
		$c=1;
		if (count($ls)!=0){			
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Nr. Romi/Tigani</th><th>% Romi/Tigani</th><th>Total Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrlWithSpecialCharsConverted("index.php","action=viewraion&id=".$l->raion_id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->name.'</a></td><td style="text-align:center;">'.number_format($l->tigani, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format(($l->tigani/$l->p)*100, 0, ',', ' ').'</td><td style="text-align:center;">'.number_format($l->p, 0, ',', ' ').'</td></tr>';
				$c=$c+1;	
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}											
	function getLeftMenu(){
		$out='<ul>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'" title="Populatia">Lista si numarul de Municipii</a></li>';
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
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=vieworase").'" title="Stiri">Lista a 30 cele mai sus amplasate localitati</a></li>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=vieworase").'" title="Stiri">Lista a 30 cele mai aglomerate localitati, de ex in raza de 10 km</a></li>';	
		//$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai mari sate</a></li>';
		//$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai mici sate</a></li>';		
		//$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai jos amplasate localitati</a></li>';
		//$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai sus amplasate localitati</a></li>';		
		//$out.='<li><a href="index.php#3" title="Imobil">Lista celor 30 cele mai populate Localitati</a></li>';				
		//$out.='<li><a href="index.php#4" title="Imagini">Lista celor 30 cele mai putin populate Localitati</a></li>';
		//$out.='<li><a href="index.php#4" title="Imagini">Populatia pe Municipii si Raioane</a></li>';		
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
		$o2b='<div class="groupboxtable">';
		$o2b.='<table style="width:100%;">';
		$o2b.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th></tr>";
		$c=1;
		foreach($ls as $l){	
			$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
			$o2b.='<tr><td style="text-align:center;">'.$c.'</td><td><a href="'.$url.'">'.$l->getFullName().'</a></td></tr>';
			$c=$c+1;
		}
		$o2b.='</table>';
		$o2b.='</div>';
		$out.=$this->getGroupBoxH3($o2s,$o2b);

		$o3s='<a name="3"></a>'.$this->getConstants("IndexLocationsWebPageRaionHarta")." ".$this->raion->getFullNameDescription();
		//$o3b.=$this->getMap($this->raion->lat,$this->raion->lng,12,3,$this->raion->lat,$this->raion->lng,$this->raion->getFullNameDescription());
		$this->raion->zoom=12;
		$this->raion->maptype=3;
		$this->raion->centerlat=$this->raion->lat;
		$this->raion->centerlng=$this->raion->lng;
		$o3b=$this->getMap($this->raion);
		$out.=$this->getGroupBoxH3($o3s,$o3b);
		///$o4s='<a name="4"></a>Acoperirea GSM';
		//$o3b.=$this->getMap($this->raion->lat,$this->raion->lng,12,3,$this->raion->lat,$this->raion->lng,$this->raion->getFullNameDescription());
		///$this->raion->zoom=12;
		///$this->raion->maptype=3;
		///$this->raion->centerlat=$this->raion->lat;
		///$this->raion->centerlng=$this->raion->lng;
		///$o4b.=$this->getGSMMap($this->raion);
		///$out.=$this->getGroupBoxH3($o4s,$o4b);

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
		
		//$ls=$this->location->getLocations();
		//if (count($ls)!=0){				
			$o2s='';
			$o2b='';
			$o2s.='<a name="2"></a>'.$this->location->getFullNameDescription().' - Primaria:';		
			//foreach($ls as $l){
			//	$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$l->id);
			//	$o2b.='<a href="'.$url.'">'.$l->getFullName().'</a>; ';
				//$o2b.='<a href="?lc='.$l->id.'">'.$l->getFullName().'</a><br>';	
			//}
			$o2b.='Vezi <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$primariisite."/index.php","action=viewprimarie&id=".$this->location->getPrimarieLocation()->id).'">'.$this->location->getPrimarieLocation()->getPrimariaName().'</a> pentru mai multe informatii depsre primaria localitatii';
			$out.=$this->getGroupBoxH3($o2s,$o2b);		
		//}			

		$o3s='<a name="3"></a>'.$this->location->getFullNameDescription()." - Date geografice";
		$o3b='Latitudinea: '.$this->location->lat.'<br>';
		$o3b.='Longitudinea: '.$this->location->lng.'<br>';
		$o3b.='Altitudinea: '.$this->location->elevation.' m.<br>';
		$out.=$this->getGroupBoxH3($o3s,$o3b);	
		
		$o4s='<a name="4"></a>'.$this->location->getFullNameDescription()." - Pozitia pe harta";
		//$o4b=$this->getMap($this->location->lat,$this->location->lng,12,3,$this->location->lat,$this->location->lng, $this->location->getFullNameDescription());
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
			$o2s.='<a name="5"></a>'.$this->location->getFullNameDescription().' - Distanțe:';		
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
		$o2s='';
		$o2b='';
		if (($this->location->p>0)){				
			$o2s='<a name="5"></a>'.$this->location->getFullNameDescription().' - Populatia (Conform recensamintului din anul 2004):';		
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
			$o2b.='<tr><td>Romi/Tigani</td><td style="text-align:center;">'.$tigani.'</td><td style="text-align:center;">'.$tiganip.'</td></tr>';		
			$o2b.='<tr><td>Altele</td><td style="text-align:center;">'.$altele.'</td><td style="text-align:center;">'.$altelep.'</td></tr>';
			$o2b.='</table>';
			$o2b.='</div>';
			$chco='008000|224499|FF0000|FF9900|AA0033|7777CC|80C65A|AB8F3C|AD4949';
			$chd='t:'.$moldovenip.','.$ucrainenip.','.$rusip.','.$gagauzip.','.$bulgarip.','.$evreip.','.$polonezip.','.$tiganip.','.$altelep;
			$chdl='Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Romi/Tigani|Altele';
			$chl=$moldovenip.'%|'.$ucrainenip.'%|'.$rusip.'%|'.$gagauzip.'%|'.$bulgarip.'%|'.$evreip.'%|'.$polonezip.'%|'.$tiganip.'%|'.$altelep;
			$chtt='Nationalitati '.$this->location->getFullNameDescription();
			$o2b.='<br>';
			//$o2b.='Componenta pe nationalitati:';
			//$o2b.='<br>';
			$o2b.='<div style="text-align:center">';
			$o2b.='<img src="http://chart.apis.google.com/chart?chf=bg,s,eeeeee&chs=580x260&cht=pc&chco='.$chco.'&chd='.$chd.'&chdl='.$chdl.'&chl='.$chl.'&chma=5,5,5,5&chtt='.$chtt.'" width="580" height="260" alt="'.$chtt.'" />';
			$o2b.='</div>';
			
			//http://chart.apis.google.com/chart?chs=400x225&cht=p&chd=t:90.25,0.73,1,0.31,1,0,0,6.29,0.42&chdl=Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Tigani|Altele&chl=90.25%25|0.73%25|1%25|0.31%25|1%25|0%25|0%25|6.29%25|0.42%25&chtt=Nationalitati+in+satul+XXXX
			
		
		}
		$out.=$this->getGroupBoxH3($o2s,$o2b);					
		return $out;
	}
	
	function getRaionPopulation(){
		$out="";

		$pn=new Population();
		$pno=$pn->getPopulationByRaion($this->raion->id);		
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
			$o2b.='<tr><td>Romi/Tigani</td><td style="text-align:center;">'.$tigani.'</td><td style="text-align:center;">'.$tiganip.'</td></tr>';		
			$o2b.='<tr><td>Altele</td><td style="text-align:center;">'.$altele.'</td><td style="text-align:center;">'.$altelep.'</td></tr>';
			$o2b.='</table>';
			$o2b.='</div>';
			$chco='008000|224499|FF0000|FF9900|AA0033|7777CC|80C65A|AB8F3C|AD4949';
			$chd='t:'.$moldovenip.','.$ucrainenip.','.$rusip.','.$gagauzip.','.$bulgarip.','.$evreip.','.$polonezip.','.$tiganip.','.$altelep;
			$chdl='Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Romi/Tigani|Altele';
			$chl=$moldovenip.'%|'.$ucrainenip.'%|'.$rusip.'%|'.$gagauzip.'%|'.$bulgarip.'%|'.$evreip.'%|'.$polonezip.'%|'.$tiganip.'%|'.$altelep;
			$chtt='Nationalitati '.$this->raion->getFullNameDescription();
			$o2b.='<br>';
			//$o2b.='Componenta pe nationalitati:';
			//$o2b.='<br>';
			$o2b.='<div style="text-align:center">';
			$o2b.='<img src="http://chart.apis.google.com/chart?chf=bg,s,eeeeee&chs=580x260&cht=pc&chco='.$chco.'&chd='.$chd.'&chdl='.$chdl.'&chl='.$chl.'&chma=5,5,5,5&chtt='.$chtt.'" width="580" height="260" alt="'.$chtt.'" />';
			$o2b.='</div>';
			
			//http://chart.apis.google.com/chart?chs=400x225&cht=p&chd=t:90.25,0.73,1,0.31,1,0,0,6.29,0.42&chdl=Moldoveni|Ucraineni|Rusi|Gagauzi|Bulgari|Evrei|Polonezi|Tigani|Altele&chl=90.25%25|0.73%25|1%25|0.31%25|1%25|0%25|0%25|6.29%25|0.42%25&chtt=Nationalitati+in+satul+XXXX
			
			
			//}
			$out.=$this->getGroupBoxH3($o2s,$o2b);
		}					
		return $out;
	}	
	function getNews(){
		$out="";			
		$o2s='<a name="9"></a>'.$this->location->getFullNameDescription().' - Stiri:';		
		$n=new News();		
		$o2b=$n->getNewsByLocalitate($this->location->id);	
		return $this->getGroupBoxH3($o2s,$o2b);
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

		$this->setBodyTag('<body onload="MapViewOnMapLoad()">');
		$this->setJavascript("http://maps.google.com/maps/api/js?sensor=false");

		//$this->setBodyTag('<body onload="MapViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));


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
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));

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
		return $this->getGroupBoxH3('<a name="13"></a>Alte date:',$out);
	}
	function getImobilList($raionid,$locationid){
	
		$p=new PropertyView();
		$rs=$p->getPropertiesSnapListView(0,0,0,0,0,$raionid,$locationid,0,0,10);
		return $this->getGroupBoxH3('<a name="10"></a>Cereri si Oferte de Imobile în raza de 10km:',$rs);
	}
	function getDictionar($l){
		$d=new Dictionar();
		$ds=$d->getAll("localitate_id=".$l->id);
		$out="";
		if (count($ds)){			
			//$o1s='<a name="1"></a>'.$ds[0]->denumire;
			$o1s='Descrierea din Dictionarul Geografic al Basarabiei an. 1904 de Zamfir Arbore';
			$o1b=$ds[0]->descriere;
			//$o1f='<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$dictionarsite."/index.php","action=viewdictionar&id=".$ds[0]->id).'">Mai mult despre dictionar vezi aici</a>';
			$o1f='<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$dictionarsite."/index.php").'">Mai mult despre dictionar vezi aici</a>';
			$out.=$this->getGroupBoxH3($o1s,$o1b,$o1f);
		}
		return $out;
	}
	function getTopNames($l){
		$n=new Nume();
		$ns=$n->getTopNamesByLocation($l->id);
		$out="";
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;">';
		$out.='<tr><th style="width:20%;">Nr de ordine</th><th style="width:50%;">Nume de familie</th><th style="width:30%;text-align:center">Numarul total de familii</th></tr>';		
		if (count($ns)!=0){			
			$c=1;
			foreach($ns as $n){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted(Config::$numesite."/index.php","action=viewnume&id=".$n->id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$n->name.'</a></td><td>'.$n->counter.'</td></tr>';
				$c=$c+1;	
			}
		}
		$out.="</table>";
		$out.="</div>";		
		
		return $this->getGroupBoxH2("<a name=\"12\"></a>Top 50 Nume de Familii cele mai populare",$out);
	}
	function getContacts($l){
		
		$ns=$l->getContacts();
		$out="";
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;">';
		$out.='<tr><th style="width:20%;">Nr de ordine</th><th style="width:50%;">Contact</th><th style="width:30%;text-align:center">Telefon</th></tr>';
		if (count($ns)!=0){
			$c=1;
			foreach($ns as $n){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				//$url=$this->getUrlWithSpecialCharsConverted(Config::$numesite."/index.php","action=viewnume&id=".$n->id);
				$tmp="";
				if ($n->SubdivizionName!=""){
					$tmp.=$n->SubdivizionName;
				}
				if ($n->SectorName!=""){
					if ($tmp!=""){
						$tmp.=", ".$n->SectorName;
					} else {
						$tmp.=$n->SectorName;
					}
				}
				if ($tmp!=""){
					$tmp="(".$tmp.")";
				}
					
				$out.='<tr><td>'.$c.'</td><td>'.$n->type.' '.$tmp.'</a></td><td>0-'.$n->phoneprefix.'-'.$n->phonenumber.'</td></tr>';
				$c=$c+1;
			}
		}
		$out.="</table>";
		$out.='<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$telefoanesite."/index.php","action=viewprefixbylocation&id=".$this->id).'">Mai multe contacte vezi aici</a>';
		$out.="</div>";
	
		return $this->getGroupBoxH2("<a name=\"11\"></a>Contacte, Telefoane a celor mai importante institutii sociale",$out);
	}																			
	function getPopulationInTime(){
		$out="";
		$o2s='';
		$o2b='';
		if (($this->location->p>0)){
			$d=new Dictionar();
			$ds=$d->getAll("localitate_id=".$this->location->id);
			$out="";
			if (count($ds)){			

				$pop1904=$ds[0]->populatie;
				$pop2004=$this->location->p;
				
				if ((!empty($pop1904))&&(!empty($pop2004))){

					$min=$pop1904=$ds[0]->populatie;
					$max=$pop2004=$this->location->p;
						
					
					if ($pop2004<$pop1904){
						$max=$min;
						$min=$pop2004;
					}
					
					$o2s=$this->location->getFullNameDescription().' - Evolutia numarului de locuitori in ultimii 100 ani:';
					
					//$o2b.='Componenta pe nationalitati:';
					$o2b.='<br>';
					$o2b.='<div class="groupboxtable">';
					$o2b.='<table style="width:100%;">';
					$o2b.='<tr><th>Anul</th><th>Numar de Locuitori</th><th>Sursa</th></tr>';
					$o2b.='<tr><td>2004</td><td style="text-align:center;">'.number_format($pop2004, 0, ',', ' ').'</td><td style="text-align:left;">Conform recensamintului din anul 2004</td></tr>';
					$link='<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$dictionarsite."/index.php","action=viewdictionar&id=".$ds[0]->id).'">Vezi aici</a>';
					$o2b.='<tr><td>1904</td><td style="text-align:center;">'.number_format($pop1904, 0, ',', ' ').'</td><td style="text-align:left;">Conform Dictionarului Geografic an. 1904 de Zamfir Arbore '.$link.'</td></tr>';
					$o2b.='</table>';
					$o2b.='</div>';
					$o2b.='<br>';
					
					$o2b.='<div style="text-align:center">';
					$chxl='0:|an.+1904|an.+2004|1:|'.$pop1904.'+loc.|'.$pop2004.'+loc.';
					$chxp='0,1904,2004|1,'.$pop1904.','.$pop2004;
					$chxr='0,1904,2004|1,0,'.($pop2004+$pop1904);
					$chds='1904,2004,0,'.($pop2004+$pop1904);
					$chd='t:1904,2004|'.$pop1904.','.$pop2004;
					$chtt='Evolutia numarului de locuitori in ultimii 100 ani in '.$this->location->getFullName();
					$o2b.='<img src="//chart.googleapis.com/chart?chf=bg,s,EEEEEE&chxl='.$chxl.'&chxp='.$chxp.'&chxr='.$chxr.'&chxs=0,000000,14,0,lt,000000|1,000000,14,0,lt,000000&chxt=x,y&chs=580x360&cht=lxy&chco=3D7930&chds='.$chds.'&chd='.$chd.'&chg=10,10,1,1&chls=3&chma=10,10,10,10&chm=B,C5D4B5BB,0,0,0,1&chtt='.$chtt.'&chts=000000,16" width="580" height="360" alt="" />';
					
					$o2b.='</div>';
					
					//http://chart.googleapis.com/chart?chxl=0:|an.+1904|an.+2004|1:|1090+loc.|3450+loc.&chxp=0,1904,2004|1,1090,3450&chxr=0,1904,2004|1,0,3450&chxs=0,676767,11.5,0,lt,676767|1,676767,12.5,0,lt,676767&chxt=x,y&chs=580x360&cht=lxy&chco=3D7930&chds=1904,2004,0,3450&chd=t:1904,2004|1090,3450&chg=10,10,1,1&chls=4&chma=10,10,10,10&chm=B,80C65A,0,0,0,1&chtt=Populatia+
				}
			}
	
		}
		$out.=$this->getGroupBoxH3($o2s,$o2b);
		return $out;
	}
	function showElectoralPreferences(){
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
}
$n=new IndexLocationsWebPage();
?>
