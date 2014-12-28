<?php
/*
 * Created on 27 Feb 2009
 *
 */
class NewsLocation extends DBManager {
	public $id;
	public $news_id;
	public $localitate_id;	

	function getTableName(){
		return "news_localitate";
	}
}

?>
