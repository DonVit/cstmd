<?php
class Country extends DBManager {
	public $id;
	public $ISO;
	public $Country;		

	function getTableName(){
		return "country";
	}	
}

?>
