<?php
class TipImobil extends DBManager {
	public $id;
	public $scop_id;
	public $name;
	public $menu_name;
	public $note;
	public $order;	
	function getImobilCount(){
		$sql="select count(*) as cnt from imobil where imobil.status = 0 and tipimobil_id=$this->id";
		$rs=DBManager::doSql($sql);
		$cnt=0;
		foreach($rs as $r){
			$cnt=$r->cnt;
		}
		return $cnt;
	}
}

?>
