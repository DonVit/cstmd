<?php
require_once(__DIR__ . '/../main/loader.php');
 
class IndexPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$t="Alegeri Presidentiale din REPUBLICA MOLDOVA";		
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->create();		
	}
	function actionDefault(){
		$this->map=User::getCurrentMap();
		$this->map->zoom=2;
		$this->setCSS("style/maps.css");
		$this->show();
	}
	function actionViewSectie(){
	
		$this->map=User::getCurrentMap();
		$this->sectievot=new Sectiidevot();
		if (isset($this->id)){
			if ($this->sectievot->loadById($this->id)){
				$this->sectievot->count();
			}
		}
		$logotitle='Alegeri Parlamentare Moldova';
		$sectia='Sectia Nr. '.$this->sectievot->sectie_nr;
		$title=$logotitle.': '.$sectia.' din '.$this->sectievot->tara.', '.$this->sectievot->localitate;
		$this->setTitle($title);
		$this->setLogoTitle($logotitle);
		$maptitle='<a name="1"></a>'.$sectia.' '.$this->sectievot->localitate;
		if ($this->sectievot->tara=='Moldova'){
			$maptitle.=', '.$this->sectievot->circumscriptie;
		} else {
			$maptitle.=', '.$this->sectievot->tara;
		}
		
		$this->setCSS("style/maps.css");
		$this->setCenterContainer($this->getGroupBoxH1($maptitle,$this->getMap($this->sectievot)));
		//$this->setCenterContainer($this->getGroupBoxH3("Sectia:",$this->sectievot->sectie_nr));
		$this->setCenterContainer($this->getGroupBoxH3('<a name="2"></a>Adresa:',$this->sectievot->adresa));
		$this->setCenterContainer($this->getGroupBoxH3('<a name="3"></a>Hotar:',$this->sectievot->hotar));
		$this->setCenterContainer($this->getGroupBoxH3('<a name="4"></a>Contacte:',$this->sectievot->telefon));
		$this->setCenterContainer($this->showAlegeriPresidentiale161030Rezultate());
		$this->setCenterContainer($this->showAlegeriPresidentiale161030Date());
		$this->setCenterContainer($this->getGroupBoxH3("Alte Date",$this->getSystemDetails()));
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii:",Comment::getComments($this,'s',$this->sectievot->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getRightMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Referinte Utile",$this->getLeftMenu()));
		
	
		$this->showsectie();
	}
	function actionViewMap(){
		
		$this->map=User::getCurrentMap();
		
		if (isset($this->id)){
			$m=new Map();
			if ($m->loadById($this->id)){
				$m->count();
			}
			
			if (isset($m->id)){
				$this->map=$m;
			}
		}	
		$this->setTitle("Alegeri Moldova: ".$this->map->title);
		$this->setCSS("style/maps.css");
		$this->setCenterContainer($this->getGroupBoxH1($this->map->title,$this->getViewMap()));
		$this->setCenterContainer($this->getGroupBoxH3("Descriere",$this->getViewMapDescription()));
		$this->setCenterContainer($this->getGroupBoxH3("Alte Date",$this->getSystemDetails()));
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii:",Comment::getComments($this,'m',$m->id)));
		
		$this->showmap();
	}
	function actionViewPoi(){
	
		$this->map=User::getCurrentMap();
	
		if (isset($this->lat)){
			$m=new Map();
			$m->centerlat=$this->lat;
			$m->centerlng=$this->lng;
			$m->lat=$this->lat;
			$m->lng=$this->lng;
			$m->zoom=16;	
			$m->maptype=0;						
			$m->title='';			
			$this->map=$m;
			
		}
		$this->setTitle("Harti Moldova: ".$this->map->title);
		$this->setCSS("style/maps.css");
		$this->setCenterContainer($this->getGroupBoxH1($this->map->title,$this->getViewPoi()));
		$this->setCenterContainer($this->getGroupBoxH3("Descriere",$this->getViewMapDescription()));
		$this->showmap();
	}			
	function show($html=""){
		$out='<div id="container">';
		$out.=$this->getAllMap();
		//$out.=$this->getLastMaps();
		$out.='</div>';
		MainWebPage::show($out);
	}	
	function showmap(){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="left" class="container left" style="width:98px;">';
		$out.=$this->getLeftContainer();
		$out.='</div>';		
		$out.='<div id="center" class="container center" style="width:800px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right" class="container right" style="width:98px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}	
	function showsectie($out=''){
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
	function getAllMap($out=''){

		$this->setBodyTag('<body onload="MapAlegeriViewOnMapLoad(true)">');
		$this->setJavascript("https://maps.google.com/maps/api/js?sensor=false");
		$this->setJavascript("https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js");
		$this->setJavascript(Config::$commonsite."/js/maps.js");
		$this->setJavascript(Config::$commonsite."/js/controls.js");
		
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
		$out.='<div id="map" style="width: 998px;height: 520px;border:1px solid #777777;margin-top: 2px;"></div>';
		$out.='</form>';
		return $out;
	}
	function getLastMaps($out=''){
		$lastmaps=$this->map->getAll("","id desc","0","20");
		$out='';
		$out.='<div id="maps" style="width: 998px;border:1px solid #777777;margin-top: 2px;">';
		foreach ($lastmaps as $lm){
			$url=$this->getUrlWithSpecialCharsConverted(Config::$mapssite."/index.php","action=viewmap&id=".$lm->id);
			$out.='<div class="newscomment_head" style="font-size:85%;">';
			$out.='<span><a href="'.$url.'"># '.$lm->id.'</a></span>';
			$out.='<span><b> Titlu:</b> '.System::getHTML($lm->title).'</span>';
			$out.='<span><b> Data:</b> '.$lm->data.' </span>';
			$out.='</div>';
			$out.='<div class="newscomment_body">';
			$out.=System::getHTML($lm->description);
			$out.=' <a href="'.$url.'">vezi mai mult</a>';
			$out.='</div>';
		}	
		$out.='</div>';
		$out.='</form>';
		return $out;
	}	
	function getViewMap(){

		$this->setBodyTag('<body onload="MapViewOnMapLoad()">');
		$this->setJavascript("https://maps.google.com/maps/api/js?sensor=false");
		$this->setJavascript(Config::$commonsite."/js/maps.js");
		
		$out='';
		$out='<form id="poiform" name="poiform" method="post">';		
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->map->lat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->map->lng.'"/>';
		
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->map->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->map->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->map->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->map->lng.'"/>';
		$out.='<input name="title" type="hidden" id="title"  readonly="true" class="inptdisabled" value="'.$this->map->title.'"/>';		
		$out.='<input name="description" type="hidden" id="description"  readonly="true" class="inptdisabled" value="'.$this->map->description.'"/>';		
		$out.='<div id="map" style="height: 400px;border:1px solid #777777;	margin-top: 2px;"></div>';
		$out.='</form>';
		return $out;
	}
	function getViewPoi(){
	
		$this->setBodyTag('<body onload="MapViewPoiOnMapLoad()">');
		$this->setJavascript("https://maps.google.com/maps/api/js?sensor=false");
		$this->setJavascript(Config::$commonsite."/js/maps.js");
	
		$out='';
		$out='<form id="poiform" name="poiform" method="post">';
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->map->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->map->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->map->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->map->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->map->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->map->lng.'"/>';
		$out.='<input name="title" type="hidden" id="title"  readonly="true" class="inptdisabled" value="'.$this->map->title.'"/>';
		$out.='<input name="description" type="hidden" id="description"  readonly="true" class="inptdisabled" value="'.$this->map->description.'"/>';
		$out.='<div id="osmmap" style="height: 400px;border:1px solid #777777;	margin-top: 2px;"></div>';
		$out.='<div id="roadmap" style="height: 400px;border:1px solid #777777;	margin-top: 2px;"></div>';
		$out.='<div id="satellitemap" style="height: 400px;border:1px solid #777777;	margin-top: 2px;"></div>';
		$out.='<div id="terrainmap" style="height: 400px;border:1px solid #777777;	margin-top: 2px;"></div>';						
		$out.='</form>';
		return $out;
	}	
	function getViewMapDescription(){
		$out='<div><div class="newscomment_body">'.System::getHTML($this->map->description).'</div></div>';
		if ($this->map->description==""){
			$out='<div><div class="newscomment_body">Nu exista</div></div>';
		}
		return $out;
	}
	function getSystemDetails(){
		$out='<div>';
		$out.='<div id="property-view-dateq" style="float:left">';
		$out.='Data publicarii: '.$this->sectievot->getData();
		$out.='</div>';
		$out.='<div id="property-view-dateq" style="float:right">';
		$out.='Vizualizari: '.$this->sectievot->contor;
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $out;
	}
	function showAlegeriPresidentiale161030Rezultate(){
	
		$o2s='<a name="4"></a>Rezultatele:';
	
		$o2b='<b>Rezultate Tur 2:</b>';
		$o2b.=Alegeri::getAlegeriPresidentialeBySectieAndTur($this,$this->sectievot->sectie_id,2);
		$o2b.='<b>Rezultate Tur 1:</b>';
		$o2b.=Alegeri::getAlegeriPresidentialeBySectieAndTur($this,$this->sectievot->sectie_id,1);
		//$o2b.='<b>Imagini Sursa:</b>';
		//$o2b.=Alegeri::getPresidentialImageUrlByPrimarie($this->location->raion_id,$this->location->id);
		$o2f="Sursa: www.cec.md";
		if (empty($o2b)){
			return "";
		} else {
			return $this->getGroupBoxH3($o2s,$o2b,$o2f);
		}
	}
	function showAlegeriPresidentiale161030Date(){
	
		$o2s='<a name="4"></a>Rezultatele:';
	
		$o2b='<b>Rezultate Tur 2:</b>';
		$o2b.=Alegeri::getAlegeriDatePresidentialeBySectieAndTur($this,$this->sectievot->sectie_id,2);
		$o2b.='<b>Rezultate Tur 1:</b>';
		$o2b.=Alegeri::getAlegeriDatePresidentialeBySectieAndTur($this,$this->sectievot->sectie_id,1);
		$o2f="Sursa: www.cec.md";
		if (empty($o2b)){
			return "";
		} else {
			return $this->getGroupBoxH3($o2s,$o2b,$o2f);
		}
	}	
	function getRightMenu(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewsectie&id=".$this->id."#1").'">Harta</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewsectie&id=".$this->id."#2").'">Adresa</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewsectie&id=".$this->id."#3").'">Hotar</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewsectie&id=".$this->id."#4").'">Contacte</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewsectie&id=".$this->id."#5").'">Rezultate</a></li>';
		$out.='</ul>';
		return $out;
	}
	
	function getLeftMenu(){
		$out='<ul>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'" >Toate sectiile</a></li>';
		$out.='</ul>';
		return $out;
	}
}
$n=new IndexPage();
?>
