<?php
/*
 * Created on 25 Feb 2009
 *
 */
//ini_set("memory_limit","200M");
set_time_limit(10720);
require_once('loader.php');

class IndexWebPage extends WebPage {
	function __construct(){
		parent::__construct();
		$this->show();		
	}
	function show($html="IndexWebPageHTML"){

		$out="";
		$feedscount=0;
		
		$f=new Company();
		
		$fs=$f->getAll('rssfeed!=""',"id desc");
		
		
		
		foreach($fs as $f){
			
			sleep(1);
			
			$opts = array(
			  'http'=>array(
			    'method'=>"GET",
			    'header'=>"Accept-language: en\r\n"
			  )
			);			
			$context = stream_context_create($opts);
				

			//$xml_content = file_get_contents($f->rssfeed,false,$context);
			$feeddownloadstatus=0;
			$feedparsestatus=0;
			$errormessage="";
			//$xml_content = file_get_contents($f->rssfeed);
			
			$ch = curl_init();
			
			//curl_setopt($ch, CURLOPT_URL, $f->rssfeed);
			//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
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
						//$item_desc="";
						//$item_descs=$item->getElementsByTagName('description');
						//if ($item_descs->length!=0){
						//	$item_desc=$item_descs->item(0)->childNodes->item(0)->nodeValue;
						//}
						//$item_date=$item->getElementsByTagName('pubDate')->item(0)->childNodes->item(0)->nodeValue;
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
					
						//$out.="<p><a href='" . $item_link. "'>" . $item_title . "</a>";
						//$out.="<br>";
						//$out.=strip_tags($item_desc)."</p>";
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
			$feedscount++;
		}

		$out.="Feeds counted=".$feedscount;
		
		WebPage::show($out);
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
$n=new IndexWebPage();
?>
