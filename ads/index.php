<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class AdsWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Ads");
		$this->setLogoTitle("Ads");
		
		
		
		if (isset($this->id)){
			$m=new AdsMessage();
			$m->loadbyid($this->id);
			if (isset($m->id)){
				$this->setTitle($m->title);
				$this->setLogoTitle($m->title);
				$this->msg=$m;
			} else {
				$this->redirect(Config::$mainsite);
			}
			
		} else {
			$this->redirect(Config::$mainsite);
		}
		
		$this->show();		
	}
	function show($html=""){
		$out='<div id="container">';
		//$out.='<div id="left">my left';
		//$out.='</div>';
		$out.='<div id="center" style="width:100%;">';
		$out.=$this->getMessage();
		$out.='</div>';		
		//$out.='<div id="right"> my right';
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	/*
	 * get last 30 days
	 */
	function getMessage(){
			
		return $this->msg->text;
	}
}
$n=new AdsWebPage();
?>
