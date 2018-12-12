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
		$sql = "select id, title, data from maps order by data desc";
		$m=new Photo();
		$ms=$m->doSql($sql);
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($ms as $m){
			//$link = Config::$imagessite."/index.php?id=".$p->id;
			$link = htmlspecialchars($this->getUrlWithSpecialCharsConverted(Config::$mapssite.'/index.php','action=viewmap&id='.$m->id));
			$pubDate = date("Y-m-d", strtotime($m->data));
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
