<?php
class NewsLocation extends DBManager {
	public $id;
	public $news_id;
	public $localitate_id;	

	function getTableName(){
		return "news_localitate";
	}
}

?>
