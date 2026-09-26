<?php
require_once(__DIR__ . '/../main/loader.php');

class JsonMapsWebPage extends WebPage {
	function __construct(){
		$this->setContentType("application/json");
		parent::__construct();


		$this->show();
	}
	function show($html=""){

		WebPage::show($this->getJson());
	}
	function getJson(){


		$markers = array();

		$l=new Nume();
		$l->loadById($this->id);
		$ls=$l->getLocations();
		foreach($ls as $l){
			$marker = array(
				'lat' => $l->localitate_lat,
				'lng' => $l->localitate_lng
			);
			$markers[] = $marker;
		}

		return json_encode(array('markers' => $markers));
	}
}
$n=new JsonMapsWebPage();

?>