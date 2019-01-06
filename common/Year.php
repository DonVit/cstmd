<?php
class Year extends DBManager {
	public $id;
	public $startdate;
	public $enddate;
	public $animal;
	public $animal_ro;
	public $animal_url;
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
			$dt_start=DateTime::createFromFormat("Y-m-d",$yt->startdate);
			$dt_end=DateTime::createFromFormat("Y-m-d",$yt->enddate);

			//var_dump($dt_start->format('Y-m-d H:i:s'));
            //echo $dt_start->format('d/m/Y');
            //echo $yt->startdate.'-'.$yt->enddate.'<br>';
			if (($date>$dt_start)&&($date<$dt_end)){
				$rt=$yt;
				break;
			}
		}
		return $rt;
	}
	
}

?>
