<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class AboutWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Despre CasaTA");
		$this->setLogoTitle("Despre CasaTA");
		$this->show();		
	}
	function show($html="AboutWebPageHtml"){
		$out='<div id="container">';
		$out.='<div id="left">my left';
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$html;
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}	
}
$n=new AboutWebPage();

?>
