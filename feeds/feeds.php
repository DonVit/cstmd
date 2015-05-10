<?php
set_time_limit(10720);
require_once(__DIR__ . '/../main/loader.php');
 
class IndexWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setCSS(Config::$mainsite."/style/main.css");
		$t="FEEDS CRAWLER DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->create();	
	}
	function actionDefault(){
		$this->setLeftContainer($this->getGroupBoxH3("Menu", $this->getMenuFeeds()));	
		$this->setCenterContainer($this->getGroupBoxH1("Ultimile Joburi",$this->getLatestJobs()));
		$this->show();
	}
	function actionCrawl(){
		$f=new FeedJob();
		$f->started_at=System::getCurentDateTime();
		$f->save();	
		$f->getRssFeeds();	
		$f->ended_at=System::getCurentDateTime();
		$f->save();			
		$this->setLeftContainer($this->getGroupBoxH3("Menu", $this->getMenuFeeds()));	
		$this->setCenterContainer($this->getGroupBoxH1("Ultimile Joburi",$this->getLatestJobs()));
		$this->show();
	}
	function actionRedirect(){
			if (isset($this->id)&&(is_numeric($this->id))){
			$fi=new FeedItem();
			$fi->loadById($this->id);
			if ($fi->link!=""){
				$fi->count();
				$this->redirect($fi->link);
			} else {
				$this->redirect("index.php");
			}
		} else {
			$this->redirect("index.php");
		}
	}	
	function getMenuFeeds(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("feeds.php").'">Ultimile Joburi</a></li>';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("feeds.php","action=crawl").'">Crawl News</a></li>';				
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Ultimele Stiri</a></li>';				
		$out.='</ul>';
		return $out;
	}			
	function show($html="IndexWebPageHTML"){

		
		$out='<div id="container" class="container">';
		$out.='<div id="left" class="container left" style="width:198px;">';
		$out.=$this->getLeftContainer();
		$out.='</div>';				
		$out.='<div id="center" class="container center" style="width:798px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';			
		MainWebPage::show($out);
	}		
	function getLastNews(){
		$out="";
		$n=new FeedItem();
		$ns=$n->doSql("select fi.id,title,link,fi.description,pubdate,companyid,c.name from feeditem as fi inner join company as c on fi.companyid=c.id where fi.status=1 order by fi.id desc");
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;" class="pure-table pure-table-bordered">';
		$out.='<thead><tr><th style="width:20%;">Nr</th><th style="width:20%;">Compania</th><th style="width:50%;">Title</th><th style="width:30%;text-align:center">Descriere</th><th style="width:30%;text-align:center">Data</th></tr></thead>';		
		$out.="<tbody>";
		if (count($ns)!=0){			
			$c=1;
			foreach($ns as $n){
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$n->id);
				$out.='<tr><td>'.$n->id.'</td><td>'.$n->name.'</td><td><a href="'.$n->link.'">'.$n->title.'</a></td><td>'.$n->description.'</td><td>'.$n->pubdate.'</td></tr>';
				$c=$c+1;	
			}
		}
		$out.="</tbody>";
		$out.="</table>";
		$out.="</div>";	

		
		return $out;
	}
	function getTodayNews(){
		$out="";
		$n=new FeedItem();
		$now=new DateTime();
		$ns=$n->doSql("select fi.id,title,link,fi.description,pubdate,companyid,c.name from feeditem as fi inner join company as c on fi.companyid=c.id where createdat>='".$now->format('Y-m-d')."' order by fi.id desc");
		$out.=$this->getNewsTable($ns);
		return $out;
	}

	function getLatestJobs(){
		$out="";
		$j=new FeedJob();
		$js=$j->getAll("","id desc","0","100");
		$out.=$this->getJobsTable($js);
		return $out;
	}
	function getJobsTable($js){
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;" class="pure-table pure-table-bordered">';
		$out.='<thead><tr><th>Nr</th><th>Start Date</th><th>End Date</th><th>Duration</th></tr></thead>';
		$out.="<tbody>";
		if (count($js)!=0){
			foreach($js as $j){
				$out.='<tr><td>'.$j->id.'</td><td>'.$j->started_at.'</td><td>'.$j->ended_at.'</td><td></td></tr>';
			}
		}
		$out.="</tbody>";
		$out.="</table>";
		$out.="</div>";	
		return $out;
	}			
}
$n=new IndexWebPage();
?>
