<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class RssImobilWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){

		WebPage::show($this->getRss());
	}
	function getRss(){
		$p=new Property();
		$ps=$p->getAll("scop_id in (1,3)","id desc","0","10");
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<rss version="2.0" xmlns:georss="http://www.georss.org/georss">';
		$out.='<channel>';
		$out.='<title>Anunturi Imobiliare din Republica Moldova</title>';
		$out.='<link>'.Config::$imobilsite.'</link>';
		$out.='<description>Serviciu de colectare Anunturi Imobiliare.</description>';
		$out.='<language>ro</language>';

		foreach($ps as $p){

			$title = $p->getShortDescription();
			$link = Config::$imobilsite.'/property.php?id='.$p->id;
			$description = $p->getLongDescription();
			$pubDate = date('r', strtotime($p->data));
			$image_file = Config::$mainsite.'/common/img/no_image_100x100.jpg';
			$image_description = 'no image';
			if ($p->getMainImage()!=null){
				$image_file = Config::$imobilsite.'/data/t'.$p->getMainImage()->imagepath;
				$image_description = $p->getMainImage()->imagenote;
			}   

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
$n=new RssImobilWebPage();

?>
