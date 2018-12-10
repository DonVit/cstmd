<?php
require_once(__DIR__ . '/../main/loader.php');
 
class AddPrimarieWebPage extends MainWebPage {


	function __construct(){
		$this->redirect($this->getUrl(Config::$accountssite."/index.php"));
		
		$this->create();
	}
}
$n=new AddPrimarieWebPage();
?>
