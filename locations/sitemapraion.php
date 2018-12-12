<?php
require_once(__DIR__ . '/../main/loader.php');
 
class SitemapRaionsWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getSitemap());
	}
	function getSitemap(){
		$r=new Raion();
		$rs=$r->getAll();
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($rs as $r){
			$link = htmlspecialchars(Config::$locationssite."/index.php?action=viewraion&id=".$r->id);
			$pubDate = "2010-11-27";
			$out.='<url>';
			$out.='<loc>'.$link.'</loc>';
			$out.='<lastmod>'.$pubDate.'</lastmod>';
			$out.='</url>';
		}
		$out.='</urlset>';
		return $out;
	}
}
$n=new SitemapRaionsWebPage();

?>
