<?php
require_once(__DIR__ . '/../main/loader.php');
 
class DistancesWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		
		//if ((isset($this->lat))&&(isset($this->lng))&&(isset($this->title))){
		/*	
		if (isset($this->lat)){
			$m=new Map();
			$m->centerlat=$this->centerlat;
			$m->centerlng=$this->centerlng;
			$m->zoom=$this->zoom;
			$m->maptype=$this->maptype;
			$m->lat=$this->lat;
			$m->lng=$this->lng;
			$m->title=$this->ptitle;
			$m->description=$this->pdescription;
			$m->save();
			if (isset($m->id)){
				$this->redirect(Config::$mapssite.'/index.php?id='.$m->id);
			}
		}
		*/
		
		if (!SessionManager::isObject('fromraion')) {
			SessionManager::setObject('fromraion',Raion::getTopFirstRaion());
		}
		
		$t="HARTI DIN REPUBLICA MOLDOVA";		
		$this->setTitle($t);
		$this->setLogoTitle($t);


		$this->create();		
	}
	function actionDefault(){
	
		$this->setCSS("style/distante.css");
		$this->map=User::getCurrentMap();

		$this->locationfrom=new Location();
		$this->locationto=new Location();
		if (isset($this->from)){
			$this->locationfrom->loadById($this->from);
		} else {
			$this->locationfrom->loadById(101);		
		}
		if (isset($this->to)){
			$this->locationto->loadById($this->to);
		} else {
			$this->locationto->loadById(301);		
		}		
		$t="Distanța din ".$this->locationfrom->getFullNameDescription()." pînă în ".$this->locationto->getFullNameDescription();
		$gt='<div class="distance-metrics">';
		$gt.='<div class="distance-metric"><span>Distanța în linie dreaptă</span><strong id="directdistance"></strong></div>';
		$gt.='<div class="distance-metric distance-metric-road"><span>Distanța pe drum</span><strong id="roaddistance"></strong></div>';
		$gt.='</div>';
		$this->setTitle($t);
		$this->setCenterContainer('<section class="distance-route-panel">'.$this->getGroupBoxH3("Alege punctele traseului",$this->getFilter()).'</section>');
		$this->setCenterContainer('<section class="distance-results-panel">'.$this->getGroupBoxH3("Distanța dintre localități",$gt.$this->getMap()).'</section>');
		$this->show();
	}
	function actionViewMap1(){
		
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
		$this->setTitle("Harti Moldova: ".$this->map->title);
		
		//$this->setBodyTag('<body onload="OnViewMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;hl=ro&amp;sensor=false&amp;key=".Config::getMapKey($this->getServerName()));
		//$this->setCSS("style/maps.css");
		//$this->setJavascript("js/scripts.js");

		//$this->setLogoTitle("Localitati din Republica Moldova");
		//$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getLeftMenu()));	
		$this->setCenterContainer($this->getGroupBoxH1($this->map->title,$this->getViewMap()));
		$this->setCenterContainer($this->getGroupBoxH3("Descriere",$this->getViewMapDescription()));
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii:",Comment::getComments($this,'m',$m->id)));
		$this->showmap();
	}		
	function show($html=""){
		$out="";
		$out.='<div id="container">';
		//$out.='<div id="left" class="container left" style="width:198px;">';
		//$out.=$this->getLeftContainer();
		//$out.='</div>';		
		$out.='<main id="center" class="container center distance-content">';
		$out.=$this->getCenterContainer();
		$out.='</main>';
		//$out.='<div id="right" class="container right" style="width:198px;">';
		//$out.=$this->getRightContainer();
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
		
	}
	function showmap1(){
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
	function getFilter(){
		
		$out='';
		//$out.='<div id="filter" style="width: 998px;height: 40px;border:1px solid #777777;margin-top: 2px;">';
		$out.='<form id="frmWizard" class="distance-route-form" name="frmWizard" method="post">';
		$out.='<label class="distance-endpoint"><span>Punct de plecare</span>';
		/*
		$locationfrom=new Location();
		$locationto=new Location();
		if (isset($this->from)){
			$out.=Raion::getRaionLocalitateDropDownAsync($this->from);
			$locationfrom->loadById($this->from);
		} else {
			$out.=Raion::getRaionLocalitateDropDownAsync(101);
			$locationfrom->loadById(101);		
		}
		$out.='<strong> pina la </strong>';
		if (isset($this->to)){
			$out.=Raion::getRaionLocalitateDropDownAsync($this->to);
			$locationto->loadById($this->to);
		} else {
			$out.=Raion::getRaionLocalitateDropDownAsync(301);
			$locationto->loadById(301);		
		}		
		*/
		$out.=Raion::getRaionLocalitateDropDownAsync("raionstart","localitatestart",$this->locationfrom->id);
		$out.='</label>';
		$out.='<label class="distance-endpoint"><span>Punct de sosire</span>';
		$out.=Raion::getRaionLocalitateDropDownAsync("raionend","localitateend",$this->locationto->id);
		$out.='</label>';
		//$out.=Location::getLocationDropDown(Raion::getTopFirstRaion()->id,Location::getTopFirstLocationByRaionId(Raion::getTopFirstRaion()->id)->id);
		
		$out.='<input id="fromlat" name="fromlat" type="hidden" value="'.$this->locationfrom->lat.'"/>';
		$out.='<input id="fromlng" name="fromlng" type="hidden" value="'.$this->locationfrom->lng.'"/>';
		$out.='<input id="tolat" name="tolat" type="hidden" value="'.$this->locationto->lat.'"/>';
		$out.='<input id="tolng" name="tolng" type="hidden" value="'.$this->locationto->lng.'"/>';

		//$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->map->maptype.'"/>';
		//$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->map->zoom.'"/>';
		//$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->map->lat.'"/>';
		//$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->map->lng.'"/>';
		//$out.='<input name="title" type="hidden" id="title"  readonly="true" class="inptdisabled" value="'.$this->map->title.'"/>';		
		//$out.='<input name="description" type="hidden" id="description"  readonly="true" class="inptdisabled" value="'.$this->map->description.'"/>';		
		$out.='<button name="show" type="button" class="distance-submit" onclick="javascript:ShowDirections(\'localitatestart\',\'localitateend\')">Arată ruta</button>';
		$out.='</form>';
		return $out;
	}
	function getMap($out=''){

		$this->setBodyTag('<body onload="DirectionsMapViewOnMapLoad()">');
		$this->setCSS("https://unpkg.com/leaflet@1.9.4/dist/leaflet.css");
		$this->setCSS("https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css");
		$this->setJavascript("https://unpkg.com/leaflet@1.9.4/dist/leaflet.js");
		$this->setJavascript("https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js");
		$this->setJavascript("https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js");		
		$this->setJavascript("directions.js");

		$out='<div id="map" class="distance-map" style="height:min(72vh,720px);min-height:500px;width:min(100%,calc(100vw - 32px));max-width:1000px;box-sizing:border-box;"></div>';
		//$out.='<div id="dir" style="width: 100%;border:1px solid #777777;margin-top: 2px;"></div>';

		return $out;
	}	
	function getViewMap1(){

		// $this->setBodyTag('<body onload="MapViewOnMapLoad()">');
		// $this->setJavascript("https://maps.google.com/maps/api/js?sensor=false");
		// $this->setJavascript(Config::$commonsite."/js/maps.js");
		
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
		$out.='<div id="map" style="height: 400px;border:1px solid #777777;	margin-top: 2px;"></div>';
		$out.='</form>';
		return $out;
	}
	function getViewMapDescription(){
		$out='<div><div class="newscomment_body">'.$this->map->description.'</div></div>';
		if ($this->map->description==""){
			$out='<div><div class="newscomment_body">Nu exista</div></div>';
		}
		return $out;
	}						
}
$d=new DistancesWebPage();
?>
