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
		$out.=$this->getQueriesBySession();
		$out.='</div>';		
		//$out.='<div id="right"> my right';
		//$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}

	function getQueriesBySession(){
		if (!isset($this->session)) {
			//need to set to last session
			$this->session=0;	
		}		
		$sql='select datetime,ip,referer,query from views where session=\''.$this->session.'\' order by datetime';		
		$vs=DBManager::doSql($sql);
		$out='<h1>Report By datetime,ip,referer,query on '.$this->session.'</h1>';		
		$out.='<table class="grid">';
		$out.='<tr><th class="gridth">datetime</th><th>ip</th><th>referer</th><th>query</th></tr>';
		if (count($vs)!=0){
			$i=0;
			foreach($vs as $v){
				$i++;
				$out.='<tr class="gridtr'.($i & 1).'"><td class="gridtd">'.$v->datetime.'</td><td class="gridtd">'.$v->ip.'</td><td class="gridtd"><a href="'.$v->referer.'">'.$v->referer.'</a></td><td class="gridtd"><a href="'.$v->query.'">'.$v->query.'</a></td></tr>';
			}
		}
		$out.='</table>';	
		return $out;		
	}		
}
$n=new StatsWebPage();
?>
