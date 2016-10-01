<?php
require_once(__DIR__ . '/../main/loader.php');


class IndexNumeWebPage extends MainWebPage {

	
	function __construct(){
		parent::__construct();
		$this->setCSS("style/maps.css");
		$this->setLogoTitle("NUME IN REPUBLICA MOLDOVA");		
		$this->create();		
	}
	function actionDefault(){
		$this->setTitle("NUME IN REPUBLICA MOLDOVA");
		$this->setLeftContainer($this->getLeftMenu());
		$this->setCenterContainer($this->getGroupBoxH2("Top 100 Nume de Familii cele mai populare",$this->getMain()));
		$this->setRightContainer($this->getSearchNume());
		$this->show();
	}
	function actionViewTop100NamesGeograficlyLocated(){
		$this->setTitle("NUME IN REPUBLICA MOLDOVA");
		$this->setLeftContainer($this->getLeftMenu());
		$this->setCenterContainer($this->getGroupBoxH2("Top 100 Nume de Familii cele mai raspindite geografic",$this->getTop100NamesGeograficlyLocated()));
		$this->setRightContainer($this->getSearchNume());
		$this->show();
	}
	function actionViewTop100Prenume(){
		$this->setTitle("NUME IN REPUBLICA MOLDOVA");
		$this->setLeftContainer($this->getLeftMenu());
		$this->setCenterContainer($this->getGroupBoxH2("Top 100 cele mai populare prenume",$this->getTop100Prenume()));
		$this->setRightContainer($this->getSearchNume());
		$this->show();
	}		
	function actionViewNume(){
		if (isset($this->id)){
			$n=new Nume();
			$n->loadById($this->id);
			$this->nume=$n;		
		} else{			
			$this->redirect(Config::$numesite);
		}
		$n->count();
		$this->setTitle('Familia '.$this->nume->name.' din Republica Moldova ');
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageTitle").' '.$this->raion->getFullNameDescription());	
		$this->setCenterContainer($this->getNameDescription());	
		$this->setCenterContainer($this->getMapView());
		$this->setCenterContainer($this->getTop100Locations());
		$c='<a name="11"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'f',$n->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenuNume()));
		$this->setLeftContainer($this->getSearchNume());
		$this->setRightContainer($this->getLeftMenu());
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
		return Nume::getTopFamiliiList($this);
	}
	function getTop100NamesGeograficlyLocated(){
		return Nume::getTopFamiliiAmplasateGeograficList($this);
	}
	function getTop100Prenume(){
		return Prenume::getTopPrenumePopulareList($this);
	}
	function getTop100Prenume1(){
		$n=new Prenume();
		$ns=$n->getAll("","suma desc","0","100");
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;">';
		//$out.='<tr><th style="width:20%;">Nr</th><th style="width:50%;">Prenume</th><th style="width:30%;text-align:center">Oameni cu acest nume</th></tr>';
		$out.='<tr><th style="width:20%;">Nr</th><th style="width:50%;">Prenume</th>';
		if (count($ns)!=0){
			$c=1;
			foreach($ns as $n){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$n->id);
				$out.='<tr><td>'.$c.'</td><td>'.$n->name.'</td></tr>';
				//$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$n->name.'</a></td><td>'.$n->suma.'</td></tr>';
				$c=$c+1;
			}
		}
		$out.="</table>";
		$out.="</div>";
	
		return $out;
	}	
	function getMenuNume(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$this->id."#2").'">Distributia pe Harta</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$this->id."#3").'">Top Localitati</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$this->id."#4").'">Forum/Comentarii</a></li>';
		$out.='</ul>';
		return $out;
	}
												
	function getLeftMenu(){
		$outm='<ul>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'" title="Populatia">Lista si numarul de Municipii</a></li>';
		$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Top 100 cele mai populare nume de familie</a></li>';
		$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtop100namesgeograficlylocated").'">Top 100 cele mai raspindite geografic nume de familie</a></li>';
		$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtop100prenume").'">Top 100 cele mai populare prenume</a></li>';
		//$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Familii unicale in RM</a></li>';
		//$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Top 100 cele mai scurte familii</a></li>';
		//$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Top 100 cele mai lungi familii</a></li>';
		//$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Lista de familii dupa alfabet</a></li>';
		//$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Lista de prenume dupa alfabet</a></li>';
		$outm.='</ul>';
		$out="";
		$out.=$this->getGroupBoxH3("Referinte Utile: ",$outm);
		$out.=$this->getGroupBoxH3("Despre Date:",$this->getInfo());
		$out.=$this->getGroupBoxH3("Info Nume de Familii:",$this->getInfoNume());
		$out.=$this->getGroupBoxH3("Info Prenume:",$this->getInfoPrenume());
		return $out;		
	}
	function getInfo(){
		$out='Datele sunt din Cartea de telefoane anul 2007';
		return $out;
	}
	function getInfoNume(){
		$out='Numarul total de nume de familii este de: <br><div style="text-align:center;width:100%;font-size:x-large;font-weight: bold;">73,118</div>';
		return $out;
	}
	function getInfoPrenume(){
		$out='Numarul total de prenume este de: <br><div style="text-align:center;width:100%;font-size:x-large;font-weight: bold;">9,040</div>';
		return $out;
	}		
	function getNameDescription(){

		$out="";
		$o1s='<a name="1"></a>Familia '.$this->nume->name;
		//$o1b='Numele de familie '.$this->nume->name.' este pe locul XXX dupa popularitate si pe locul YYY dupa raspindirea geografica, conform cartii de telefoane exista <b>'.$this->nume->getFamiliesNumber().'</b> familii cu acest nume si sunt presente in <b>'.$this->nume->getLacalitiesNumber().'</b> localitati din RM, vezi mai jos';
		$o1b='Conform datelor din cartea de telefoane (anul 2007), numele de familie <b>'.$this->nume->name.'</b> se intilneste in <b>'.$this->nume->getLacalitiesNumber().'</b> localitati din Republica Moldova in care exista <b>'.$this->nume->getFamiliesNumber().'</b> familii cu acest nume.';
		$out.=$this->getGroupBoxH3($o1s,$o1b);


		return $out;
	}
	function getSearchNume(){
		return $this->getGroupBoxH3("Cauta Nume de Familie:",$this->getSearchName());	
	}
	
	function getMapView(){
		$m=new Map();
		$m->lat="0";
		$m->zoom="7";
		$m->centerlat="47.0200004577636719";
		$m->centerlng="28.5458335876464844";
		return $this->getGroupBoxH3('Distributia familiei "'.$this->nume->name.'" pe Harta in forma de HeatMap:',$this->getMap($m));		
	}
	function getMap($out=''){
	
		$this->setBodyTag('<body onload="initialize('.$this->nume->id.')">');
		$this->setJavascript("http://maps.google.com/maps/api/js?sensor=false");
		$this->setJavascript("https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js");
		$this->setJavascript("http://maps.googleapis.com/maps/api/js?libraries=geometry&libraries=visualization&sensor=false");
		$this->setJavascript(Config::$commonsite."/js/nume.js");
	
		$out='<div id="map" style="width: 100%;height: 500px;border:1px solid #777777;margin-top: 2px;"></div>';
		//$out.='<div id="dir" style="width: 100%;border:1px solid #777777;margin-top: 2px;"></div>';
	
		return $out;
	}	
	function getTop100Locations(){
		return Nume::getTop100LocationsByFamily($this,$this->nume->id);
	}

}
$n=new IndexNumeWebPage();
?>