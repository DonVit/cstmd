<?php
require_once(__DIR__ . '/../main/loader.php');
 
class KMLNewsWebPage extends WebPage {
	function __construct(){
		//$this->setContentType("text/xml");
		//$this->setDownload(true);
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getKML());
	}
	function getKML(){
		$sql = "select news.id, news.date from news inner join company on news.company_id=company.id and news.valid=1 and news.deleted=0 order by date desc";
		//$n=new News();
		//$ns=$n->doSql($sql);
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<kml xmlns="http://www.opengis.net/kml/2.2">';
		$out.='<Document>';
		$out.='<Placemark>';
		$out.='<name>Simple placemark</name>';
		$out.='<description>Desc</description>';
		$out.='<Point>';
		$out.='<coordinates>-122.0822035425683,37.42228990140251,10000</coordinates>';
		$out.='</Point>';
		$out.='</Placemark>';	
		$out.='</Document>';		
		$out.='</kml>';
		return $out;
	}
}
$n=new KMLNewsWebPage();

?>
