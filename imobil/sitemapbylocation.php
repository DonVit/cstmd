<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class SitemapImobilByLocationWebPage extends WebPage {
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
			$link = htmlspecialchars(Config::$imobilsite."/index.php?raionid=".$r->id);
			$pubDate = date("Y-m-d", strtotime("2011-10-05"));
			$out.='<url>';
			$out.='<loc>'.$link.'</loc>';
			$out.='<lastmod>'.$pubDate.'</lastmod>';
			$out.='</url>';
			$l=new Location();
			$ls=$l->getAll("raion_id=".$r->id);
			foreach($ls as $l){
				$link = htmlspecialchars(Config::$imobilsite."/index.php?raionid=".$r->id."&locationid=".$l->id);
				$pubDate = date("Y-m-d", strtotime("2011-10-05"));
				$out.='<url>';
				$out.='<loc>'.$link.'</loc>';
				$out.='<lastmod>'.$pubDate.'</lastmod>';
				$out.='</url>';				
			}			
		}
		$out.='</urlset>';
		return $out;
	}
}
$n=new SitemapImobilByLocationWebPage();

?>
