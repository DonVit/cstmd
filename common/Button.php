<?php
class Table extends Object {
	private $title="buttonTitle";
	private $currentPage;
	private $clicklink;
	
	
	public function __construct(){
	}
	public function setCurrentPage($currentPage){
		$this->currentPage=$currentPage;
		if(isset($currentPage->page)){
			$this->setPage($currentPage->page);
		}
	}	
	public function getCurrentPage(){
		return $this->currentPage;
	}
	public function setClickLink($clicklink){
		$this->clicklink=$clicklink;
	}
	public function setTitle($title){
		$this->title=$title;
	}	

	public function show(){
		$out='';
		$n=$this->clicklink;
		if (isset($n)){
				$out.='<a class="link_button" href="'.$n().'" target="_blank">'.$this->title.'</a>';
		}
		
		return $out;	
	}
}
?>
