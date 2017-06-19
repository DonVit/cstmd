<?php

class Album extends DBManager {
	public $id;
	public $title;
	public $description;	
	public $raion_id;
	public $localitate_id;
	public $centerlat;
	public $centerlng;
	public $lat;
	public $lng;
	public $zoom;
	public $maptype;
	public $user_id;
	public $data;
	public $contor;						
	function getTableName(){
		return "albums";
	}	
	function setRaion($raion){
		$this->raion_id=$raion->id;
		$this->centerlat=$raion->lat;
		$this->centerlng=$raion->lng;
		$this->lat=0;
		$this->lng=0;
		$this->zoom=$raion->zoom;
		$this->maptype=3;
	}	
	function getPhotos(){
		$files=array();
		$sql="SELECT photos.* FROM photos where album_id=".$this->id;
		$ps=$this->doSql($sql);
		return $ps;		
		
	}
}

?>
