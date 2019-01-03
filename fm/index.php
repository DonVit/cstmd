<?php
require_once(__DIR__ . '/../main/loader.php');

class IndexFmWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("FM Statii din Republica Moldova");
		$this->setLogoTitle("Statii Radion FM Live din Republica Moldova");
		$this->setCenterContainer('<iframe width="100%" height="100%" frameborder="0" allowfullscreen src="index.htm"></iframe>');
		$this->show();
	}
	function show($out=''){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="center" class="container center" style="width:1000px;height:1400px">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
}
$n=new IndexFmWebPage();
?>
