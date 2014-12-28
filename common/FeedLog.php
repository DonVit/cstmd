<?php
/*
 * Created on 27 Feb 2009
 *
 */
class FeedLog extends DBManager {
	public $id;
	public $companyid;
	public $downloadstatus;
	public $parsestatus;
	public $error;
	public $attemptat;
	function getTableName(){
		return "feedlog";
	}	
}

?>
