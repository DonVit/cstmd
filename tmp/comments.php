<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class CommentsWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
				
		$this->create();
	}
	function actionDefault(){
		$this->setLogoTitle("Comments");
		$this->setTitle("Comments");

		$this->show("Comments");
	}
	function actionValidate(){
		
		if (isset($this->id)){
			$c=new Comment();
			$c->loadById($this->id);
			$c->deleted=0;
			$c->save();
			Mail::send_commentaprove_email($c);
		}
		$this->setTitle("Comments");
	
		$this->show("Comment was approved");
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
$n=new CommentsWebPage();

?>
