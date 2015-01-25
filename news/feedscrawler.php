<?php
//ini_set("memory_limit","200M");
set_time_limit(10720);
require_once('loader.php');

class IndexWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		//$this->setCSS("style/main.css");
		$this->setCSS(Config::$mainsite."/style/main.css");
		//$t="Acasa: Raionul-".User::getCurrentRaion()->name." Localitatea-".User::getCurrentLocation()->name;
		$t="PORTAL IMOBILIAR DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->show();		
	}
	function show($html="IndexWebPageHTML"){
		
		$fl=new FeedLog();
		$fl->getRssFeeds();
		$out='';
		$out.='<div id="container" class="container">';
		$out.='<div>';
		$out.=$this->getStatus();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';	
		MainWebPage::show($out);
	}	

	function getStatus(){
		$out="";
		$fl=new FeedLog();
		
		$fs=$fl->getAtemptsForToday();
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;" class="pure-table pure-table-bordered">';
		$out.='<thead><tr><th>Nr</th><th>Companie Id</th><th>Download Status</th><th>Parse Status</th><th>Error</th><th>Atempt At</th></tr></thead>';
		$out.="<tbody>";
		
		$c=1;
		foreach($fs as $f){
				$out.='<tr><td>'.$c.'</td><td>'.$f->companyid.'</td><td>'.$f->downloadstatus.'</td><td>'.$f->parsestatus.'</td><td>'.$f->error.'</td><td>'.$f->attemptat.'</td></tr>';
				$c=$c+1;
		}
		$out.="</tbody>";
		$out.="</table>";
		$out.="</div>";
			
			
		return $out;
	}	
}
$n=new IndexWebPage();
?>
