<?php
require_once('loader.php');
 
class IndexWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setCSS(Config::$mainsite."/style/main.css");
		$t="PORTAL IMOBILIAR DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->show();		
	}
	function show($html="IndexWebPageHTML"){
		$fl=new FeedLog();
		$fl->getRssFeeds();
		$fi=new FeedItem();
		$fi->itentifyNewItems();
		
		$out='<div id="container" class="container">';
		$out.='<div>';
		$out.=$this->getTodayNews();
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
		$ns=$n->doSql("select fi.id,title,link,fi.description,pubdate,companyid,c.name from feeditem as fi inner join company as c on fi.companyid=c.id where fi.status=1 and createdat>='".$now->format('Y-m-d')."' order by fi.id desc");
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
}
$n=new IndexWebPage();
?>
