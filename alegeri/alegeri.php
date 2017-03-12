<?php
require_once(__DIR__ . '/../main/loader.php');
 
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

		$m=new Sectiidevot();
		$ms=$m->getAllSectii();
		foreach($ms as $m){
			$out.='<marker ';
			$out.='title="'.$this->parseToXML($m->localitate).'" ';
			$out.='description="'.$this->parseToXML($m->adresa).'" ';
			$out.='lat="'.$m->lat.'" ';
			$out.='lng="'.$m->lng.'" ';
			
			$out.= (($m->winner==8)?'type="red" ':'type="yellow" ');
			
			$out.='link="'.htmlspecialchars($m->getUrl(Config::$alegerisite.'/index.php','action=viewsectie&id='.$m->id)).'" ';
			//$out.='link="'.Config::$mapssite.'/index.php?action=viewmap&id='.$m->id.'" ';								
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
