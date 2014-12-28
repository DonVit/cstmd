<?php
/*
 * Created on 27 Feb 2009
 *
 */
class AdsMessage extends DBManager {
	public $id;
	public $title;
	public $text;
	public $datetime;	
	function getTableName(){
		return "ads_message";
	}	
}

?>
