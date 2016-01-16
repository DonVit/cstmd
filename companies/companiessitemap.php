<?php

require_once(__DIR__ . '/../main/loader.php');
 
class SitemapCompaniesWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		if (isset($this->id)){
			WebPage::show($this->getSitemapByRaion($this->id));
		} else {
			WebPage::show($this->getSitemap());
		}
	}
	function getSitemapByRaion($raionid){
		
		$c=new CoCompany();
		if ($raionid!=0){
			$cs=$c->getCompaniesByRaion($raionid,"2 desc",0,30000);
		}		
		
		$sitemap=new Sitemap();
		$sitemap->setDataSet($cs);
		$locfunc=function($row){
			return $this->getUrlWithSpecialCharsConverted(Config::$companiesite."/companies.php","action=viewcompany&id=".$row->id);
		};
		$sitemap->setLocFunc($locfunc);
		$lastmodfunc=function($row){
			return $row->created_at;
		};
		$sitemap->setLastModFunc($lastmodfunc);
				
		$out=$sitemap->show();
		return $out;
	}
	function getSitemap(){
	
		$r=new Raion();
		$rs=$r->sql("select id, name from raion");
	
		$sitemap=new Sitemap();
		$sitemap->setDataSet($rs);
		$locfunc=function($row){
			return htmlspecialchars(Config::$companiesite."/companiessitemap.php?id=".$row->id);
		};
		$sitemap->setLocFunc($locfunc);
		$lastmodfunc=function($row){
			return '2015-12-18';
		};
		$sitemap->setLastModFunc($lastmodfunc);
	
		$out=$sitemap->show();
		return $out;
	}	
}
$n=new SitemapCompaniesWebPage();

?>
