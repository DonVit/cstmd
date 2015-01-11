<?php

class Rss {
	public $id;
	public $url;
	public $title;
	public $link;
	public $description;
	public $language;
	public function loadRss(){
		$doc  = new DOMDocument("1.0");
		$doc->load($this->url);
		$channels = $doc->getElementsByTagName('channel');
		$channel=$channels->item(0);		
		
		$this->title=getTagValue($channel,'title');
		$this->link=getTagValue($channel,'link');
		$this->description=getTagValue($channel,'description');
		$this->language=getTagValue($channel,'language');

		$items = $channel->getElementsByTagName('item');		
		foreach($items as $item){
			$ri=new RssItem($item);
			$ri->rssid=$this->id;
			//print_r($ri);
		}  
	}
}
class RssItem {
	public $id;
	public $rssid;
	public $title;
	public $link;
	public $description;
	public $pubDate;
	public function RssItem($item){
		foreach ($this as $name => $value) {
			$this->$name=getTagValue($item,$name);
		}
	}
}
function getTagValue($element,$tag){
		$value='';
		$tags=$element->getElementsByTagName($tag);
		if ($tags->length!=0){
			$tag=$tags->item(0);
			$value=$tag->firstChild->textContent;
		}
		return $value;	
}	


$r=new Rss();
$r->url='http://cadastru.md/rss/';
$r->loadRss();
print_r($r);

?>