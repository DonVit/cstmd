<?php
/*
 * Created on 25 Feb 2009
 *
 */
//ini_set("memory_limit","200M");
//set_time_limit(10720);
require_once('loader.php');

class IndexWebPage extends WebPage {
	function __construct(){
		parent::__construct();
		$this->show();		
	}
	function show($html="IndexWebPageHTML"){

		$out="";
		$feedscount=0;
		
		$fi=new FeedItem();
		
		$fis=$fi->getAll("status=0");
		
		if (count($fis)!=0){
			foreach($fis as $fi){
				if (FeedItem::isNew($fi)){
					$fi->status=1;
					$fi->save();
				} else {
					$fi->delete();
				}
			}
		}

		DBManager::doSql("delete from feeditem where deleted=1");
		$out.="Feeds counted=".$feedscount;
		
		WebPage::show($out);
	}
}
$n=new IndexWebPage();
?>
