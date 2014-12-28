<?php
/*
 * Created on 27 Feb 2009
 *
 */

class NewsCategory extends DBManager {
	public $id;
	public $name_ro;
	public $name_ru;	
	public $name_en;		
	public $description_ro;
	public $description_ru;	
	public $description_en;		
	public $order;
	public $valid;

	function getTableName(){
		return "news_category";
	}
	function getName1(){
		$fieldname="name_".$this->getLang()->name;
		//echo $fieldname;
		return $this->$fieldname;
		//return "menu item";
	}	
}

?>
