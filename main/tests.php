<?php
/*
 * Created on 2 Mar 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
class TestsWebPage extends MainWebPage {
	function __construct(){
		MainWebPage::__construct();
		$this->setTitle("Site Tests");
		$this->setLogoTitle("Site Tests");
		$this->show();
	}
	function show(){
		$out='<div id="container">';
		$out.='<div id="center">';
		$out.=$this->testRaion();
		//$out.=$this->testProperty();
		//$out.=$this->testImage();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function testRaion(){
		$r=new Raion();
		$r->name="name";
		//$r->save();
		$os=DBManager::doSql("select count(*) as cnt from raion");
		return $os[0]->toString();
	}
	function testProperty(){
		$i=new Property();
		$i->user_id="1";
		$i->save();
		$o=$i->getById($i->id);
		$o->aria_totala="100";
		$o->save();
		return $o->toString();

	}
	function testImage(){
		$i=new Image();
		$i->imobil_id=1;
		$i->filename="dd";
		$i->filepath="fasdf asdf";
		$i->ddd="ddasd a";
		$i->save();
	}	
}

$t=new TestsWebPage();
echo phpinfo();
?>
