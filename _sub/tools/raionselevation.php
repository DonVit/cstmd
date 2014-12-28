<?php
/*
 * Created on 25 Feb 2009
 *
 */
ini_set("memory_limit","200M"); 
set_time_limit(10720);
require_once('loader.php');

class RaionElevationWebPage extends MainWebPage {
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
		$out.=$this->getElevations();
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function getElevations(){
		$out="";

		$r=new Raion();
		$rs=$r->getAll('elevation=0');
		//$ls=$l->getAll();
		foreach($rs as $r){
			$h=$this->getElevation($r->lat,$r->lng);
			$out.=$h."<br>";
			$r->elevation=$h;
			$r->save();
			//sleep(1);
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
$n=new RaionElevationWebPage();
?>
