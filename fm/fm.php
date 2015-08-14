<?php

require_once(__DIR__ . '/../main/loader.php');

class Fms extends WebPage {
	function __construct(){
		parent::__construct();
		$this->setContentType("text/json");
		$this->show();
	}
	function show($html="LocationsWebPageHTML"){
		$out='';
		$fm=new Fm();
		$fms=$fm->getAll("stream_url!=''","register_order");
		$out.=json_encode($fms);				
		WebPage::show($out);
	} 	
}
$n=new Fms();
?>
