<?php
/*
 * Created on 27 Feb 2009
 *
 */
class FeedItem extends DBManager {
	public $id;
	public $companyid;
	public $title;
	public $link;
	public $description;
	public $pubdate;
	public $createdat;
	public $status;
	function getTableName(){
		return "feeditem";
	}
	public static function isNew($feeditem){
		$fi=new FeedItem();
		$fis=$fi->getAll("companyid=".$feeditem->companyid." and link=\"".$feeditem->link."\" and status=1");
		if (is_null($fis)){
			return true;
		} else {
			return false;
		}
	}
	
}

?>
