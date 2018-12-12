<?php
class AddUser extends DBManager {
	public $id;
	public $email;
	function getTableName(){
		return "ads_user";
	}	
}

?>
