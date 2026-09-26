<?php
class DBConnection {
	private static $DBConnection = null;
    
    public static function getConnection()
    {
		//global $mysql_host, $mysql_user, $mysql_password, $mysql_db;

		//get connection
		// $DBConnection = mysql_connect(Config::$mysql_host, Config::$mysql_user, Config::$mysql_password);

		if (self::$DBConnection == null) {
            self::$DBConnection = new mysqli(Config::$mysql_host, Config::$mysql_user, Config::$mysql_password, Config::$mysql_db);
			mysqli_query(self::$DBConnection, "SET CHARACTER SET utf8");
			mysqli_query(self::$DBConnection, "SET NAMES 'utf8'");
        }
		// $DBConnection = new mysqli(Config::$mysql_host, Config::$mysql_user, Config::$mysql_password, Config::$mysql_db);
		if (!self::$DBConnection) {
		    die('Not connected : ' . mysqli_error());
		}

		// Set the current db
		// $db_selected = mysql_select_db(Config::$mysql_db, $DBConnection);
		// if (!$db_selected) {
		//     die ('Can\'t use DataBase : ' . mysql_error());
		// }

		return self::$DBConnection;
	}
}
?>
