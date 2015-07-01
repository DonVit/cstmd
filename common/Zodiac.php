<?php
class Zodiac extends DBManager {
	public $id;
	public $name;
	public $startdate;
	public $enddate;
	public $sign;
	function getTableName(){
		return "zodiac";
	}
	public static function getZodiacByDate($date){
		$z=new Zodiac();
		$zs=$z->getAll();
		
		$dt_year=$date->format('Y');
		$rt=$zs[0];
		
		foreach ($zs as $zt){
			$dt_start=DateTime::createFromFormat("dmY",$zt->startdate.$dt_year);
			$dt_end=DateTime::createFromFormat("dmY",$zt->enddate.$dt_year);
			if (($date>=$dt_start)&&($date<=$dt_end)){
				$rt=$zt;
				break;
			}
		}
		return $rt;
	}
	
}

?>
