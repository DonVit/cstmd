<?php
require_once(__DIR__ . '/../main/loader.php');
 
class SitemapMapsWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getSitemap());
	}
	function getSitemap(){
		
		$s=new Sectiidevot();
		$ss=$s->getAll();
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($ss as $s){
			$link = $this->getUrlWithSpecialCharsConverted(Config::$alegerisite.'/index.php','action=viewsectie&id='.$s->id);
			$pubDate = date("Y-m-d", strtotime($s->data));
			$out.='<url>';
			$out.='<loc>'.$link.'</loc>';
			$out.='<lastmod>'.$pubDate.'</lastmod>';
			$out.='</url>';
		}
		$out.='</urlset>';
		return $out;
	}
}
$n=new SitemapMapsWebPage();

?>
