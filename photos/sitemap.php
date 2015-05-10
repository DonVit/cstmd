<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class SitemapImagesWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getSitemap());
	}
	function getSitemap(){
		$sql = "select id, title, file, data from photos order by data desc";
		$p=new Photo();
		$ps=$p->doSql($sql);
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($ps as $p){
			//$link = Config::$imagessite."/index.php?id=".$p->id;
			$link = htmlspecialchars($this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/index.php','action=viewimage&id='.$p->id));
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
$n=new SitemapImagesWebPage();

?>
