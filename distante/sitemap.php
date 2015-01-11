<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
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
		$ld=new LocationDistance();
		$lds=$ld->getAll();
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($lds as $ld){
			//$link = Config::$imagessite."/index.php?id=".$p->id;
			$link = htmlspecialchars($this->getUrlWithSpecialCharsConverted(Config::$distantesite.'/index.php','from='.$ld->from.'&to='.$ld->to));
			$pubDate = date("Y-m-d", strtotime('2011-11-26'));
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
