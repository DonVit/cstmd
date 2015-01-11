<?php
/*
 * Created on 27 Feb 2009
 *
 */


class LocationDistance extends DBManager {
	public $id;
	public $from;
	public $to;
	public $distance;

	function getTableName(){
		return "localitate_distance";
	}
	function getFrom(){
		$l=new Location();
		$l->loadById($this->from);
		return $l;
	}
	function getTo(){
		$l=new Location();
		$l->loadById($this->to);
		return $l;
	}		
	public static function getDistancesByLocation($location){
		$o=DBManager::doSql("SELECT l.id, f.id as from_id, f.name as from_name, f.lat as from_lat, f.lng as from_lng, t.id as to_id, t.name as to_name, t.lat as to_lat, t.lng as to_lng, l.distance FROM localitate_distance as l inner join localitate as f on l.from=f.id inner join localitate as t on l.to=t.id where l.from=".$location." order by l.distance");
		if (!is_null($o)){
			return $o;
		} else {	
			return null;
		}	
	}				
}

?>
