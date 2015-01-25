<?php
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
	
	function getAtemptsForToday(){
		$now=new DateTime();
		$current_date=$now->format('Y-m-d');
		return $this->getAll('attemptat>='.$current_date,"attemptat desc");
	}
	function getRssFeeds(){
		$f=new Company();
		$fs=$f->getAll('rssfeed!=""',"id desc");
		$feedscount=0;
		foreach($fs as $f){
			$status=$this->getFeed($f);
			$feedscount++;
		}			
	}	
	function getItem($node, $nodename){
		$rtn="";
		$items=$node->getElementsByTagName($nodename);
		if ($items->length!=0){
			$rtn=$items->item(0)->childNodes->item(0)->nodeValue;
		}		
		return $rtn;
	}
	function getFeed($f){

			$feeddownloadstatus=0;
			$feedparsestatus=0;
			$errormessage="";
			
			$ch = curl_init();
			
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
					
			curl_setopt($ch, CURLOPT_URL,$f->rssfeed);
			
			$xml_content = curl_exec ($ch);
				
			if ($xml_content){
				$feeddownloadstatus=1;
				$xmlDoc = new DOMDocument();
				if ($xmlDoc->loadXML(trim($xml_content))){
					$feedparsestatus=1;
					//get and output "<item>" elements
					$x=$xmlDoc->getElementsByTagName('item');
					foreach($x as $item){
						$item_title=$item->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
						$item_link=$item->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
						$item_desc=$this->getItem($item,'description');
						$item_date=$this->getItem($item,'pubDate');
						$fi=new FeedItem();
						$fi->companyid=$f->id;
						$fi->title=strip_tags($item_title);
						$fi->link=strip_tags($item_link);
						$fi->description=strip_tags($item_desc); 
						$fi->pubdate=strip_tags($item_date);
						$fi->createdat=System::getCurentDateTime();
						$fi->save();
					}
				}
			} else {
				$errormessage=curl_error($ch);
			}
			curl_close ($ch);
			
			$fl=new FeedLog();
			$fl->companyid=$f->id;
			$fl->downloadstatus=$feeddownloadstatus;
			$fl->parsestatus=$feedparsestatus;
			$fl->error=$errormessage;
			$fl->attemptat=System::getCurentDateTime();
			$fl->save();		
		return (($feeddownloadstatus==1)&&($feedparsestatus==1));
	}
}

?>
