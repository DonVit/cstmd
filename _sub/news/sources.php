<?php
/*
 * Created on 25 Feb 2009
 * Este un redirect temporar
 */
require_once('loader.php');
 
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
