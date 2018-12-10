<?php
ini_set("memory_limit","200M"); 
set_time_limit(10720);
require_once(__DIR__ . '/../main/loader.php');

class LocationElevationWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Pie");
		$this->setLogoTitle("Pie");
		$this->show();		
	}
	function show($html="LocationsWebPageHTML"){
		$out='<div id="container">';
		$out.='<div id="left">my left';
		$out.='</div>';
		$out.='<div id="center">';
		//$out.=$this->getElevations();
		$in='http://chart.apis.google.com/chart?cht=p&chd=t:40,30,20,10&chs=400x200&chl=PCRM%2033%|PLDM%2020%|PL|AMN&chco=FF0000|00FF00|0000FF|FFFF00';
		$out='image1.jpg';
		$this->save_image($in,$out);
		$out.='<img src="image1.jpg"/>';
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function getElevations(){
		$out="";

		$l=new Location();
		$ls=$l->getAll('elevation=0');
		//$ls=$l->getAll();
		foreach($ls as $l){
			$h=$this->getElevation($l->lat,$l->lng);
			$out.=$h;
			$l->elevation=$h;
			$l->save();
			//sleep(1);
		}
		 	
		$out.="done!";	
			
		return $out;
	}
	function getElevation($lat=0,$lng=0){
		$elevation=0;
		$lines = file('http://chart.apis.google.com/chart?cht=p&chd=t:40,30,20,10&chs=400x200&chl=PCRM%2033%|PLDM%2020%|PL|AMN&chco=FF0000|00FF00|0000FF|FFFF00');		
		$elevation=trim(str_replace("</meters>","",str_replace("<meters>","",$lines[7])));
		return $elevation;
	}
	function save_image($inPath,$outPath)
	{ //Download images from remote server
	    $in=    fopen($inPath, "rb");
	    $out=   fopen($outPath, "wb");
	    while ($chunk = fread($in,8192))
	    {
	        fwrite($out, $chunk, 8192);
	    }
	    fclose($in);
	    fclose($out);
	}		
}
$n=new LocationElevationWebPage();
?>
