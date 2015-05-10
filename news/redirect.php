<?php
/*
 * Created on 14 Apr 2008
 *
 */
require_once(__DIR__ . '/../main/loader.php');
class NewsRedirectWebPage extends WebPage {
	function __construct(){
		parent::__construct();
		
		if (isset($this->id)&&(is_numeric($this->id))){
			$n=new News();
			$n->loadById($this->id);
			if ($n->url!=""){
				$this->redirect($n->url);
			} else {
				$this->redirect("index.php");
			}
		} else {
			$this->redirect("index.php");
		}
	}
}
$nr=new NewsRedirectWebPage();
?>
