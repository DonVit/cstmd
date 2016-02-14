<?php
class Location extends DBManager {
	public $id;
	public $alegeri_id;
	public $partidcod;
	public $pardidname;	
	public $partidcolor;

	function getTableName(){
		return "al_partid";
	}
			
	public static function getPartidByAlegeriAndParidCod($alegeriid,$partidcod){
		$p=new Partid();
		$ps=$p->getAll("alegeri_id=".$alegeriid." and partidcod=".$partidcod);
		
		if (!is_null($ps)){
			return $ps[0];
		} else {	
			return null;
		}		
	}

}
?>
