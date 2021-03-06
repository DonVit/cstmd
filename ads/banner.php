<?php
require_once(__DIR__ . '/../main/loader.php');
 
class AdsWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Ads");
		$this->setLogoTitle("Ads");
				
		if (isset($this->id)){
			$b=new AdsBanner();
			$b->loadById($this->id);
			$b->count();
			$this->redirect($b->link_url);			
		} else {
			$this->redirect(Config::$mainsite);
		}			
	}
	function show($html=""){
		$out='<div id="container">';
		$out.='<div id="center" style="width:100%;">';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
}
$n=new AdsWebPage();
?>
