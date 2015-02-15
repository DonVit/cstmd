<?php
class Location extends DBManager {
	public $id;
	public $parent_id;
	public $raion_id;
	public $raion_center;	
	public $statut;
	public $tip;
	public $oras;
	public $name;
	public $name_ro;
	public $lat;
	public $lng;
	public $elevation;
	public $p;
	public $m;
	public $f;
	public $note;
	public $data;
	public $contor;
	public $order;
// 	public function __construct() {
// 		parent::__construct();
// 		Logger::setLogs("xyzparent_id".$this->parent_id);
// 	}
	function getTableName(){
		return "localitate";
	}
	function getLatShort(){
		return substr($this->lat,0,7);
	}
	function getLngShort(){
		return substr($this->lng,0,7);
	}
	public function getFullName(){
		$fn="";
		if (isset($this->id)){
			if ($this->oras==1){
				$fn="or. ".$this->getName();
			}else{
				$fn="s. ".$this->getName();
			}
		}
		return $fn;
	}
	public function getFullNameDescription(){
		$fn="";
		if (isset($this->id)){
			if ($this->oras==1){
				$fn="Orasul ".$this->getName();
			}else{
				$fn="Satul ".$this->getName();
			}
		}
		return $fn;
	}
	public function getPrimariaName(){
		$fn="";
		if (isset($this->id)){
			if ($this->oras==1){
				$fn="Primaria Orasului ".$this->getName();
			}elseif ($this->isComuna()){
				$fn="Primaria Comunei ".$this->getName();
				} else {
				$fn="Primaria Satului ".$this->getName();
			}	
			
		}
		return $fn;
	}
	public function isComuna(){
		$r=false;
		if ($this->oras!=1){
			$o=DBManager::doSql("select * from localitate where parent_id=".$this->id);
			if (!is_null($o)){
				$r=true;
			}		
		}
		return $r;
	}
	function getPrimarieLocation(){
		$rt=$this;
		Logger::setLogs("xyz_id".$this->id);
		Logger::setLogs("xyzparent_id".$this->parent_id);
		Logger::setLogs("xyzraion_id".$this->raion_id);
		if (($this->statut==6)||($this->statut==9)){
			Logger::setLogs("xyzparent_id".$this->parent_id);
			$l=new Location();
			$l->loadById($this->parent_id);
			$rt=$l;
		}
		return $rt;
	}		
	public function getRaion(){
		$r=new Raion();
		$r->loadById($this->raion_id);
		return $r;
	}
	public function getLocations(){
		$l=new Location();
		$ls=$l->getAll("parent_id=".$this->id,"oras desc, name");
		return $ls;
	}
	public function getParentLocation(){
		$l=new Location();
		$l->loadById($this->parent_id);
		return $l;
	}			
	public static function getTopFirstLocationByRaionId($raionid){
		$o=DBManager::doSql("select * from localitate where raion_id=".$raionid." order by oras desc, `order` limit 0,1");
		if (!is_null($o)){
			return $o[0];
		} else {	
			return null;
		}		
	}
	public static function getLocationDropDown($raionid,$localitateid,$style=''){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$raionid,"`order`,`oras` desc,`name`");
		$out='<select id="localitate_id" name="localitate_id" style="'.$style.'" size="1" onchange="javascript:WizardOnDropDownChange()">';
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
	function getLocationsInRadius(){	
		$sql="SELECT localitate.*, (6371*acos(cos(radians($this->lat))*cos(radians(lat))*cos(radians(lng)-radians($this->lng))+sin(radians($this->lat))*sin(radians(lat)))) AS distance FROM localitate where id!=$this->id and (6371*acos(cos(radians($this->lat))*cos(radians(lat))*cos(radians(lng)-radians($this->lng))+sin(radians($this->lat))*sin(radians(lat))))<10 ORDER BY distance ";
		$ls=$this->doSql($sql);
		return $ls;
	}
	function getPrimariiInRadius(){
		$sql="SELECT localitate.*, (6371*acos(cos(radians($this->lat))*cos(radians(lat))*cos(radians(lng)-radians($this->lng))+sin(radians($this->lat))*sin(radians(lat)))) AS distance FROM localitate inner join primari2011 on localitate.id=primari2011.localitate_id where id!=$this->id and (6371*acos(cos(radians($this->lat))*cos(radians(lat))*cos(radians(lng)-radians($this->lng))+sin(radians($this->lat))*sin(radians(lat))))<10 ORDER BY distance ";
		$ls=$this->doSql($sql);
		return $ls;
	}	
	function getLocationsWithSameName(){	
		$sql="SELECT localitate.* FROM localitate where id!=$this->id and name='$this->name'";
		$ls=$this->doSql($sql);
		return $ls;
	}
	function getChildLocations(){
		
		$l=new Location();
		$ls=$l->getAll("id=$this->id or parent_id=".$this->id,"oras desc, p desc, name");
		return $ls;		
	}
	function getPrimariePopulation(){
		$sql="SELECT sum(p) as p FROM localitate where id=$this->id or parent_id=".$this->id;
		$ls=$this->doSql($sql);
		return $ls[0]->p;
	}
	function getPrimarName(){
		$sql="SELECT * FROM primari2011 where localitate_id=".$this->id;
		$ls=$this->doSql($sql);
		return $ls[0];
	}
	function getPrimarieConsilieri(){
		$sql="SELECT * FROM consilierilocali2011 where localitate_id=".$this->id;
		$ls=$this->doSql($sql);
		return $ls;
	}
	function getPrimarieConsilieriTotal(){
		$sql="SELECT count(*) as c FROM consilierilocali2011 where localitate_id=".$this->id;
		$ls=$this->doSql($sql);
		return $ls[0]->c;
	}
	function getPrimarieConsilieriPerPartid(){
		$sql="SELECT partid,count(*) as c FROM consilierilocali2011 where localitate_id=".$this->id." group by partid order by 2 desc";
		$ls=$this->doSql($sql);
		return $ls;
	}						
	function getDistanceToLocation($locationid){	
		$sql="SELECT * FROM localitate_distance where `from`=$this->id and `to`=$locationid";
		$ls=$this->doSql($sql);
		return $ls[0]->distance;
	}
	function getContacts(){
		$sql="SELECT xcompany.type,xsubdivizion.new_name as SubdivizionName, xsubsector.new_name as SectorName,phoneprefix,phonenumber FROM xsubcompany inner join xcompany on xsubcompany.companyid=xcompany.id left join xsubdivizion on xsubcompany.subdivizionid=xsubdivizion.id left join xsubsector on xsubcompany.subsectorid=xsubsector.id where localitateid=".$this->id." and xcompany.type in ('Primaria','Scoala','Gradinita','Medic','Politia','Casa De Cultura','Posta','Biblioteca','Banca','Biserica','Gimnaziu') order by xcompany.type LIMIT 0,100";
		$ls=$this->doSql($sql);
		return $ls;
		
	}	
	function getName(){						
		
		$fn="name_".$this->getLang()->name;
		if ($this->isMember($fn)){
			return $this->$fn;
		} else {
			return $this->name;
		}		
	}
	function getData(){
		//return System::getDate(date_parse($this->data));
		return substr($this->data,0,10);
	}
	function getPrefixes(){
		$o=DBManager::doSql("SELECT substring(prefix,1,3) as prefix FROM localitate l INNER JOIN prefix p ON l.id = p.localitate_id where l.id=".$this->id." group by substring(prefix,1,3) order by prefix");
		if (!is_null($o)){
			return $o;
		} else {	
			return null;
		}
	}
	function finLocationsInText($text){
		
	}		
}
?>
