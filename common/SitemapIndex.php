<?php
class SitemapIndex extends Object {
	private $loc;
	private $lastmod;
	private $dataset;
	
	public function __construct(){
	}
	public function setLocFunc($loc){
		$this->loc=$loc;
	}
	public function setLastModFunc($lastmod){
		$this->lastmod=$lastmod;
	}	
	public function setDataSet($dataset){
		$this->dataset=$dataset;
	}
	public function getDataSet(){
		return $this->dataset;
	}
	public function show(){
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$locfunc=$this->loc;
		$lastmodfunc=$this->lastmod;
		
		if (!(is_bool($this->getDataSet()) === true)) {
			while($row = mysql_fetch_object($this->getDataSet())){
				$out.='<sitemap>';
				$out.='<loc>'.$locfunc($row).'</loc>';
				$out.='<lastmod>'.$lastmodfunc($row).'</lastmod>';				
				$out.='</sitemap>';
			}
		}
		$out.='</sitemapindex>';		
		
		return $out;	
	}
}
?>
