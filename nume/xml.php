<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class XmlMapsWebPage extends WebPage {
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

		$l=new Nume();
		$l->loadById($this->id);
		$ls=$l->getLocations();
		foreach($ls as $l){
			$out.='<marker ';
			$out.='lat="'.$l->localitate_lat.'" ';
			$out.='lng="'.$l->localitate_lng.'" ';
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
$n=new XmlMapsWebPage();

?>
