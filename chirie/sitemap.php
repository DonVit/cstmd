<?php
require_once(__DIR__ . '/../main/loader.php');
 
class SitemapCompaniesWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getSitemap());
	}
	function getSitemap(){
		$sql = "select `id`,`name`,`created_date` FROM `company` where valid=1 order by `created_date` desc";
		$c=new Company();
		$cs=$c->doSql($sql);
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach($cs as $c){
			$link = htmlspecialchars($this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/index.php','action=viewcompany&id='.$c->id));
			$pubDate = date("Y-m-d", strtotime($c->created_date));
			$out.='<url>';
			$out.='<loc>'.$link.'</loc>';
			$out.='<lastmod>'.$pubDate.'</lastmod>';
			$out.='</url>';
		}
		$out.='</urlset>';
		return $out;
	}
}
$n=new SitemapCompaniesWebPage();

?>
