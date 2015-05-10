<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class StatsWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Statistica");
		$this->setLogoTitle("Statistica");
		$this->show();		
	}
	function show($html=""){
		$out='<div id="container">';
		//$out.='<div id="left">my left';
		//$out.='</div>';
		$out.='<div id="center" style="width:100%;">';
		$out.=$this->getDates();
		$out.='</div>';		
		//$out.='<div id="right"> my right';
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	/*
	 * get last 30 days
	 */
	function getDates(){
		$sql='select date(datetime) as date, count(distinct session) as sessions, count(id) as queries  from views where date(datetime)>=DATE_ADD(CURDATE(),INTERVAL -31 DAY) group by date(datetime) order by date(datetime) desc';		
		$vs=DBManager::doSql($sql);
		$out='<h1>Report by Date, Sessions, Queries</h1>';
		$out.='<table class="grid" width="50%">';
		$out.='<tr><th class="gridth">Date</th><th>Sessions</th><th>Queries</th></tr>';
		if (count($vs)!=0){
			$i=0;
			foreach($vs as $v){
				$i++;
				$out.='<tr class="gridtr'.($i & 1).'"><td class="gridtd"><a href="'.Config::$statssite.'/sessions.php?date='.$v->date.'">'.$v->date.'</td><td class="gridtd">'.$v->sessions.'</td><td class="gridtd">'.$v->queries.'</td></tr>';
			}
		}
		$out.='</table>';	
		//echo phpinfo();		
		return $out;
	}
}
$n=new StatsWebPage();
?>
