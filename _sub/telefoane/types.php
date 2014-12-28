<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class ImagesWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setCSS("style/styles.css");
		$t="Companii din Republica Moldova";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->show();		
	}
	function show($html=""){
		$out='<div id="container">';		
		$out.='<div id="left">';
		$out.=$this->getLeftContainer();
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right">';
		$out.=$this->getRightContainer();		
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';	
		MainWebPage::show($out);
	}
	function getLeftContainer(){
		$out="";
		$out.=$this->getCompanyList();
		$out.=$this->getCompanyTypeList();		
		//$out.=$this->getCompanyTypes();
		return $out;	
	}	
	function getCenterContainer(){
		$out="";
		$out.=$this->getCompanyTypes();
		return $out;	}
	function getRightContainer(){
		$out="";
		return $out;				
	}
	function getCompanyList(){
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php">Lista Companii</a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $out;
	}
	function getCompanyTypeList(){
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="types.php">Lista Tipuri Companii</a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $out;
	}			
	function getCompanyTypes(){
		$c=new CompanyType;
		$cs=$c->getAll("","`order`");
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		foreach ($cs as $c){
			$out.='<li><a href="index.php?type='.$c->id.'">'.$c->name.'</a></li>';
		}
		$out.="</ul>";
		$out.="</div>";
		return $out;
	}	
}
$n=new ImagesWebPage();

?>
