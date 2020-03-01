<?php
require_once(__DIR__ . '/../main/loader.php');
 
class JsonMapsWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/json");
		$this->setCORS(true);
		parent::__construct();
		$this->show();		
	}
	function show($html=""){
		WebPage::show($this->getXml());
	}
	function getXml(){

		$p=new Photo();
		$ps=$p->getAll();
		return  json_encode($ps);
	}	
}
$n=new JsonMapsWebPage();

?>
