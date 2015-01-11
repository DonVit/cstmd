<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
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
		//$out.='<div id="left">my left';
		//$out.='</div>';
		$out.='<div id="center" style="width:100%;">';
		$out.='</div>';		
		//$out.='<div id="right"> my right';
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
}
$n=new AdsWebPage();
?>
