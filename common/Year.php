<?php
class Year extends DBManager {
	public $id;
	public $startdate;
	public $enddate;
	public $animal;
	public $element;
	public $stem;
	public $branch;
	function getTableName(){
		return "year";
	}
	public static function getYearByDate($date){
		$y=new Year();
		$ys=$y->getAll();		
		$rt=$ys[0];
		
		foreach ($ys as $yt){
			$dt_start=DateTime::createFromFormat("d-m-Y",$yt->startdate);
			$dt_end=DateTime::createFromFormat("d-m-Y",$yt->enddate);
			if (($date>=$dt_start)&&($date<=$dt_end)){
				$rt=$yt;
				break;
			}
		}
		return $rt;
	}
	
}

?>
