<?php
require_once(__DIR__ . '/../main/loader.php');

class IndexWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setCSS(Config::$mainsite."/style/main.css");
		$t="PORTAL IMOBILIAR DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->show();		
	}
	function show($html="IndexWebPageHTML"){
		$out='<div id="container" class="container">';
		$out.='<div id="group1" class="maingroupbox">';
		$out.='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Anunturi Oferte, Cereri Imobiliare</h2>';		
		$out.='</div>';
		$out.='<div id="left" style="width:168px;float:left;">';
		$out.=$this->getImobilCount();	
		$out.=$this->getImobilAdd();				
		$out.='</div>';			
		$out.='<div id="center" style="width:824px;float:right;">';
		$out.=$this->getLastImobil();
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		$out.='<div id="group2" class="maingroupbox">';
		$out.='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Anunturi Oferte, Cereri Chirii</h2>';		
		$out.='</div>';
		$out.='<div id="left" style="width:168px;float:left;">';
		$out.=$this->getChirieCount();	
		$out.=$this->getChirieAdd();					
		$out.='</div>';			
		$out.='<div id="center" style="width:824px;float:right;">';
		$out.=$this->getLastChirie();
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';

		$out.='<div id="group3" class="maingroupbox">';
		$out.='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Ultimile Titluri de Stiri </h2>';		
		$out.='</div>';
		$out.='<div id="left" style="width:168px;float:left;">';
		$out.=$this->getLastFeedsCount();						
		$out.='</div>';			
		$out.='<div id="center" style="width:824px;float:right;"">';
		$out.=$this->getLastFeedsTitles();
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';			
		
		$out.='<div id="group3" class="maingroupbox">';
		$out.='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Stiri Imobiliare</h2>';		
		$out.='</div>';
		$out.='<div id="left" style="width:168px;float:left;">';
		$out.=$this->getNewsCount();						
		$out.='</div>';			
		$out.='<div id="center" style="width:824px;float:right;"">';
		$out.=$this->getLastNews();
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';	

		$out.='<div id="group3" class="maingroupbox">';
		$out.='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Fotografii, Imagini</h2>';		
		$out.='</div>';
		$out.='<div id="left" style="width:168px;float:left;">';
		$out.=$this->getImagesCount();						
		$out.='</div>';			
		$out.='<div id="center" style="width:824px;float:right;">';
		$out.=$this->getLastImages();
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';

		$out.='<div id="group3" class="maingroupbox">';
		$out.='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Comentarii:</h2>';
		$out.='</div>';
		$out.='<div id="left" style="width:168px;float:left;">';
		$out.=$this->getCommentsCount();
		
		$out.='</div>';
		$out.='<div id="center" style="width:824px;float:right;">';
		$out.=$this->getGroupBoxH3("Comentarii recente:",Comment::getAllComments());
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		$out.='</div>';
		
		MainWebPage::show($out);
	}

	function getLastFeedsTitles(){
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Titluri de Stiri</h2>';
		$out.='<table width="100%"><tr><td>';
		$f=new FeedItem();	
		$todayDate=date(FeedItem::$dateFormat);	
		$out.=$f->getNewsByDate($todayDate,25);
		$out.='</td></tr></table>';
		$out.='<h2 class="groupfooter_h2"><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$feedssite.'/index.php').'">mai multe stiri</a></h2>';		
		$out.='</div>';
		return $out;
	}	
	
	function getLastNews(){
		$out="";
		$sql="select news.id as news_id, title, left(news.text,850) as t, url, date, company.id as company_id, company.name as company_name, news_category.id as news_category_id, news_category.name_ro as news_category_name, contacturl,news.contor as news_contor from news ";
		$sql.=" inner join company on news.company_id=company.id ";
		$sql.=" inner join news_category on news.news_category=news_category.id ";
		$sql.=" inner join image on news.id=image.reftypeid and image.reftype='n' and image.imagemain=1 ";
		$sql.=" where news.valid=1 order by date desc limit 0,3";

		$n=new News();
		$ns=$n->doSql($sql);	
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Ultimile Stiri</h2>';
		$out.='<table width="100%"><tr><td>';		
		foreach($ns as $n){
		   		$out.='<h2 class="groupheader_h2"><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$newssite.'/index.php','action=viewnews&id='.$n->news_id).'" class="title" target="_self">'.$n->title.'</a></h2>';
		  		$out.='<table class="news_body"><tr><td style="padding-bottom:5px;">';
		  		$i=Image::getMainImageByRefType('n', $n->news_id);
		  		if ($i!=null){
		  			$out.='<table align="left" style1="margin-right:6px;"><tr><td class1="imageframe"><a href="'.Config::$newssite.'/index.php?id='.$n->news_id.'" class1="imga"><img src="'.Config::$newssite.'/data/t'.$i->imagepath.'" alt="'.$i->imagenote.'" style1="hight:100px; width:150px;border: 1px;border-color: back;border-style: solid;" class="imageborder"></img></a><p class="news_image_tag"><a href="'.System::getURLAmpReplace($i->imageurl).'" target="_blank">'.System::getDomainFromURL($i->imageurl).'</a></p></td></tr></table>';
		  		}
		  		$out.='<span id="spantext">'.$n->t.'</span> ... <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$newssite.'/index.php','action=viewnews&id='.$n->news_id).'" target="_self">mai mult</a></td></tr>';
		  		$out.='</table>';
		}	
		$out.='</td></tr></table>';
		$out.='<h2 class="groupfooter_h2"><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$newssite.'/index.php').'">mai multe stiri</a></h2>';		
		$out.='</div>';	
		return $out;
	}	
	function getLastImobil(){
		$p=new Property();
		$rs=$p->get3ImobilWithImages();
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Ultimile Anunturi</h2>';
		$out.='<table width="100%"><tr><td>';		
		foreach($rs as $n){
		   		$out.='<h2 class="groupheader_h2"><a href="'.Config::$imobilsite.'/property.php?id='.$n->id.'" class="title" target="_self">'.$n->getShortDescription().'</a></h2>';
		  		$out.='<table style="width:100%"><tr><td style="padding-bottom:5px;">';
		  		$i=Image::getMainImageByRefType('i', $n->id);
		  		if ($i!=null){
		  			$out.='<table align="left" style1="margin-right:5px;"><tr><td style1="vertical-align: top;border: 1px;border-color: red;border-style: solid;margin-right:5px;"><img src="'.Config::$imobilsite.'/data/t'.$i->imagepath.'" alt="'.$i->imagenote.'" style1="height: 100px; width:130px;"  class="imageborder"></img></td></tr></table>';
		  		} else {
		  			$out.='<table align="left" style1="margin-right:5px;"><tr><td style1="vertical-align: top;border: 1px;border-color: red;border-style: solid;margin-right:5px;"><img src="'.Config::$commonsite.'/img/no_image_100x100.jpg" alt="no image" style1="height: 100px; width:130px;" class="imageborder"></img></td></tr></table>';
		  		}
		  		$out.=$n->getLongDescription().' <a href='.Config::$imobilsite.'/property.php?id='.$n->id.'>vezi mai mult</a></td></tr>';
		  		$out.='</table>';
		}													
		$out.='</td></tr></table>';
		$out.='<h2 class="groupfooter_h2"><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imobilsite.'/index.php').'">mai multe imobile</a></h2>';
		$out.='</div>';	
		return $out;			
	}
	function getLastChirie(){
		$p=new Property();
		$rs=$p->get3ChirieWithImages();
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Ultimile Anunturi</h2>';
		$out.='<table width="100%"><tr><td>';		
		foreach($rs as $n){
		   		$out.='<h2 class="groupheader_h2"><a href="'.Config::$chiriesite.'/property.php?id='.$n->id.'" class="title" target="_self">'.$n->getShortDescription().'</a></h2>';
		  		$out.='<table style="width:100%"><tr><td style="padding-bottom:5px;">';
		  		$i=Image::getMainImageByRefType('c', $n->id);
		  		if ($i!=null){
		  			$out.='<table align="left" style1="margin-right:5px;"><tr><td style1="vertical-align: top;border: 1px;border-color: red;border-style: solid;margin-right:5px;"><img src="'.Config::$chiriesite.'/data/t'.$i->imagepath.'" alt="'.$i->imagenote.'" style1="height: 100px; width:130px;" class="imageborder"></img></td></tr></table>';
		  		} else {
		  			$out.='<table align="left" style1="margin-right:5px;"><tr><td style1="vertical-align: top;border: 1px;border-color: red;border-style: solid;margin-right:5px;"><img src="'.Config::$commonsite.'/img/no_image_100x100.jpg" alt="no image" style1="height: 100px; width:130px;" class="imageborder"></img></td></tr></table>';
		  		}
		  		$out.=$n->getLongDescription().' <a href='.Config::$chiriesite.'/property.php?id='.$n->id.'>vezi mai mult</a></td></tr>';
		  		$out.='</table>';
		}													
		$out.='</td></tr></table>';
		$out.='<h2 class="groupfooter_h2"><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$chiriesite.'/index.php').'">mai multe chirii</a></h2>';
		$out.='</div>';	
		return $out;			
	}

	function getLastImages(){
		$sql="select id, title, file from photos where deleted=0 order by data desc";
		$sql.=" limit 0,8";
		$p=new Photo();
		$ps=$p->doSql($sql);
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Ultimile Imagini</h2>';
		$out.='<table width="100%">';
		$i=0;
		foreach($ps as $p){
			if ($i==0){
				$out.="<tr>";
			}
			if ($i==4){
				$out.="</tr><tr>";
			}
		  	$out.='<td align="center" style="padding:10px;vertical-align:top;">';
	  		$out.='<div><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/index.php','action=viewimage&id='.$p->id).'"><img src="'.Config::$imagessite.'/files/t'.$p->file.'" alt="'.$p->title.'" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"></img><p style="font-size:80%;">'.$p->title.'</p></a></div>';	  		
	  		$out.='</td>';
			if ($i==8){
				$out.="</tr>";
			}
			$i=$i+1;	  		
		}
		$out.="</tr></table>";
		$out.='<h2 class="groupfooter_h2"><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/index.php').'">mai multe imagini</a></h2>';		
		$out.="</div>";
		return $out;
	}
	function getLastCompanies(){
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Ultimile Companii Imobiliare</h2>';												
		$out.='<h2 class="groupfooter_h2"><a href="'.Config::$companiesite.'">mai multe companii</a></h2>';
		$out.='</div>';
		return $out;				
	}	
	function getImobilCount(){

		$p=new Property();
		$ps=$p->doSql("select count(*) as cnt from imobil where scop_id in (1,3) and deleted=0 and status=0");
		
		$c=0;
		foreach ($ps as $p){
			$c=$p->cnt;
		}

		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Total anunturi:</h2>';												
		$out.='<ul class="leftmenulist">';		
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Imobil</a></li>';
		$out.='<li style="font-size:200%;text-align:center;">'.$c.'</li>';		
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}
	function getImobilAdd(){
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Adauga Anunt:</h2>';												
		$out.='<ul class="leftmenulist">';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imobilsite.'/add.php').'">Adauga Oferta Imobil</a></li>';
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Chirie</a></li>';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imobilsite.'/add.php','scop_id=3').'">Adauga Cerere Imobil</a></li>';
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Cerere Chirie</a></li>';				
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}	
	function getChirieCount(){

		$p=new Property();
		$ps=$p->doSql("select count(*) as cnt from imobil where scop_id in (2,4) and deleted=0 and status=0");
		
		$c=0;
		foreach ($ps as $p){
			$c=$p->cnt;
		}

		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Total anunturi:</h2>';												
		$out.='<ul class="leftmenulist">';		
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Imobil</a></li>';
		$out.='<li style="font-size:200%;text-align:center;">'.$c.'</li>';		
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}
	function getChirieAdd(){
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Adauga Anunt:</h2>';												
		$out.='<ul class="leftmenulist">';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$chiriesite.'/add.php','scop_id=2').'">Adauga Oferta Chirie</a></li>';
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Chirie</a></li>';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$chiriesite.'/add.php','scop_id=4').'">Adauga Cerere Chirie</a></li>';
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Cerere Chirie</a></li>';				
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}
	function getLastFeedsCount(){
		$f=new FeedItem();
		$fs=$f->doSql("select count(*) as cnt from feeditem where deleted=0 and status=2");
		
		$c=0;
		foreach ($fs as $f){
			$c=$f->cnt;
		}

		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Total Titluri:</h2>';												
		$out.='<ul class="leftmenulist">';		
		$out.='<li style="font-size:200%;text-align:center;">'.$c.'</li>';		
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;
	}	
	function getNewsCount(){

		$n=new News();
		$ns=$n->doSql("select count(*) as cnt from news where deleted=0 and valid=1");
		
		$c=0;
		foreach ($ns as $n){
			$c=$n->cnt;
		}

		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Total Stiri:</h2>';												
		$out.='<ul class="leftmenulist">';		
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Imobil</a></li>';
		$out.='<li style="font-size:200%;text-align:center;">'.$c.'</li>';		
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}
	function getNewsAdd(){
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Adauga Anunt:</h2>';												
		$out.='<ul class="leftmenulist">';		
		$out.='<li><a href="'.Config::$imobilsite.'/property.php">Adauga Oferta Imobil</a></li>';
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Chirie</a></li>';		
		$out.='<li><a href="'.Config::$imobilsite.'/property.php">Adauga Cerere Imobil</a></li>';
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Cerere Chirie</a></li>';				
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}
	function getImagesCount(){

		$i=new Image();
		$is=$i->doSql("select count(*) as cnt from photos where deleted=0");
		
		$c=0;
		foreach ($is as $i){
			$c=$i->cnt;
		}

		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Total Imagini:</h2>';												
		$out.='<ul class="leftmenulist">';		
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Imobil</a></li>';
		$out.='<li style="font-size:200%;text-align:center;">'.$c.'</li>';		
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}
	function getImagesAdd(){
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Adauga Anunt:</h2>';												
		$out.='<ul class="leftmenulist">';		
		$out.='<li><a href="'.Config::$imobilsite.'/property.php">Adauga Oferta Imobil</a></li>';
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Chirie</a></li>';		
		$out.='<li><a href="'.Config::$imobilsite.'/property.php">Adauga Cerere Imobil</a></li>';
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Cerere Chirie</a></li>';				
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}																								
	function getCompaniesCount(){
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Total Companii:</h2>';												
		$out.='<ul class="leftmenulist">';		
		$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Imobil</a></li>';
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}
	function getCompaniesAdd(){
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Adauga Companie:</h2>';												
		$out.='<ul class="leftmenulist">';		
		$out.='<li><a href="'.Config::$companiesite.'">Adauga Companie</a></li>';
		$out.='</ul>';		
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;			
	}
	function getCommentsCount(){
	
		$i=new Image();
		$is=$i->doSql("select count(*) as cnt from comment where deleted=0");
	
		$c=0;
		foreach ($is as $i){
			$c=$i->cnt;
		}
	
		$out='<div class="groupbox">';
		$out.='<h2 class="groupheader_h2">Total Comentarii:</h2>';
		$out.='<ul class="leftmenulist">';
		//$out.='<li><a href="'.Config::$companiesite.'">Adauga Oferta Imobil</a></li>';
		$out.='<li style="font-size:200%;text-align:center;">'.$c.'</li>';
		$out.='</ul>';
		$out.='<h2 class="groupfooter_h2">...</h2>';
		$out.='</div>';
		return $out;
	}	
}
$n=new IndexWebPage();
?>