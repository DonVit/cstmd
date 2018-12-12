<?php
require_once(__DIR__ . '/../main/loader.php');

class IndexLocationsWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		
		$this->map=User::getCurrentMap();

		if ((isset($this->lid))&&(isset($this->nid))){
			$l=new Location();
			$l->loadById($this->lid);
			$n=new Location();
			$ns=$n->doSql("SELECT * FROM nodes where id=".$this->nid);
			if (!is_null($ns)){
				foreach($ns as $n){
					$l->lat=$n->latitude;
					$l->lng=$n->longitude;
				}
				$l->save();
			}			
		}
		if ((isset($this->lat))&&(isset($this->lng))&&(isset($this->locationid))){
			$l=new Location();
			$l->loadById($this->locationid);
			if(($l->lat==0)&&($l->lng==0)){
				$l->lat=$this->lat;
				$l->lng=$this->lng;
				$l->save();
			}	
		}
		
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
		//$out='<form >';
		$out='<form id="poiform" name="poiform" method="post">';
		$out.='<div id="container">';
		$out.='<div id="left1">';
		$out.=$this->getLocationDropDown($this->localitate_id);		
		$out.='<input type="submit" value="Alege"><br>';
		$out.=$this->getNodeDropDown($this->localitate_id);			
		$out.='</div>';		
		$out.='<div id="center">';
		$out.=$this->getMap();
		$out.='</div>';
		//$out.='<div id="right"> my right';
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		$out.='</form>';
		MainWebPage::show($out);
	}
	function getLocationDropDown($localitateid=0){
		$r=new Raion();
		$rs=$r->doSql("select r.* from raion as r inner join localitate as l on r.id=l.raion_id where l.id=$localitateid");
		foreach($rs as $r){				
			$this->raion=$r;
		}		
		$l=new Location();
		$ls=$l->doSql("SELECT l.id as lid, oras, l.name as lname, l.name_ro as lnamero, r.id as rid, r.name as rname, (SELECT name FROM `localitate` where id=l.parent_id ) as parentname FROM `localitate` as l inner join raion as r on l.raion_id=r.id where l.lat=0 order by r.id");
		$out="<select id=\"localitate_id\" name=\"localitate_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnDropDownChange()\">";
		//if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
		//	$out.= "<option value=\"0\">Nu Conteaza</option>";
		//}
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->lid==$localitateid){
					$out.= "<option value=".$ll->lid." selected>".$ll->rname."->".$ll->oras."-".$ll->lname."-".$ll->lnamero."(".$ll->parentname.")</option>";
				} else {
					$out.= "<option value=".$ll->lid.">".$ll->rname."->".$ll->oras."-".$ll->lname."-".$ll->lnamero."(".$ll->parentname.")</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}
	function getNodeDropDown($localitateid=0){
		$l=new Location();
		$ls=$l->doSql("select n.id, nt.v, n.latitude, n.longitude from `node_tags` as nt inner join nodes as n on nt.id=n.id inner join localitate as l on l.id=$localitateid and nt.v=l.name WHERE nt.k='name'");
		$out="<select id=\"node_id\" name=\"node_id\" class=\"select\" size=\"5\">";
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
	function getMap(){
		$out='';
		//$out.='<form id="poiform" name="poiform" method="post">';
		$out.='<input id="locationid" name="locationid" type="hidden" value="'.$this->localitate_id.'"/>';		
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->raion->lat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->raion->lng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="3"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->raion->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value=""/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value=""/>';
		$out.='<input name="title" type="hidden" id="title"  readonly="true" class="inptdisabled" value=""/>';		
		$out.='<input name="description" type="hidden" id="description"  readonly="true" class="inptdisabled" value=""/>';		
		$out.='<div id="map"></div>';
		//$out.='</form>';
		return $out;
	}									
}
$n=new IndexLocationsWebPage();
?>
