<?php
require_once(__DIR__ . '/../main/loader.php');
 
class SitemapDaysWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getSitemap());
	}
	function getSitemap(){
		$d=new Day();
		$ds=$d->getAll();
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($ds as $d){
			$link = htmlspecialchars(Config::$calendarsite."/index.php?action=viewdate&id=".$d->dayid);		
			$pubDate = "2019-07-29";
			$out.='<url>';
			$out.='<loc>'.$link.'</loc>';
			$out.='<lastmod>'.$pubDate.'</lastmod>';
			$out.='</url>';
		}
		$out.='</urlset>';
		return $out;
	}
}
$n=new SitemapDaysWebPage();

?>
