<?php
/*
 * Created on 27 Feb 2009
 *
 */
class AdsMessageUser extends DBManager {
	public $id;
	public $ads_message_id;
	public $ads_user_id;
	public $key;
	public $sent;	
	public $senddate;
	public $visited;
	public $visitdate;
	function getTableName(){
		return "ads_messageuser";
	}	
}

?>
