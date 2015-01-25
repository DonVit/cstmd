<?php
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
		
		FeedItem::itentifyNewItems();
		$out.="Feeds counted=".$feedscount;
		
		WebPage::show($out);
	}
}
$n=new IndexWebPage();
?>
