<?php
/*
 * Created on 25 Feb 2009
 *
 */
ini_set("memory_limit","200M"); 
set_time_limit(10720);
require_once(__DIR__ . '/../main/loader.php');

class LocationElevationWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Elevation");
		$this->setLogoTitle("Elevation");
		$this->show();		
	}
	function show($html="LocationsWebPageHTML"){
		$out='<div id="container">';
		$out.='<div id="left">my left';
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$this->getGeocodes();
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function getGeocodeByAddress($map_address){
			// address to map
			// $map_address = "str.Chişinăului nr.26, Anenii Noi, Moldova";
			$url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode($map_address);
			$lat_long = get_object_vars(json_decode(file_get_contents($url)));
			// pick out what we need (lat,lng)
			//$lat_long = $lat_long['results'][0]->geometry->location->lat . "," . $lat_long['results'][0]->geometry->location->lng;
			//$out.=$lat_long;
			return $lat_long;
	}
	
	function getGeocodes(){
		$out="";
		// address to map
// 		$map_address = "str.Chişinăului nr.26, Anenii Noi, Moldova";
// 		$url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode($map_address);
// 		$lat_long = get_object_vars(json_decode(file_get_contents($url)));
// 		// pick out what we need (lat,lng)
// 		$lat_long = $lat_long['results'][0]->geometry->location->lat . "," . $lat_long['results'][0]->geometry->location->lng;
// 		$out.=$lat_long;

		$l=new Sectiidevot();
		//$ls=$l->getAll("lat=0 and circumscriptie='Chisinau' and tara='Moldova' and localitate in ('CENTRU','Buiucani')");
		$ls=$l->getAll("lat=0 and tara!='Moldova'");
		//$ls=$l->getAll();
		foreach($ls as $l){
			//$h=$this->getElevation($l->lat,$l->lng);
			$strada='';
			if (!empty($l->strada)){
				$strada=$l->strada.',';
			}
			$address=$l->localitate.','.$l->tara;
			//$address=$l->localitate.','.$l->circumscriptie.','.$l->tara;
			//$address=$strada.$l->localitate.','.$l->circumscriptie.','.$l->tara;
			//$address=$strada.$l->circumscriptie.','.$l->tara;
			$lat_long=$this->getGeocodeByAddress($address);
			$lat=$lat_long['results'][0]->geometry->location->lat;
			$lng=$lat_long['results'][0]->geometry->location->lng;
			$out.=$address.','.$lat . ',' . $lng.'<br>';
			$l->lat=$lat;
			$l->lng=$lng;
			$l->adresalunga=$address;
			$l->save();
			sleep(2);
		}
		 	
		$out.="done!";	
			
		return $out;
	}
	function getElevation($lat=0,$lng=0){
		$elevation=0;
		$lines = file('http://www.earthtools.org/height/'.$lat.'/'.$lng);		
		$elevation=trim(str_replace("</meters>","",str_replace("<meters>","",$lines[7])));
		return $elevation;
	}		
}
$n=new LocationElevationWebPage();
?>
