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

		$m=new Map();
		$ms=$m->getAll();
		foreach($ms as $m){
			$out.='<marker ';
			$out.='title="'.$this->parseToXML($m->title).'" ';
			$out.='description="'.$this->parseToXML($m->description).'" ';
			$out.='lat="'.$m->lat.'" ';
			$out.='lng="'.$m->lng.'" ';
			$out.='type="link" ';	
			$out.='link="'.htmlspecialchars($m->getUrl(Config::$mapssite.'/index.php','action=viewmap&id='.$m->id)).'" ';
			$out.='/>';								
		}

		$p=new Property();
		$ps=$p->getAll("lat!=\"\"");
		foreach($ps as $p){
			$out.='<marker ';
			$out.='title="'.$this->parseToXML($p->getShortDescription()).'" ';
			$out.='description="'.$this->parseToXML($p->getShortDescription()).'" ';
			$out.='lat="'.$p->lat.'" ';
			$out.='lng="'.$p->lng.'" ';
			if ($p->scop_id==1){
				$out.='type="imobil" ';		
				$out.='link="'.Config::$imobilsite.'/property.php?id='.$p->id.'" ';
			}else {
				$out.='type="chirie" ';		
				$out.='link="'.Config::$chiriesite.'/property.php?id='.$p->id.'" ';				
			}								
			$out.='/>';								
		}
		$p=new Photo();
		$ps=$p->getAll();
		foreach($ps as $p){
			$out.='<marker ';
			$out.='title="'.$this->parseToXML($p->title).'" ';
			$out.='description="'.$this->parseToXML($p->note).'" ';
			$out.='lat="'.$p->lat.'" ';
			$out.='lng="'.$p->lng.'" ';
			$out.='type="photo" ';
			$out.='link="'.Config::$imagessite.'/index.php?id='.$p->id.'" ';
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
