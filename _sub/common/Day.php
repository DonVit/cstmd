<?php
/*
 * Created on 27 Feb 2009
 *
 */
class Day extends DBManager {
	public $dayid;
	public $date;
	public $year;
	public $month;
	public $day;
	public $week;
	public $weekday;
	function getTableName(){
		return "day";
	}
	public static function addDaysByYear($year){
		$dt=new DateTime();
		$dt->setTimestamp(mktime(0,0,0,1,1,$year));	
		$onedayinterval = DateInterval::createfromdatestring('+1 day');
		$curyear=$dt->format("Y");
		
		while ($curyear==$year){
			$d=new Day();
			$d->dayid=$dt->format("Ymd");
			$d->date=$dt->format("Y-m-d");
			$d->year=$dt->format("Y");
			$d->month=$dt->format("n");
			$d->day=$dt->format("j");
			$d->week=$dt->format("W");
			$d->weekday=$dt->format("N");
			$d->save();
			$dt->add($onedayinterval);
			$curyear=$dt->format("Y");
		}
	}
	public static function getFreeDaysByYear($year){
		
		$ds=Day::doSql("select count(*) as cnt from day where year=".$year." and weekday in (6,7)");
		return $ds[0]->cnt;

	}
	public static function getFreeDaysFromDate($year, $date){
	
		$ds=Day::doSql("select count(*) as cnt from day where year=".$year." and weekday in (6,7) and dayid>=".$date->format("Ymd"));
		return $ds[0]->cnt;
	
	}

	public static function doesDayExists($date){
	
		$dayexists=true;
		$ds=Day::doSql("select count(*) as cnt from day where dayid=".$date->format("Ymd"));
		if ($ds[0]->cnt==0){
			$dayexists=false;
		}
		return $dayexists;
	}	
	
}

?>
