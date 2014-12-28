<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class SitemapImobilWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getSitemap());
	}
	function getSitemap(){
		$p=new Property();
		$ps=$p->getAll("scop_id in (1,3)","id desc");
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($ps as $p){
			$link = htmlspecialchars(Config::$imobilsite."/property.php?id=".$p->id);
			$pubDate = date("Y-m-d", strtotime($p->data));
			$out.='<url>';
			$out.='<loc>'.$link.'</loc>';
			$out.='<lastmod>'.$pubDate.'</lastmod>';
			$out.='</url>';
		}
		$out.='</urlset>';
		return $out;
	}
}
$n=new SitemapImobilWebPage();

?>
