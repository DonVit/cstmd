<?php
class DBConnection {
    
    public static function getConnection()
    {
		//global $mysql_host, $mysql_user, $mysql_password, $mysql_db;

		//get connection
		$DBConnection = mysql_connect(Config::$mysql_host, Config::$mysql_user, Config::$mysql_password);
		if (!$DBConnection) {
		    die('Not connected : ' . mysql_error());
		}

		// Set the current db
		$db_selected = mysql_select_db(Config::$mysql_db, $DBConnection);
		if (!$db_selected) {
		    die ('Can\'t use DataBase : ' . mysql_error());
		}

		mysql_query("SET CHARACTER SET utf8", $DBConnection);
		mysql_query("SET NAMES 'utf8'", $DBConnection);
		return $DBConnection;
	}
}
?>
