<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class RssCompaniesWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){

		WebPage::show($this->getRss());
	}
	function getRss(){
		$sql = "select `id`,`name`,`created_date`, lat, lng FROM `company` where valid=1 order by `id` desc limit 0,20";
		$c=new Company();
		$cs=$c->doSql($sql);
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<rss version="2.0" xmlns:georss="http://www.georss.org/georss">';
		$out.='<channel>';
		$out.='<title>Companii "Imobil Moldova"</title>';
		$out.='<link>'.Config::$companiesite.'</link>';
		$out.='<description>Lista Companii.</description>';
		$out.='<language>ro</language>';

		foreach($cs as $c){

			$title = $c->name;
			//$link = Config::$companiesite."/index.php?id=".$c->id;
			$link = $this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/index.php','action=viewcompany&id='.$c->id);
			$description = $c->name;
			$pubDate = date("r", strtotime($c->created_date));
			//$image_file = Config::$companiesite."/files/t".$c->logo_file;
			$image_file = Config::$commonsite.'/img/no_image_100x100.jpg';
			$image_description = 'no image';
			
			$i=Image::getMainImageByRefType('f', $c->id);
			if ($i!=null){
				$image_file = Config::$companiesite."/data/t".$i->imagepath;
				$image_description = $i->imagenote;
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
			if ($c->lat!=0){
				$out.='<georss:point>'.$c->lat.' '.$c->lng.'</georss:point>';
			}			
			$out.='</item>';			
		}
		$out.='</channel>';
		$out.='</rss>';
		return $out;
	}
}
$n=new RssCompaniesWebPage();

?>
