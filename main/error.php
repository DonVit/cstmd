<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class ErrorWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$t="Hopa, ceva errrrori au aparut";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->create();		
	}
	function actionDefault($html="IndexWebPageHTML"){
		$out='<div id="container">';						
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$this->getTitle();
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		
		
		MainWebPage::show($out);
	}

}
$n=new ErrorWebPage();
?>
