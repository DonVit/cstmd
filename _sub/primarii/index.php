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
		$this->setLogoTitle("PRIMARIILE DIN REPUBLICA MOLDOVA");		
		$this->create();		
	}
	function actionDefault(){
		//$this->setLogoTitle("Localitati din Republica Moldova");
		$this->setTitle("Lista primariilor dupa Raioane si Municipii");
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));	
		$this->setCenterContainer($this->getGroupBoxH2("Lista primariilor dupa Raioane si Municipii",$this->getMain()));
		$this->setRightContainer($this->getGroupBoxH3("Cauta Primarie:",$this->getSearchPrimarie()));
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
		$this->setTitle('Primariile din '.$this->raion->getFullNameDescription());
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageTitle").' '.$this->raion->getFullNameDescription());	
		$this->setCenterContainer($this->getRaion());
		//$this->setCenterContainer($this->getRaionPopulation());
		//$this->setCenterContainer($this->getRaionPhotos());		
		$c='<a name="5"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'x',$r->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenuRaion()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Primarie:",$this->getSearchPrimarie()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));
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
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageTitle").' '.$this->location->getFullNameDescription());
		//$this->setLogoTitle("Localitati din Republica Moldova");			
		$this->setCenterContainer($this->getLocalitate());
		$this->setCenterContainer($this->getPrimarieConsilieri());
		//$this->setCenterContainer($this->getPopulation());
		$this->setCenterContainer($this->getPrimariiInJur());
		$this->setCenterContainer($this->getLocalitatiDistance());	
		//$this->setCenterContainer($this->getPhotos());	
		//$this->setCenterContainer($this->getPanoramioFotos($l));
		//$this->setCenterContainer($this->getLocalitatiCuAcelasNume());	
		$this->setCenterContainer($this->getContacts($l));
		$this->setCenterContainer($this->getNews());
		
		$c='<a name="9"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'y',$l->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenuPrimarie()));
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Primarie:",$this->getSearchPrimarie()));
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
		$out.='<div style="clear: both;"/></div>';
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
				$url=$this->getUrl("index.php","action=viewraion&id=".$r->id);
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
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewraion&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewraion&id=".$this->id."#2").'">Primarii in componenata</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewraion&id=".$this->id."#3").'">Harta</a></li>';
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewraion&id=".$this->id."#4").'">Populatia</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewraion&id=".$this->id."#5").'">Forum/Comentarii</a></li>';
		$out.='</ul>';
		return $out;
	}
	function getMenuPrimarie(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewprimarie&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewprimarie&id=".$this->id."#2").'">Localitati in componenta</a></li>';	
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewprimarie&id=".$this->id."#3").'">Date geografice</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewprimarie&id=".$this->id."#4").'">Pozitia pe Harta</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewprimarie&id=".$this->id."#5").'">Lista Consilierilor</a></li>';		
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewprimarie&id=".$this->id."#6").'">Primarii in raza de 10 km</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewprimarie&id=".$this->id."#7").'">Telefoane</a></li>';
		
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewlocalitate&id=".$this->id."#6").'">Foto/Imagini in raza de 10 km</a></li>';		
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewlocalitate&id=".$this->id."#7").'">Populatia</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewprimarie&id=".$this->id."#8").'">Stiri</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php","action=viewprimarie&id=".$this->id."#9").'">Forum/Comentarii</a></li>';
		
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				//$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Nr. Tigani</th><th>% Tigani</th><th>Total Populatie</th></tr>";
			foreach($ls as $l){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$urlr=$this->getUrl("index.php","action=viewraion&id=".$l->raion_id);
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
		//$out.='<li><a href="'.$this->getUrl("index.php").'" title="Populatia">Lista si numarul de Municipii</a></li>';
		$out.='<li><a href="'.$this->getUrl("index.php").'" >Lista primariilor dupa Raioane si Municipii</a></li>';
		$out.='<li><a href="'.$this->getUrl(Config::$locationssite."/index.php").'">'.$this->getConstants("IndexLocationsWebPageRaioaneTitle").'</a></li>';
		$out.='<li><a href="'.$this->getUrl(Config::$locationssite."/index.php","action=vieworase").'" >'.$this->getConstants("IndexLocationsWebPageOraseTitle").'</a></li>';
		$out.='<li><a href="'.$this->getUrl(Config::$locationssite."/index.php","action=viewtopsuslocalitati").'" >Lista a 50 cele mai sus amplasate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrl(Config::$locationssite."/index.php","action=viewtopjoslocalitati").'" >Lista a 50 cele mai jos amplasate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrl(Config::$locationssite."/index.php","action=viewtopuppoplocalitati").'" >Lista a 50 cele mai populate localitati din Moldova</a></li>';
		$out.='<li><a href="'.$this->getUrl(Config::$locationssite."/index.php","action=viewtopdownpoplocalitati").'" >Lista a 50 cele mai putin populate localitati din Moldova</a></li>';
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewtoplocalitatiucraineni").'" >Lista localitatilor populate cu Ucraineni din Moldova</a></li>';
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewtoplocalitatirusi").'" >Lista localitatilor populate cu Rusi in Moldova</a></li>';
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewtoplocalitatigagauzi").'" >Lista localitatilor populate cu Gagauzi in Moldova</a></li>';
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewtoplocalitatibulgari").'" >Lista localitatilor populate cu Bulgari in Moldova</a></li>';
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewtoplocalitatievrei").'" >Lista localitatilor populate cu Evrei in Moldova</a></li>';
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewtoplocalitatipolonezi").'" >Lista localitatilor populate cu Polonezi din Moldova</a></li>';
		//$out.='<li><a href="'.$this->getUrl("index.php","action=viewtoplocalitatitigani").'" >Lista localitatilor populate cu Tigani din Moldova</a></li>';		
		//$out.='<li><a href="'.$this->getUrl("index.php","action=vieworase").'" title="Stiri">Lista a 30 cele mai sus amplasate localitati</a></li>';
		//$out.='<li><a href="'.$this->getUrl("index.php","action=vieworase").'" title="Stiri">Lista a 30 cele mai aglomerate localitati, de ex in raza de 10 km</a></li>';	
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

		$ls=$this->raion->getPrimarii();
		
		$o1s='<a name="1"></a>'.$this->raion->getFullNameDescription();
		$rm=($this->raion->municipiu==1)?("Municipiului"):("Raionului");	
		$o1b=$this->raion->getFullNameDescription().' este situat la latitudinea '.$this->raion->getLatShort().' longitudinea '.$this->raion->getLngShort().' si altitudinea de '.$this->raion->elevation.' metri fata de nivelul marii. In componenta '.$rm.' intra '.count($ls).' localitati. ';
		if (($this->raion->id!=500)&&($this->raion->id!=700)){
			$o1b.='Conform recensamintului din anul 2004 popuplatia este de '.number_format($this->raion->getPopulation(), 0, ',', ' ').' locuitori.';
		}
		$out.=$this->getGroupBoxH3($o1s,$o1b);

		$o2s='<a name="2"></a> Primarii in componenta '.$this->raion->getFullNameDescription().':';	
		$o2b='<div class="groupboxtable">';
		$o2b.='<table style="width:100%;">';
		$o2b.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th></tr>";
		$c=1;
		foreach($ls as $l){	
			$url=$this->getUrl("index.php","action=viewprimarie&id=".$l->id);
			$o2b.='<tr><td style="text-align:center;">'.$c.'</td><td><a href="'.$url.'">'.$l->getPrimariaName().'</a></td></tr>';
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
		$url=$this->getUrl(Config::$locationssite."/index.php","action=viewraion&id=".$this->raion->id);
		$o1s='<a name="1"></a>'.$this->location->getPrimariaName().' - Scurta descriere:';
		$o1b=$this->location->getPrimariaName().' este  in  componenta <a href='.$url.'>'.$this->raion->getFullNameDescription().'</a> situata la latitudinea '.$this->location->getLatShort().' longitudinea '.$this->location->getLngShort().' si altitudinea de '.$this->location->elevation.' metri fata de nivelul marii.';
		
		$primar=$this->location->getPrimarName();
		if (!is_null($primar)){
			$o1b.=' Primarul este '.$primar->prenume.' '.$primar->nume.' '.$primar->partid_text.' ('.$primar->partid.') ales din '.$primar->tur.'. ';	
		} 
		
		if ($this->location->p>0){
			if ($this->location->isComuna()){
				$o1b.='Conform recensamintului din anul 2004 populatia comunei este de '.number_format($this->location->getPrimariePopulation(), 0, ',', ' ').' locuitori. ';
			} else {
				$o1b.='Conform recensamintului din anul 2004 populatia este de '.number_format($this->location->getPrimariePopulation(), 0, ',', ' ').' locuitori. ';
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
		
		$ls=$this->location->getChildLocations();
		if (($this->location->raion_center==0) and (count($ls)!=0)){				
			$o2s='';
			$o2b='';
			$o2s.='<a name="2"></a>'.$this->location->getPrimariaName().' - Localitati in componenta:';
			$o2b.='<div class="groupboxtable">';					
			$o2b.='<table style="width:100%;">';
			$o2b.='<th>Denumirea localitatii</th><th>Numarul de locuitori</th>';
			foreach($ls as $l){
				$url=$this->getUrl(Config::$locationssite."/index.php","action=viewlocalitate&id=".$l->id);
				$o2b.='<tr><td><a href="'.$url.'">'.$l->getFullName().'</a><td>'.$l->p.'</td></td></tr>';
				//$o2b.='<a href="?lc='.$l->id.'">'.$l->getFullName().'</a><br>';	
			}
			$o2b.='</table>';
			$o2b.='</div>';
			$out.=$this->getGroupBoxH3($o2s,$o2b);
		}			

// 		$ls=$this->location->getPrimarieConsilieri();
// 		if (count($ls)!=0){
// 			$o2s='';
// 			$o2b='';
// 			$o2s.='<a name="2"></a>'.$this->location->getPrimariaName().' - Lista consilierilor:';
// 			$o2b.='<div class="groupboxtable">';
// 			$o2b.='<table style="width:100%;">';
// 			$o2b.='<th>Numele Cosilierui</th><th>Partidul</th>';
// 			foreach($ls as $l){
// 				$url=$this->getUrl(Config::$locationssite."/index.php","action=viewlocalitate&id=".$l->id);
// 				$o2b.='<tr><td>'.$l->prenume.' '.$l->nume.'</td><td>'.$l->partid.'</td></tr>';
// 				//$o2b.='<a href="?lc='.$l->id.'">'.$l->getFullName().'</a><br>';
// 			}
// 			$o2b.='</table>';
// 			$o2b.='</div>';
// 			$out.=$this->getGroupBoxH3($o2s,$o2b);
// 		}		
		
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
		if (count($ls)!=0){
			$o2s='';
			$o2b='';
			$o2s.='<a name="5"></a>'.$this->location->getPrimariaName().' - Lista Consilierilor:';
			$o2b.='<div class="groupboxtable">';
			$o2b.='<table style="width:100%;">';
			$o2b.='<th>Nr</th><th>Numele Cosilierui</th><th>Partidul</th>';
			$cnt=1;
			foreach($ls as $l){
				$o2b.='<tr><td>'.$cnt.'</td><td>'.$l->prenume.' '.$l->nume.'</td><td>'.$l->partid.'</td></tr>';
				$cnt++;
			}
			$o2b.='</table>';
			$o2b.='</div>';
			$out.=$this->getGroupBoxH3($o2s,$o2b);
		}
	

		$ls=$this->location->getPrimarieConsilieriPerPartid();
		$c=$this->location->getPrimarieConsilieriTotal();
		
		if (count($ls)!=0){
			$o2s='';
			$o2b='';
			$o2s.='<a name="2"></a>'.$this->location->getPrimariaName().' - Lista Partidelor in Consiliu:';
			$o2b.='<div class="groupboxtable">';
			$o2b.='<table style="width:100%;">';
			$o2b.='<th>Partidul</th><th>Numarul de consilieri</th><th>Procent %</th>';
			//$lp='';
			//$lc='';

			$chd='t:';
			$chdl='';
			$chl='';
			$chco='';
			$cnt=1;
			foreach($ls as $l){
				if ($cnt==1){
					$chco.=$this->getPartidColor($l->partid);
					$chd.=round($l->c*100/$c,2);
					$chdl.=$l->partid;
					$chl.=round($l->c*100/$c,2);
				} else {
					$chco.='|'.$this->getPartidColor($l->partid);
					$chd.=','.round($l->c*100/$c,2);
					$chdl.='|'.$l->partid;
					$chl.='%|'.round($l->c*100/$c,2);
						
				}
				$o2b.='<tr><td>'.$l->partid.'</td><td>'.$l->c.'</td><td>'.round($l->c*100/$c,2).'</td></tr>';
				$cnt++;
			}
			$o2b.='</table>';
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
			$o2b.='<img src="http://chart.apis.google.com/chart?chf=bg,s,eeeeee&chs=580x260&cht=pc&chco='.$chco.'&chd='.$chd.'&chdl='.$chdl.'&chl='.$chl.'&chma=5,5,5,5&chtt='.$chtt.'" width="580" height="260" alt="'.$chtt.'" />';
			$o2b.='</div>';		
		
			$out.=$this->getGroupBoxH3($o2s,$o2b);
		}
		return $out;
	}
	function getPartidColor($partid){
			$rtcl="008000";
			$partidcolors=array("PCRM"=>"FF0000","PLDM"=>"008000","PDM"=>"224499","PL"=>"ADD8E6","CI"=>"A52A2A","PPCD"=>"FFA500","PRM"=>"800080","PSD"=>"FF6464","BEFT"=>"FFB272","PNL"=>"FFFF32","PCNM"=>"B23232","PEAVM"=>"B29D32","PPNT"=>"88B232","PPPSRM"=>"942914","PM"=>"C65B46","MSPR"=>"801500","PPDM"=>"B2B232","UCM"=>"B25D32","PE"=>"FF32FF","PPPM"=>"FF3377","PLD"=>"33FFFF");
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
				$lurl=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$rurl=$this->getUrl("index.php","action=viewraion&id=".$l->getRaion()->id);
				$o2b.=round($loc->distance).' km - distanța directă pînă la <a href="'.$lurl.'">'.$l->getFullNameDescription().'</a> din <a href="'.$rurl.'">'.$l->getRaion()->getFullNameDescription().'</a> - <a href="'.$this->getUrl(Config::$distantesite."/index.php","from=".$this->location->id."&to=".$loc->id).'">vezi pe harta</a><br>';
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
				$lurl=$this->getUrl("index.php","action=viewprimarie&id=".$l->id);
				$rurl=$this->getUrl("index.php","action=viewraion&id=".$l->getRaion()->id);
				$o2b.=round($loc->distance).' km - distanța directă pînă la <a href="'.$lurl.'">'.$l->getPrimariaName().'</a> din <a href="'.$rurl.'">'.$l->getRaion()->getFullNameDescription().'</a> - <a href="'.$this->getUrl(Config::$distantesite."/index.php","from=".$this->location->id."&to=".$loc->id).'">vezi pe harta</a><br>';
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
				$furl=$this->getUrl("index.php","action=viewlocalitate&id=".$loc->from_id);
				$turl=$this->getUrl("index.php","action=viewlocalitate&id=".$loc->to_id);
				//$turl=$this->getUrl("index.php","action=viewraion&id=".$l->getRaion()->id);
				//$o2b.=round($loc->distance).' km - distanța directă pînă la <a href="'.$turl.'">'.$loc->to_name.'</a> din <a href="'.$rurl.'">'.$l->getRaion()->getFullNameDescription().'<br></a>';
				$o2b.='Distanța directă pîna în <a href="'.$turl.'">'.$l->getFullName().'</a> este de - '.round($loc->distance).' km <a href="'.$this->getUrl(Config::$distantesite."/index.php","from=".$loc->from_id."&to=".$loc->to_id).'">vezi pe harta</a><br>';
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
				$lurl=$this->getUrl("index.php","action=viewlocalitate&id=".$l->id);
				$rurl=$this->getUrl("index.php","action=viewraion&id=".$l->getRaion()->id);
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
		$o2s='<a name="8"></a>'.$this->location->getPrimariaName().' - Stiri:';		
		$n=new News();		
		$o2b=$n->getNewsByLocalitate($this->location->id);	
		return $this->getGroupBoxH3($o2s,$o2b);
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
	function _getMap($centerlat,$centerlng,$zoom,$maptype,$lat,$lng,$title){
		$out="";
		//$out.="<table width=\"100%\">";
		//$out.="<tr>";
		//$out.="<td align=\"center\">";
		$out.="<input id=\"centerlat\" name=\"centerlat\" type=\"hidden\" value=\"$centerlat\" />";
		$out.="<input id=\"centerlng\" name=\"centerlng\" type=\"hidden\" value=\"$centerlng\" />";
		$out.="<input id=\"maptype\" name=\"maptype\" type=\"hidden\" value=\"$maptype\" />";
		$out.="<input id=\"zoom\" name=\"zoom\" type=\"hidden\" value=\"$zoom\" />";
		$out.="<input name=\"lat\" type=\"hidden\" id=\"lat\" readonly=\"true\" class=\"inptdisabled\" value=\"$lat\" /> ";
		$out.="<input name=\"lng\" type=\"hidden\" id=\"lng\" readonly=\"true\" class=\"inptdisabled\" value=\"$lng\" />";
		//$out.="</td>";
		//$out.="</tr>";
		//$out.="<tr>";
		//$out.="<td align=\"center\">";
		$out.="<div id=\"map\" style=\"width: 100%; height: 400px\"></div>";
		//$out.="</td>";
		//$out.="</tr>";
		//$out.="</table>";	
		return $out;
	}
	function _getLinks(){
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php#1" title="Populatia">Populatia</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Stiri</a></li>';
		$out.='<li><a href="index.php#3" title="Imobil">Imobil</a></li>';				
		$out.='<li><a href="index.php#4" title="Imagini">Imagini</a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $out;
	}
	function _getAddLinks(){
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php#1" title="Populatia">Adauga Imobil</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Adauga Comentariu</a></li>';
		$out.='<li><a href="index.php#3" title="Imobil">Imobil</a></li>';				
		$out.='<li><a href="index.php#4" title="Imagini">Imagini</a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $out;
	}	
	function _getCenterContainer1(){
		$out="";
		if (isset($this->location)){
			$out.=$this->getLocationGroup();
		}else{
			$out.=$this->getRaionGroup();
		}	
		return $out;
	}
	function _getLocationGroup(){
		$out="";
		$out='<div class="groupbox">';		
		$out.='<h2>'.$this->location->getFullNameDescription().'</h2>';
		$out.='<h3><a href="?raionid='.$this->location->raion_id.'">'.$this->location->getRaion()->getFullNameDescription().'</a></h3>';		
		$out.='</div>';	
		$ls=$this->location->getLocations();
		if (count($ls)!=0){		
			$out.='<div class="groupbox">';		
			$out.='<h3>Localitati sub administrare:</h3>';		
			foreach($ls as $l){
				$out.='<a href="?locationid='.$l->id.'">'.$l->getFullName().'</a><br>';	
			}
			$out.='</div>';		
		}			
		
		
		$out.=$this->getMap($this->location->lat,$this->location->lng,12,3,$this->location->lat,$this->location->lng, $this->location->getFullNameDescription());
		return $out;
	}
	function _getRaionGroup(){
		$out="";
		$out='<div class="groupbox">';		
		$out.='<h2>'.$this->raion->getFullNameDescription().'</h2>';	
		$out.='</div>';		
		$out.='<div class="groupbox">';		
		$out.='<h3>Localitati in '.$this->raion->getFullNameDescription().':</h3>';	
		$ls=$this->raion->getLocations();	
		foreach($ls as $l){
			$out.='<a href="?locationid='.$l->id.'">'.$l->getFullName().'</a><br>';	
		}
		$out.='</div>';		

		$out.=$this->getMap($this->raion->lat,$this->raion->lng,12,3,$this->raion->lat,$this->raion->lng,$this->raion->getFullNameDescription());
		return $out;
	}						
	
	function _getLocationDropDown1($localitateid=0){
		$l=new Location();
		$ls=$l->doSql("SELECT l.id as lid, l.name as lname, r.id as rid, r.name as rname FROM `localitate` as l inner join raion as r on l.raion_id=r.id where l.lat=0 order by r.id");
		$out="<select id=\"localitate_id\" name=\"localitate_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnDropDownChange()\">";
		//if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
		//	$out.= "<option value=\"0\">Nu Conteaza</option>";
		//}
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->lid==$localitateid){
					$out.= "<option value=".$ll->lid." selected>".$ll->rname."->".$ll->lname."</option>";
				} else {
					$out.= "<option value=".$ll->lid.">".$ll->rname."->".$ll->lname."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}
	function _getNodeDropDown1($localitateid=0){
		$l=new Location();
		$ls=$l->doSql("select n.id, n.v from `node_tags` as n inner join localitate as l on l.id=$localitateid and n.v=l.name WHERE n.k='name'");
		$out="<select id=\"node_id\" name=\"node_id\" class=\"select\" size=\"5\" onchange=\"javascript:WizardOnDropDownChange()\">";
		//if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
		//	$out.= "<option value=\"0\">Nu Conteaza</option>";
		//}
		if (!is_null($ls)){
			foreach($ls as $ll){				
				$out.= "<option value=".$ll->id.">".$ll->id."->".$ll->v."</option>";
			}
		}
		$out.="</select>";
		return $out;
	}
	function _getMap1(){
		$out='';
		$out.='<form id="poiform" name="poiform" method="post">';		
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->map->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->map->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->map->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->map->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->map->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->map->lng.'"/>';
		$out.='<input name="title" type="hidden" id="title"  readonly="true" class="inptdisabled" value="'.$this->map->title.'"/>';		
		$out.='<input name="description" type="hidden" id="description"  readonly="true" class="inptdisabled" value="'.$this->map->description.'"/>';		
		$out.='<div id="map"></div>';
		$out.='</form>';
		return $out;
	}
	function _getLocation(){	
		if (isset($this->lsearch)){
			$l=new Location();
			$r=new Raion();
			$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` WHERE `localitate`.deleted=0 AND `localitate`.name like '%".$this->lsearch."%' LIMIT 0,30");
			$lsrs="";
			if (!is_null($ls)){
			foreach ($ls as $v){
				$l->loadById($v->location_id);
				$r->loadById($l->raion_id);
				$lsrs.="<a href=".$this->getBaseName()."?raionid=".$r->id."&locationid=".$l->id.">".$r->getFullName()."->".$l->getFullName()."</a><br>";
			}
			}else {
				$lsrs="Localitati *".$this->lsearch."* nu exista!";
			}
		}
		$out='<div id="location">';
		$out.='<form id="locationform" name="locationform" method="post">';
		$out.='<div id="location-raion">Municipiu/Raion:'.$this->getRaionDropDown(User::getImobilCurrentRaion()->id).'</div>';
		//if (User::getImobilCurrentRaion()->id!=0){
			$out.='<div id="location-localitate">Oras/Sat:'.$this->getLocationDropDown(User::getImobilCurrentRaion()->id,User::getImobilCurrentLocation()->id).'</div>';
		//}
		//if (User::getImobilCurrentLocation()->id!=0){
		//	$out.='<div id="location-sector">Sector:'.$this->getSectorDropDown(User::getImobilCurrentLocation()->id,User::getImobilCurrentSector()->id).'</div>';
		//}
		//$out.='<div id="location-search-label">Cauta Localitate:</div>';
		$out.='<div id="location-search-box">Cauta Localitate:<input type="text" name="lsearch" value="'.$this->lsearch.'"><input type="submit" class="button" value="Cauta"><br>'.$lsrs.'</div>';
		//$out.='<div id="location-search-box"><input type="text" id="lsearch" name="lsearch" value="Cauta Localitate" onblur="if (this.value==\'\') this.value=\'Cauta Localitate\';" onfocus="if (this.value==\'Cauta Localitate\') this.value=\'\';"><input type="submit" class="button" value="Cauta"><br>'.$lsrs.'</div>';
		//$out.='<div id="location-search">'.$lsrs.'</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}	

	function _getRaionDropDown($raionid){
		$r=new Raion();
		$rs=$r->getAll("","`municipiu` desc,`order`,`name`");
		$out="<select id=\"raionid\" name=\"raionid\" class=\"select\" size=\"1\" onchange=\"javascript:FilterOnRaionChange('".($this->getBaseName())."')\">";
		$out.="<option value=\"0\">Toate</option>";
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
	function _getLocationDropDown($raionid,$locationid){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$raionid,"`order`,`oras` desc,`name`");
		$out="<select id=\"locationid\" name=\"locationid\" class=\"select\" size=\"1\" onchange=\"javascript:FilterOnLocationChange('".($this->getBaseName())."')\">";
		$out.="<option value=\"0\">Toate</option>";
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->id==$locationid){
					$out.= "<option value=".$ll->id." selected>".$ll->tip." ".$ll->name_ro."</option>";
				} else {
					$out.= "<option value=".$ll->id.">".$ll->tip." ".$ll->name_ro."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
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
				//$url=$this->getUrl(Config::$numesite."/index.php","action=viewnume&id=".$n->id);
				$out.='<tr><td>'.$c.'</td><td>'.$n->type.' ('.$n->SectorName.')</a></td><td>0-'.$n->phoneprefix.'-'.$n->phonenumber.'</td></tr>';
				$c=$c+1;
			}
		}
		$out.="</table>";
		$out.='<a href="'.$this->getUrl(Config::$telefoanesite."/index.php","action=viewprefixbylocation&id=".$l->id).'">Mai multe contacte vezi aici</a>';
		$out.="</div>";
	
		return $this->getGroupBoxH2("<a name=\"7\"></a>Contacte, Telefoane a celor mai importante institutii sociale",$out);
	}				
												
}
$n=new IndexLocationsWebPage();
?>
