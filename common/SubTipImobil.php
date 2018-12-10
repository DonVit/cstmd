<?php
class SubTipImobil extends DBManager {
	public $id;
	public $tipimobil_id;
	public $name;
	public $note;
	public $order;	
	function getImobilCount(){
		$sql="select count(*) as cnt from imobil where imobil.status = 0 and subtipimobil_id=$this->id";
		$rs=DBManager::doSql($sql);
		$cnt=0;
		foreach($rs as $r){
			$cnt=$r->cnt;
		}
		return $cnt;
	}
}

?>
