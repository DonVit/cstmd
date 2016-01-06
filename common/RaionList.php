<?php
class RaionList extends Object {
	private $raionid=0;
	private $locationid=0;
	private $raionlink;
	private $locationlink;
	
	
	public function __construct(){
	}
	public function setRaionId($raionid){
		$this->raionid=$raionid;
	}
	public function setLocationId($locationid){
		$this->locationid=$locationid;
		$l=new Location();
		$l->loadById($locationid);
		$this->raionid=$l->raion_id;
	}
	public function setRaionLink($raionlink){
		$this->raionlink=$raionlink;
	}	
	public function setLocationLink($locationlink){
		$this->locationlink=$locationlink;
	}	
	public function show(){
		$out='';
		$out.=$this->getRaions();		
		return $out;	
	}
	function getRaions(){		
		$r=new Raion();
		$rs=$r->getAll("","municipiu desc");
		$out="<ul>";
		$rl=$this->raionlink;
		foreach($rs as $r){	
			if ($r->id==$this->raionid){
				$out.='<li style="font-weight:bold;">'.$rl($r).'</li>';			
				$out.=$this->getLocationsByRaion();
			} else {
				$out.='<li>'.$rl($r).'</li>';
			}
		}
		$out.="</ul>";
		return $out;
	}
	function getLocationsByRaion(){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$this->raionid,"oras desc, `order`, name");
		$out="<ul style=\"margin:10px;\">";
		$ll=$this->locationlink;
		foreach($ls as $l){	
			if ($this->locationid==$l->id){
				$out.='<li style="list-style-type: circle;font-weight:bold;">'.$ll($l).'</a></li>';
			} else {
				$out.='<li style="list-style-type: circle;">'.$ll($l).'</li>';
			}
						
		}
		$out.="</ul>";
		return $out;
	}
}
?>
