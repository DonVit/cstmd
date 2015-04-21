<?php
class FeedJob extends DBManager {
	public $id;
	public $started_at;
	public $ended_at;
	function getTableName(){
		return "feedjob";
	}
	function getRssFeeds(){
		$c=new Company();
		$fs=$c->getAll('rssfeed!=""',"id desc");
		foreach($fs as $f){
			$fl=new FeedLog();
			$fl->feedjobid=$this->id;
			$fl->companyid=$f->id;
			$fl->started_at=System::getCurentDateTime();
			$fl->save();		
			$fl->getFeed($f);
			$fi= new FeedItem();
			$fi->itentifyNewItems();
			$fi->mapNewsToLocations();	
		}			
	}	

}

?>
