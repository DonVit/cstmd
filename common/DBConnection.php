<?php
class DBConnection {
    
    private static $connection = null;
    
    public static function getConnection()
    {
		if (self::$connection === null) {
			self::$connection = mysqli_connect(
				Config::$mysql_host,
				Config::$mysql_user,
				Config::$mysql_password
			);
			
			if (!self::$connection) {
				die('Not connected : ' . mysqli_connect_error());
			}

			// Set the current db
			$db_selected = mysqli_select_db(self::$connection, Config::$mysql_db);
			if (!$db_selected) {
				die ('Can\'t use DataBase : ' . mysqli_error(self::$connection));
			}

			mysqli_set_charset(self::$connection, 'utf8');
		}
		return self::$connection;
	}
	
	public static function closeConnection()
	{
		if (self::$connection !== null) {
			mysqli_close(self::$connection);
			self::$connection = null;
		}
	}
}
?>
