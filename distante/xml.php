<?php
require_once(__DIR__ . '/../main/loader.php');
 
class XmlLocalitatiWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		
		if (!isset($this->raion_id)){
			$this->raion_id=0;
		}
		$this->show();		
	}
	function show($html=""){

		WebPage::show($this->getXml());
	}
	function getXml(){
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<locations>';
		$l=new Location();
		$ls=$l->getAll("raion_id=".$this->raion_id,"`order`,`oras` desc,`name`");
		if (count($ls)!=0){
			foreach($ls as $l){
				$out.='<location id="'.$l->id.'" name="'.$l->getFullName().'">';
				$out.='</location>';
			}
		}
		$out.='</locations>';
		return $out;
	}
}
$l=new XmlLocalitatiWebPage();

?>
