<?php
require_once(__DIR__ . '/../main/loader.php');
 
class RssNewsWebPage extends WebPage {
	function __construct(){
		$this->setContentType("text/xml");
		parent::__construct();
		

		$this->show();		
	}
	function show($html=""){

		WebPage::show($this->getRss());
	}
	function getRss(){
		$sql = "select news.id, title, left(news.text,500) as text, url, date, name,company.contacturl as website, news.lat,news.lng from news inner join company on news. company_id=company.id and news.valid=1 order by date desc limit 0,20";
		$n=new News();
		$ns=$n->doSql($sql);
		
		$out='<?xml version="1.0" encoding="utf-8"?>';
		$out.='<rss version="2.0" xmlns:georss="http://www.georss.org/georss">';
		$out.='<channel>';
		$out.='<title>Ştiri "Imobil Moldova"</title>';
		$out.='<link>'.Config::$newssite.'</link>';
		$out.='<description>Serviciu de colectare ştiri.</description>';
		$out.='<language>ro</language>';

		foreach($ns as $n){

			$title = $n->title;
			//$link = Config::$newssite."/index.php?id=".$n->id;
			//$link = $this->getUrlWithSpecialCharsConverted(Config::$newssite.'/index.php','action=viewnews&id='.$n->id);
			$link = htmlspecialchars($this->getUrlWithSpecialCharsConverted(Config::$newssite.'/index.php','action=viewnews&id='.$n->id));
			$description = $n->title;
			$pubDate = date("r", strtotime($n->date));
			//$image_file = Config::$newssite.'/images/'.$n->image_file;   
			//$image_description = $n->title;
			$image_file = Config::$commonsite.'/img/no_image_100x100.jpg';
			$image_description = 'no image';
			
			$i=Image::getMainImageByRefType('n', $n->id);
			if ($i!=null){
				$image_file = Config::$newssite."/data/t".$i->imagepath;
				$image_description = $i->imagenote;
			}	
			$source_website=$n->website;
			$source_name=$n->name;

			$title = htmlspecialchars($title);
			$link = htmlspecialchars($link);
			$description = htmlspecialchars($description);
			$image_file = htmlspecialchars($image_file); 
			$image_description = htmlspecialchars($image_description);
			$source_website=htmlspecialchars($source_website); 
			$source_name=htmlspecialchars($source_name); 

			$out.='<item>';
			$out.='<title>'.$title.'</title>';
			$out.='<link>'.$link.'</link>';
			$out.='<description><![CDATA[<p><img align="left" src="'.$image_file.'" border="1" hspace="5" alt="'.$image_description.'">'.$description.'</p>]]></description>';
			//$out.='<enclosure url="'.$image_file.'"/>';
			$out.='<pubDate>'.$pubDate.'</pubDate>';
			$out.='<source url="'.$source_website.'">'.$source_name.'</source>';
			if ($n->lat!=0){
				$out.='<georss:point>'.$n->lat.' '.$n->lng.'</georss:point>';
			}			
			$out.='</item>';	
				
		}
		$out.='</channel>';
		$out.='</rss>';
		return $out;
	}
}
$n=new RssNewsWebPage();

?>
