<?php
class FeedLog extends DBManager {
	public $id;
	public $feedjobid;
	public $companyid;
	public $downloadstatus;
	public $parsestatus;
	public $error;
	public $started_at;
	public $ended_at;	
	function getTableName(){
		return "feedlog";
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
						$fi->feedlogid=$fl->id;
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
			
			
			$this->downloadstatus=$feeddownloadstatus;
			$this->parsestatus=$feedparsestatus;
			$this->error=$errormessage;
			$this->ended_at=System::getCurentDateTime();
			$this->save();		
		return (($feeddownloadstatus==1)&&($feedparsestatus==1));
	}
	function getItem($node, $nodename){
		$rtn="";
		$items=$node->getElementsByTagName($nodename);
		if ($items->length!=0){
			$rtn=$items->item(0)->childNodes->item(0)->nodeValue;
		}		
		return $rtn;
	}		
}

?>
