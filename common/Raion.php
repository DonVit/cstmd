<?php
/*
 * Created on 27 Feb 2009
 *
 */
class Raion extends DBManager {
	public $id;
	public $municipiu;
	public $name;
	public $name_ro;
	//public $name_en;
	public $lat;
	public $lng;
	public $elevation;
	public $zoom;
	public $note;
	public $contor;
	public $order;	

	public function getName(){
		$fn="name_".$this->getLang()->name;
		Logger::setLogs($fn);
		if ($this->isMember($fn)){
			return $this->$fn;
		} else {
			return $this->name;
		}
	}
	public function getFullName(){
		$fn="";
		if (isset($this->id)){
			if ($this->municipiu==1){
				$fn="m. ".$this->getName();
			}else{
				$fn="r. ".$this->getName();
			}
		}
		return $fn;
	}	
	public function getFullNameDescription(){
		$fn="";
		if (isset($this->id)){
			if ($this->municipiu==1){
				$fn=$this->getConstants("RaionNameMunicipiu")." ".$this->getName();
			}else{
				$fn=$this->getConstants("RaionNameRaion")." ".$this->getName();
			}
		}
		return $fn;
	}
	public function getLocations(){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$this->id,"oras desc, name");
		return $ls;
	}
	public function getCentrulRaional(){
		$l=new Location();
		$ls=$l->getAll("raion_center=1 and raion_id=".$this->id,"oras desc, name");
		return $ls[0];
	}
	public function getPrimarii(){
		$l=new Location();
		$ls=$l->getAll("statut in (3,8) and raion_id=".$this->id,"oras desc, name");
		return $ls;
	}		
	public static function getTopFirstRaion(){
		$o=DBManager::doSql("select * from raion order by municipiu desc, `order` limit 0,1");
		if (!is_null($o)){
			return $o[0];
		} else {	
			return null;
		}		
	}
	public static function getRaionDropDown($raionid,$style=''){
		$r=new Raion();
		$rs=$r->getAll("","`municipiu` desc,`order`,`name`");
		$out='<select id="raion_id" name="raion_id" style='.$style.' size="1" onchange="javascript:WizardOnDropDownChange()">';
		if (!is_null($rs)){
			foreach($rs as $rr){
				$name=($rr->municipiu==1)?"m. ".$rr->name:"r. ".$rr->name;
				if ($rr->id==$raionid){
					$out.= "<option value=".$rr->id." selected>".$name."</option>";
				} else {
					$out.= "<option value=".$rr->id.">".$name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}
	public static function getRaionLocalitateDropDownAsync($raion,$localitate,$localitateid,$style=''){
		$l=new Location();
		$l->loadById($localitateid);
		$raionid=$l->raion_id;
		
		$r=new Raion();
		$rs=$r->getAll("","`municipiu` desc,`order`,`name`");
		$out='<select id="'.$raion.'" name="'.$raion.'" style="'.$style.'" size="1" onchange="javascript:FillLocationsDropDown(this,\''.$localitate.'\')">';
		if(!is_null($rs)){
			foreach($rs as $rr){
				$name=($rr->municipiu==1)?"m. ".$rr->name:"r. ".$rr->name;
				if ($rr->id==$raionid){
					$out.= "<option value=".$rr->id." selected>".$name."</option>";
				} else {
					$out.= "<option value=".$rr->id.">".$name."</option>";
				}
			}
		}
		$out.="</select>";
		
		$ls=$l->getAll("raion_id=".$raionid,"`order`,`oras` desc,`name`");
		$out.='<select id="'.$localitate.'" name="'.$localitate.'" style="'.$style.'" size="1">';
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->id==$localitateid){
					$out.= "<option value=".$ll->id." selected>".$ll->tip." ".$ll->name_ro."</option>";
				} else {
					$out.= "<option value=".$ll->id.">".$ll->tip." ".$ll->name_ro."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;	
		
	}					
	function getLatShort(){
		return substr($this->lat,0,7);
	}
	function getLngShort(){
		return substr($this->lng,0,7);
	}
	function getPopulation(){
		$o=DBManager::doSql("select sum(p) as p from localitate where raion_id=".$this->id);
		if (!is_null($o)){
			return $o[0]->p;
		} else {	
			return null;
		}
	}	
}
?>
