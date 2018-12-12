<?php
require_once(__DIR__ . '/../main/loader.php');

class GeoRSSMapsWebPage extends WebPage {
	function __construct(){
		$this->setContentType("application/vnd.google-earth.kml+xml");
		$this->setDownload(true);
		parent::__construct();
		$this->show();
	}
	function show($html=""){
		WebPage::show($this->getXml());
	}
	function getXml(){
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<kml xmlns="http://www.opengis.net/kml/2.2">';
		$out.='<Document>';

		$p=new Property();
		$ps=$p->getAll("lat!=\"\"","",0,50);
		foreach($ps as $p){
			$link=Config::$chiriesite.'/property.php?id='.$p->id;
			$description='<a href="'.$link.'">'.$link.'</a>';
			$out.=$this->getEntry($this->parseToXML($p->getShortDescription()),$this->parseToXML($description),$p->lat,$p->lng,$link);
		}
		$out.='</Document>';
		$out.='</kml>';
		return $out;
	}
	function getEntry($name,$description,$lat,$lng,$link){
		$out='<Placemark>';
		$out.='<name>'.$name.'</name>';
		$out.='<description>'.$description.'</description>';
		$out.='<Point><coordinates>'.$lng.','.$lat.'</coordinates></Point>';
		$out.='</Placemark>';
		return $out;
	}
	function parseToXML($htmlStr){
		$xmlStr=str_replace('<','&lt;',$htmlStr);
		$xmlStr=str_replace('>','&gt;',$xmlStr);
		$xmlStr=str_replace('"','&quot;',$xmlStr);
		$xmlStr=str_replace("'",'&#39;',$xmlStr);
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		return htmlspecialchars($htmlStr);
	}
}
$n=new GeoRSSMapsWebPage();

?>
