<?php
/*
 * Created on 25 Feb 2009
 *
 */
ini_set("memory_limit","200M"); 
set_time_limit(10720);
require_once('loader.php');

class CompanyLatLngWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("CompanyLatLng");
		$this->setLogoTitle("CompanyLatLng");
		$this->show();		
	}
	function show($html="LocationsWebPageHTML"){
		$out='<div id="container">';
		$out.='<div id="left">my left';
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$this->getCompanyLatLng();
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function getCompanyLatLng(){
		$out="";
		$c=new Company();
		$cs=$c->getAll('id>=1499');
		//$ls=$l->getAll();
		foreach($cs as $c){
			$l=new Location();
			$l->loadById($c->localitate_id);
			
			//$c->name=$c->name.' din '.$l->getFullNameDescription();
			$c->centerlat=$l->lat;
			$c->centerlng=$l->lng;
			$c->save();
			//$out.=$c->name.' din '.$l->getFullNameDescription().'<br>';
		}
		 	
		$out.="done!";	
			
		return $out;
	}
		
}
$n=new CompanyLatLngWebPage();
?>
