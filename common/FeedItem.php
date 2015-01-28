<?php

class FeedItem extends DBManager {
	public $id;
	public $companyid;
	public $title;
	public $link;
	public $description;
	public $pubdate;
	public $createdat;
	public $status;//0-just downloaded/draft; 1- new; 2-read; 
	public $contor;
	function getTableName(){
		return "feeditem";
	}
	public function isNew($feeditem){
		$fi=new FeedItem();
		$fis=$fi->getAll("companyid=".$feeditem->companyid." and link=\"".$feeditem->link."\" and status in (1,2)");
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
	//mark all latest news as read
	public function markAsReadLatestNews(){
		$now=new DateTime();
		DBManager::doSql("update feeditem set status=2 where status=1 and createdat>='".$now->format('Y-m-d')."'");		
	}	
	
	
}

?>
