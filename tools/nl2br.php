<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
//class NewsWebPage extends LocationFilterWebPage {
class Nl2brNewsWebPage extends MainWebPage {
	public $rowsperpage=10;
	function __construct(){
		parent::__construct();
		$this->setCSS("style/news.css");
		//$t="Imobiliare: Raionul-".User::getCurrentRaion()->name." Localitatea-".User::getCurrentLocation()->name;
		//$t="Stiri, Noutati, Evenimente, ... din Republica Moldova";
		$t="nl2br";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->show();		
	}
	function show($html=""){
		$out='<div id="container">';
		$out.=$this->UpdateNews();
		$out.='</div>';
		//LocationFilterWebPage::show($out);
		MainWebPage::show($out);
	}
	function UpdateNews(){

		$n=new News();
		$ns=$n->getAll("id not in (490)");
		
		foreach($ns as $n){
			$n->text="<p>".nl2br($n->text)."</p>";
			$n->save();
		}
		$out.='done';
		return $out;
	}
}
$n=new Nl2brNewsWebPage();

?>
