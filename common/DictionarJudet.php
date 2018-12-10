<?php
class DictionarJudet extends DBManager {
	public $id;
	public $nume;
	function getTableName(){
		return "dictionar_judet";
	}	
}

?>
