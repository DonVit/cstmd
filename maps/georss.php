<?php
require_once(__DIR__ . '/../main/loader.php');
 
class GeoRSSMapsWebPage extends WebPage {
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
		$out.='<feed xmlns="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss">';
		$out.='<title>GeoRSS Maps CasaTa</title>';
				
		$p=new Property();
		$ps=$p->getAll("lat!=\"\"");
		foreach($ps as $p){
			//$out.='<marker ';
			//$out.='title="'.$this->parseToXML($p->getShortDescription()).'" ';
			//$out.='description="'.$this->parseToXML($p->getShortDescription()).'" ';
			//$out.='lat="'.$p->lat.'" ';
			//$out.='lng="'.$p->lng.'" ';
			//if ($p->scop_id==1){
			//	$out.='type="imobil" ';		
			//	$out.='link="'.Config::$imobilsite.'/property.php?id='.$p->id.'" ';
			//}else {
			//	$out.='type="chirie" ';		
			//	$out.='link="'.Config::$chiriesite.'/property.php?id='.$p->id.'" ';				
			//}								
			//$out.='/>';
			$link=Config::$chiriesite.'/property.php?id='.$p->id;
			$description='<a href="'.$link.'">'.$link.'</a>';	
			$out.=$this->getEntry($this->parseToXML($p->getShortDescription()),$this->parseToXML($description),$p->lat,$p->lng,$link);							
		}						
		
		$out.='</feed>';
		return $out;
	}
	function getEntry($title,$description,$lat,$lng,$link){
		$out.='<entry>';
		$out.='<title>'.$title.'</title>';
		$out.='<link href="'.$link.'"/>';
		$out.='<content type="html">'.$description.'</content>';
		$out.='<georss:point>'.$lat.' '.$lng.'</georss:point>';
		
		$out.='</entry>';
		return $out; 
	}
	function parseToXML($htmlStr){ 
		$xmlStr=str_replace('<','&lt;',$htmlStr); 
		$xmlStr=str_replace('>','&gt;',$xmlStr); 
		$xmlStr=str_replace('"','&quot;',$xmlStr); 
		$xmlStr=str_replace("'",'&#39;',$xmlStr); 
		$xmlStr=str_replace("&",'&amp;',$xmlStr); 
		//return $xmlStr; 
		return htmlspecialchars($htmlStr);
	} 	
}
$n=new GeoRSSMapsWebPage();

?>
