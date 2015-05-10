<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class LogoutWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->logout();
		$this->redirect("index.php");			
	}	
}
$l=new LogoutWebPage();

?>
