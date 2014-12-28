<?php
/*
 * Created on 2 Mar 2009
 *
 */
class Logger {
	private static $logs="";
	public static function setLogs($logs){
		if (is_array($logs)){
			Logger::$logs.="LOG:".var_export($logs,true)."<br>";
		}else {
			Logger::$logs.="LOG:".$logs."<br>";
		}
	}
	public static function getLogs(){
		$tmp="POST fields:<br>";
		foreach ($_POST as $p=>$v){
			$tmp.=$p."=".$v."<br>";
		}
		$tmp.="GET fields:<br>";
		foreach ($_GET as $p=>$v){
			$tmp.=$p."=".$v."<br>";
		}
		//$tmp.=phpinfo();
		return $tmp.Logger::$logs;
	}
}
?>
