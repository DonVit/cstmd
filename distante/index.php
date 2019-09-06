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
		$gt="Distanța directă și distanța drumului din ".$this->locationfrom->getFullNameDescription()." pînă în ".$this->locationto->getFullNameDescription()." sunt de: <span id=\"directdistance\"></span> și <span id=\"roaddistance\"></span>";
		$this->setTitle($t);
		$this->setCenterContainer($this->getGroupBoxH3("Specifică localitatea de început și sfîrțit pentru a vedea distanța ți drumul:",$this->getFilter()));
		$this->setCenterContainer($this->getGroupBoxH3($gt,$this->getMap()));
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
		$out.='<div id="center" class="container center" style="width:1000px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
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
		$out.='<form id="frmWizard" name="frmWizard" method="post">';
		$out.='<strong>De la </strong> ';
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
		$out.='<strong> pina la </strong>';
		$out.=Raion::getRaionLocalitateDropDownAsync("raionend","localitateend",$this->locationto->id);
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
		$out.='<input name="show" type="button" class="button" style="width:60px;" value="Arata" onclick="javascript:ShowDirections(\'localitatestart\',\'localitateend\')">';
		$out.='</form>';
		return $out;
	}
	function getMap($out=''){

		$this->setBodyTag('<body onload="DirectionsMapViewOnMapLoad()">');
		$this->setJavascript("https://maps.googleapis.com/maps/api/js?key=".Config::$gmapskey."&libraries=geometry&sensor=false");
		$this->setJavascript("https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js");		
		$this->setJavascript(Config::$commonsite."/js/directions.js");		

		$out='<div id="map" style="width: 100%;height: 400px;border:1px solid #777777;margin-top: 2px;"></div>';
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
