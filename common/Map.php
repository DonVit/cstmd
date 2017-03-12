<?php
class Map extends DBManager {
	public $id;
	public $title;
	public $description;
	public $lat;
	public $lng;
	public $centerlat;
	public $centerlng;	
	public $zoom;
	public $data;
	public $maptype;						
	public $contor;
	function getTableName(){
		return "maps";
	}
	public static function getDirectDistance($lng1,$lat1,$lng2,$lat2){
		$d=(6371*acos(cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($lng2)-deg2rad($lng1))+sin(deg2rad($lat1))*sin(deg2rad($lat2))));
		//select l1.id,l2.id,(6371*acos(cos(radians(l1.lat))*cos(radians(l2.lat))*cos(radians(l2.lng)-radians(l1.lng))+sin(radians(l1.lat))*sin(radians(l2.lat)))) from `distance` as d1 inner join localitate as l1 on d1.from=l1.id inner join localitate as l2 on d1.to=l2.id
		return $d; 
	}
	function getData(){
		return substr($this->data,0,10);
	}		
}

?>
