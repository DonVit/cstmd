<?php
/*
 * Created on 27 Feb 2009
 *
 */
class AddUser extends DBManager {
	public $id;
	public $email;
	function getTableName(){
		return "ads_user";
	}	
}

?>
