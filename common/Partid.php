<?php
class Partid extends DBManager {
	public $id;
	public $alegeri_id;
	public $partidcod;
	public $pardidname;	
	public $partidcolor;

	function getTableName(){
		return "al_partid";
	}
			
	public static function getPartidByParidCod($partidcod){
		$p=new Partid();
		$ps=$p->getAll("alegeri_id=".Primar::getLastAlegeriYear()->alegeri_id." and partidcod=".$partidcod);
		
		if (!is_null($ps)){
			return $ps[0];
		} else {	
			return null;
		}		
	}
	function getPrimarPartidByLocationId($localitate_id){
		$sql="SELECT a2.* FROM al_primari as a1 inner join al_partid as a2 on a1.alegeri_id=a2.alegeri_id and a1.partid=a2.partidcod where a1.alegeri_id=".Primar::getLastAlegeriYear()->alegeri_id." and a1.localitate_id=".$localitate_id;
		$ls=$this->doSql($sql);
		return $ls[0];
	}	
}
?>
