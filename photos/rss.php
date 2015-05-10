<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class RssImagesWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		$this->show();		
	}
	function show($html=""){

		WebPage::show($this->getRss());
	}
	function getRss(){
		$sql = "select id, title, file, lat, lng, data from photos order by data desc limit 0,20";
		$p=new Photo();
		$ps=$p->doSql($sql);
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<rss version="2.0" xmlns:georss="http://www.georss.org/georss">';
		$out.='<channel>';
		$out.='<title>Foto "Imobil Moldova"</title>';
		$out.='<link>'.Config::$imagessite.'</link>';
		$out.='<description>Serviciu de colectare imagini.</description>';
		$out.='<language>ro</language>';

		foreach($ps as $p){

			$title = $p->title;
			//$link = Config::$imagessite."/index.php?id=".$p->id;
			$link = $this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/index.php','action=viewimage&id='.$p->id);
			$description = $p->title;
			$pubDate = date("r", strtotime($p->data));
			$image_file = Config::$imagessite."/files/t".$p->file;   
			$image_description = $p->title;

			$title = htmlspecialchars($title);
			$link = htmlspecialchars($link);
			$description = htmlspecialchars($description);
			$image_file = htmlspecialchars($image_file); 
			$image_description = htmlspecialchars($image_description);


			$out.='<item>';
			$out.='<title>'.$title.'</title>';
			$out.='<link>'.$link.'</link>';
			$out.='<description><![CDATA[<p><img align="left" src="'.$image_file.'" border="1" hspace="5" alt="'.$image_description.'">'.$description.'</p>]]></description>';
			//$out.='<enclosure url="'.$image_file.'"/>';
			$out.='<pubDate>'.$pubDate.'</pubDate>';
			if ($p->lat!=0){
				$out.='<georss:point>'.$p->lat.' '.$p->lng.'</georss:point>';
			}
			$out.='</item>';			
		}
		$out.='</channel>';
		$out.='</rss>';
		return $out;
	}
}
$n=new RssImagesWebPage();

?>
