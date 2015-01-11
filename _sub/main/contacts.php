<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('common/Config.php');
 
class ContactsWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Contacte");
		$this->setLogoTitle("Contacte");
		$this->show();		
	}
	function show($html="ContactsWebPageHTML"){
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
$n=new ContactsWebPage();

?>
