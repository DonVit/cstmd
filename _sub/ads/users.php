<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
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
		$out.=$this->getSessionsByDate();
		$out.='</div>';		
		//$out.='<div id="right"> my right';
		//$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}

	function getSessionsByDate(){
		if (!isset($this->date)) {
			$this->date=System::getCurentDate();	
		}		
		$sql='select datetime, session, ip, count(id) as queries from views where date(datetime)=\''.$this->date.'\' group by session, ip having count(*)>1 order by datetime desc';		
		$vs=DBManager::doSql($sql);
		$out='<h1>Report By Sessions, IPs, Queries, Time on '.$this->date.'</h1>';		
		$out.='<table class="grid">';
		$out.='<tr><th class="gridth">id</th><th>Session Start</th><th>Session</th><th>ip</th><th>Queries</th><th>Duration</th></tr>';
		if (count($vs)!=0){
			$i=0;
			foreach($vs as $v){
				$i++;
				$out.='<tr class="gridtr'.($i & 1).'"><td>'.$i.'</td><td>'.$v->datetime.'</td><td class="gridtd"><a href="'.Config::$statssite.'/queries.php?session='.$v->session.'">'.$v->session.'</td><td class="gridtd">'.$v->ip.'</td><td class="gridtd">'.$v->queries.'</td><td class="gridtd">-</td></tr>';
			}
		}
		$out.='</table>';	
		return $out;		
	}		
}
$n=new StatsWebPage();
?>
