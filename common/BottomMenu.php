<?php
/*
 * Created on 26 Feb 2009
 *
 */
class BottomMenu {
	//public static $Items=array("about.php"=>"Despre Proiect","contacts.php"=>"Contacte","feedback.php"=>"Feedback");
	public static $Items=array("feedback.php"=>"Feedback");
	public static function getSelectedItem(){
		return str_replace("/","",pathinfo($_SERVER["SCRIPT_NAME"],PATHINFO_DIRNAME));;
	}
}
?>
