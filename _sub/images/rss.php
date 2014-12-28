<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
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
		$sql = "select id, title, file, data from photos order by data desc limit 0,20";
		$p=new Photo();
		$ps=$p->doSql($sql);
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<rss version="2.0">';
		$out.='<channel>';
		$out.='<title>Foto "Imobil Moldova"</title>';
		$out.='<link>'.Config::$imagessite.'</link>';
		$out.='<description>Serviciu de colectare imagini.</description>';
		$out.='<language>ro</language>';

		foreach($ps as $p){

			$title = $p->title;
			$link = Config::$imagessite."/index.php?id=".$p->id;
			$description = $p->title;
			$pubDate = date("r", strtotime($p->data));
			$image_file = Config::$imagessite."/files/t".$p->file;   
			$image_description = $p->title;


			$out.='<item>';
			$out.='<title>'.$title.'</title>';
			$out.='<link>'.$link.'</link>';
			$out.='<description><![CDATA[<p><img align="left" src="'.$image_file.'" border="1" hspace="5" alt="'.$image_description.'">'.$description.'</p>]]></description>';
			//$out.='<enclosure url="'.$image_file.'"/>';
			$out.='<pubDate>'.$pubDate.'</pubDate>';
			$out.='</item>';			
		}
		$out.='</channel>';
		$out.='</rss>';
		return $out;
	}
}
$n=new RssImagesWebPage();

?>
