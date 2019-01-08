<?php
class Zodiac extends DBManager {
	public $id;
	public $name;
	public $startdate;
	public $enddate;
	public $startnr;
	public $endnr;
	public $sign;
	function getTableName(){
		return "zodiac";
	}
	public static function getZodiacByDate($date){
		$z=new Zodiac();
		$zs=$z->getAll();

		$datenr=$date->format('md');
		$rt=$zs[9];
		
		foreach ($zs as $zt){
			if (($datenr>=$zt->startnr)&&($datenr<=$zt->endnr)){
				$rt=$zt;
				break;
			}
		}
		return $rt;
	}
	
}

?>
