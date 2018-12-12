<?php
require_once(__DIR__ . '/../main/loader.php');

class VideoWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setCSS("style/maps.css");
		$this->setJavascript("js/scripts.js");
		$this->create();		
	}
	function actionDefault(){
		$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));		
		$this->setCenterContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageRaioaneTitle"),$this->getMain()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));		
		$this->show();
	}	

	function show(){
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

	function getRightMenu(){
		$out='<ul>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">'.$this->getConstants("IndexLocationsWebPageRaioaneTitle").'</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=vieworase").'" >'.$this->getConstants("IndexLocationsWebPageOraseTitle").'</a></li>';
		$out.='</ul>';
		return $out;		
	}	
	function getMain(){
		$out.='<div id="container">';
		$out.='<div id="left" style="width:204;">';
		$out.='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php#1" title="Populatia">Lista de Municipii si Raioane</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista de Orase</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai mari sate</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai mici sate</a></li>';		
		$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai jos amplasate localitati</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai sus amplasate localitati</a></li>';		
		$out.='<li><a href="index.php#3" title="Imobil">Lista celor 30 cele mai populate Localitati</a></li>';				
		$out.='<li><a href="index.php#4" title="Imagini">Lista celor 30 cele mai putin populate Localitati</a></li>';
		$out.='<li><a href="index.php#4" title="Imagini">Populatia pe Municipii si Raioane</a></li>';		
		$out.='</ul>';
		$out.='</div>';
		$out.='</div>';
		$out.='<div id="center" style="width:774;">';
		$out.='<div class="groupbox">';		
		$out.='<h2>Lista de Municipii si Raioane</h2>';			
		$r=new Raion();
		$rs=$r->getAll("","municipiu desc, name");
		if (count($rs)!=0){			
			foreach($rs as $r){
				$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';	
			}
		}
		$out.='</div>';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $out;
	}
}
$n=new VideoWebPage();
?>
