<?php
/*
 * Created on 25 Feb 2009
 *
 */
ini_set("memory_limit","200M"); 
set_time_limit(10720);
require_once('loader.php');

class LocationDistanceWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Distance");
		$this->setLogoTitle("Distance");
		$this->show();		
	}
	function show($html="LocationsWebPageHTML"){
		$out='<div id="container">';
		$out.='<div id="left">my left';
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$this->getDistnace();
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function getDistnace(){
		$out="";
		$l=new LocationDistance();
		$ls=$l->getAll('distance=0');
		//$ls=$l->getAll();
		foreach($ls as $l){
			$l1=new Location();
			$l1->loadById($l->from);
			$l2=new Location();
			$l2->loadById($l->to);
			$l->distance=Map::getDirectDistance($l1->lat,$l1->lng,$l2->lat,$l2->lng);;
			$l->save();
		}
		 	
		$out.="done!";	
			
		return $out;
	}
		
}
$n=new LocationDistanceWebPage();
?>
