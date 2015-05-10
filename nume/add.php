<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class AddLocationWebPage extends MainWebPage {


	function __construct(){
		$this->redirect($this->getUrl(Config::$accountssite."/index.php"));
		
		$this->create();
	}
}
$n=new AddLocationWebPage();
?>
