<?php
class Primar extends DBManager {
	public $id;
	public $alegeri_id;
	public $raion_id;
	public $localitate_id;	
	public $nr;
	public $nume;
	public $prenume;
	public $partid;

	function getTableName(){
		return "al_primari";
	}
			
	public static function getCurrentPrimarByLocalitate($localitate_id){
		$p=new Primar();
		$ps=$p->getAll("localitate_id=".$localitate_id." and alegeri_id=".Primar::getLastAlegeriYear()->alegeri_id);
		if (!is_null($ps)){
			return $ps[0];
		} else {	
			return null;
		}		
	}
	public static function getLastAlegeriYear(){
		$o=DBManager::doSql("SELECT distinct alegeri_id FROM al_partid order by alegeri_id desc");
		return $o[0];
	}
}
?>
