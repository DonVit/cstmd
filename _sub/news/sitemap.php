<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class SitemapNewsWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getSitemap());
	}
	function getSitemap(){
		$sql = "select news.id, news.date from news inner join company on news.company_id=company.id and news.valid=1 and news.deleted=0 order by date desc";
		$n=new News();
		$ns=$n->doSql($sql);
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($ns as $n){
			//$link = Config::$newssite."/index.php?id=".$n->id;
			$link = htmlspecialchars($this->getUrl(Config::$newssite.'/index.php','action=viewnews&id='.$n->id));
			$pubDate = date("Y-m-d", strtotime($n->date));
			$out.='<url>';
			$out.='<loc>'.$link.'</loc>';
			$out.='<lastmod>'.$pubDate.'</lastmod>';
			$out.='</url>';
		}
		$out.='</urlset>';
		return $out;
	}
}
$n=new SitemapNewsWebPage();

?>
