<?php
/*
 * Created on 27 Feb 2009
 *
 */
class DictionarTip extends DBManager {
	public $id;
	public $nume;
	function getTableName(){
		return "dictionar_tip";
	}	
}

?>
