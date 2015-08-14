<?php
class Fm extends DBManager {
	public $id;
	public $name;
	public $url;	
	public $stream_url;	
	function getTableName(){
		return "fm";
	}	
}

?>
