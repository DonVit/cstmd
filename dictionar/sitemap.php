<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class SitemapLocationsWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getSitemap());
	}
	function getSitemap(){
		$d=new Dictionar();
		$ds=$d->getAll();
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($ds as $d){
			$link = htmlspecialchars(Config::$dictionarsite."/index.php?action=viewdictionar&id=".$d->id);		
			$pubDate = "2013-02-03";
			$out.='<url>';
			$out.='<loc>'.$link.'</loc>';
			$out.='<lastmod>'.$pubDate.'</lastmod>';
			$out.='</url>';
		}
		$out.='</urlset>';
		return $out;
	}
}
$n=new SitemapLocationsWebPage();

?>
