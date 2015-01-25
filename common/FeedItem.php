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
	public function isNew($feeditem){
		$fi=new FeedItem();
		$fis=$fi->getAll("companyid=".$feeditem->companyid." and link=\"".$feeditem->link."\" and status=1");
		if (is_null($fis)){
			return true;
		} else {
			return false;
		}
	}
	public function itentifyNewItems(){
		$fi=new FeedItem();
		
		$fis=$fi->getAll("status=0");
		
		if (count($fis)!=0){
			foreach($fis as $fi){
				if ($this->isNew($fi)){
					$fi->status=1;
					$fi->save();
				} else {
					$fi->delete();
				}
			}
		}
		DBManager::doSql("delete from feeditem where deleted=1");		
	}
	
}

?>
