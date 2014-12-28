<?php
/*
 * Created on 27 Feb 2009
 *
 */
class SessionManager extends Object {
	public static function getSessionId(){
		return session_id();
	}
	public static function setObject($objectname, $object){
		$_SESSION[$objectname]=serialize($object);
	}
	public static function getObject($objectname){
		return unserialize($_SESSION[$objectname]);	
	}
	public static function isObject($objectname){
		if (isset($_SESSION[$objectname])){
			return true;
		}else{
			return false;	
		}
	}				
}

?>
