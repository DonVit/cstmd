<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class FeedBackWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Feedback");
		$this->setLogoTitle("Feedback");
		$this->show();		
	}
	function show($html="FeedBackWebPageHTML"){
		$out='<div id="container">';
		$out.='<div id="left">my left';
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$html;
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}	
}
$n=new FeedBackWebPage();

?>
