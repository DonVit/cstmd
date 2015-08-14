<?php

require_once(__DIR__ . '/../main/loader.php');

class IndexLocationsWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		
		$this->map=User::getCurrentMap();
		
		if (!isset($this->localitate_id)){
			$this->localitate_id=0;
		}
		
		
		//$this->setBodyTag('<body onload="OnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;hl=ro&amp;sensor=false&amp;key=".Config::getMapKey($this->getServerName()));
		$this->setCSS("style/maps.css");
		$this->setJavascript("js/scripts.js");
				
		$this->setTitle("Localitati");
		$this->setLogoTitle("Localitati");
		$this->show();		
	}
	function show($html="LocationsWebPageHTML"){
		$out='<form >';
		$out.='<div id="container">';
		$out.='<div id="left1">';
		$out.=$this->URLopen("http://radio7.md:8000/128.m3u"); 		
		$out.='<input type="submit" value="Alege"><br>';
		//$out.=$this->getNodeDropDown($this->localitate_id);			
		$out.='</div>';		
		$out.='<div id="center">';
		//$out.=$this->getMap();
		$out.='</div>';
		//$out.='<div id="right"> my right';
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		$out.='</form>';
		MainWebPage::show($out);
	}
	function getLocationDropDown($localitateid=0){
		$l=new Location();
		$ls=$l->doSql("SELECT l.id as lid, l.name as lname, r.id as rid, r.name as rname FROM `localitate` as l inner join raion as r on l.raion_id=r.id where l.lat=0 order by r.id");
		$out="<select id=\"localitate_id\" name=\"localitate_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnDropDownChange()\">";
		//if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
		//	$out.= "<option value=\"0\">Nu Conteaza</option>";
		//}
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->lid==$localitateid){
					$out.= "<option value=".$ll->lid." selected>".$ll->rname."->".$ll->lname."</option>";
				} else {
					$out.= "<option value=".$ll->lid.">".$ll->rname."->".$ll->lname."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}
	function getNodeDropDown($localitateid=0){
		$l=new Location();
		$ls=$l->doSql("select n.id, n.v from `node_tags` as n inner join localitate as l on l.id=$localitateid and n.v=l.name WHERE n.k='name'");
		$out="<select id=\"node_id\" name=\"node_id\" class=\"select\" size=\"5\" onchange=\"javascript:WizardOnDropDownChange()\">";
		//if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
		//	$out.= "<option value=\"0\">Nu Conteaza</option>";
		//}
		if (!is_null($ls)){
			foreach($ls as $ll){				
				$out.= "<option value=".$ll->id.">".$ll->id."->".$ll->v."</option>";
			}
		}
		$out.="</select>";
		return $out;
	}
	function URLopen($url) 
	{ 
	        // Fake the browser type 
	        ini_set('user_agent','MSIE 4\.0b2;'); 
	
	        $dh = fopen("$url",'r'); 
	        $result = fread($dh,8192);                                                                                                                             
	        return $result; 
	} 
}
$n=new IndexLocationsWebPage();
?>
