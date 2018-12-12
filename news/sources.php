<?php
require_once(__DIR__ . '/../main/loader.php');
 
class SourceWebPage extends MainWebPage {

	function __construct(){
		parent::__construct();
		$url=Config::$companiesite;
		if (isset($this->id)){
			$url=$url.'/index.php?id='.$this->id;
		}
		if (isset($this->t)){
			$url=$url.'/index.php?view=companies&type='.$this->t;
		}		
		$this->redirect($url);
	
	}
}
$s=new SourceWebPage();

?>
