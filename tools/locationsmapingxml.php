<?php
require_once(__DIR__ . '/../main/loader.php');
 
class LocationsMapingXmlWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){

		WebPage::show($this->getXml());
	}
	function getXml(){

		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<markers>';
		$localitateid=0;
		if (isset($this->localitateid)){
			$localitateid=$this->localitateid;
		}
		$l=new Location();
		$ls=$l->doSql("select n.id, nt.v, n.latitude, n.longitude, r.lat, r.lng from `node_tags` as nt inner join nodes as n on nt.id=n.id inner join localitate as l on l.id=$localitateid and nt.v=l.name inner join raion as r on l.raion_id=r.id WHERE nt.k='name'");

		foreach($ls as $l){
			$out.='<marker ';
			$out.='id="'.$l->id.'" ';
			$out.='title="'.$this->parseToXML($l->v).'" ';
			$out.='description="'.$this->parseToXML($l->v).'" ';
			$out.='centerlat="'.$l->lat.'" ';
			$out.='centerlng="'.$l->lng.'" ';
			$out.='lat="'.$l->latitude.'" ';
			$out.='lng="'.$l->longitude.'" ';			
			$out.='/>';								
		}		
		
		$out.='</markers>';
		return $out;
	}
	function parseToXML($htmlStr){ 
		$xmlStr=str_replace('<','&lt;',$htmlStr); 
		$xmlStr=str_replace('>','&gt;',$xmlStr); 
		$xmlStr=str_replace('"','&quot;',$xmlStr); 
		$xmlStr=str_replace("'",'&#39;',$xmlStr); 
		$xmlStr=str_replace("&",'&amp;',$xmlStr); 
		return $xmlStr; 
	} 	
}
$n=new LocationsMapingXmlWebPage();

?>
