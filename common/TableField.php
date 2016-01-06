<?php
class TableField extends Object{
	public $id;
	public $title;
	public $source;
	public $style;
	public $openlink;
	function __construct($id,$title,$source,$style,$openlink){
		$this->id=$id;
		$this->title=$title;
		$this->source=$source;
		$this->style=$style;
		$this->openlink=$openlink;
	}
}
?>
