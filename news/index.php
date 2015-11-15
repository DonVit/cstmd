<?php
require_once(__DIR__ . '/../main/loader.php');
 
class NewsWebPage extends MainWebPage {
	public $page=0;
	public $rowsperpage=10;
	public $categ=0;
	public $raion=0;
	public $localitate=0;
	public $sursa=0;
	function __construct(){
		parent::__construct();
		$t="ŞTIRI IMOBILIARE DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);		
		$this->create();		
	}
	function actionDefault(){
		if (isset($this->id)){
			$this->redirect($this->getUrl(Config::$newssite.'/index.php','action=viewnews&id='.$this->id));
		}
		
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Categorie:",$this->getNewsCategories()));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Localităţi:",$this->getRaions($this->raion)));				
		//$this->setCenterContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageRaioaneTitle"),$this->getMain()));
		$this->setCenterContainer($this->getNews());	
		$this->setRightContainer($this->getGroupBoxH3("Stiri in format RSS:",$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("Filtru pe Surse de Stiri:",$this->getNewsCompanies()));		
		$this->show();
	}	
	function actionViewCategorie(){
		$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Categorie:",$this->getNewsCategories($this->categ)));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Localităţi:",$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getNews());
		$this->setRightContainer($this->getGroupBoxH3("Stiri in format RSS:",$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("Filtru pe Surse de Stiri:",$this->getNewsCompanies()));		
		$this->show();
	}
	function actionViewRaion(){
		$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Categorie:",$this->getNewsCategories($this->categ)));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Localităţi:",$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getNews());
		$this->setRightContainer($this->getGroupBoxH3("Stiri in format RSS:",$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("Filtru pe Surse de Stiri:",$this->getNewsCompanies()));		
		$this->show();
	}
	function actionViewLocalitate(){
		$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Categorie:",$this->getNewsCategories($this->categ)));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Localităţi:",$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getNews());
		$this->setRightContainer($this->getGroupBoxH3("Stiri in format RSS:",$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("Filtru pe Surse de Stiri:",$this->getNewsCompanies()));		
		$this->show();
	}	
	function actionViewSursa(){
		$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Categorie:",$this->getNewsCategories($this->categ)));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Localităţi:",$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getNews());
		$this->setRightContainer($this->getGroupBoxH3("Stiri in format RSS:",$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("Filtru pe Surse de Stiri:",$this->getNewsCompanies($this->sursa)));		
		$this->show();
	}			
	function actionViewNews(){
		
		$n=new News();
		//$n->loadById($this->id);
		if ($n->loadById($this->id)){
			$n->count();
		}
		$i=new Image();
		$is=$i->getAll("reftype='n' and reftypeid=".$n->id);

		$fs=User::getCurrentFiles();
		for ($i = 0; $i <= 4; $i++) {
			if (isset($is[$i])){
				$fs[$i]=$is[$i];
				$fs[$i]->error=0;
			}
		}
		User::setCurrentFiles($fs);				
		//$n->contor=$n->contor+1;
		//$n->save();

		//$this->setBodyTag('<body onload="SmallViewOnMapLoad()" onunload="GUnload()">');
		//$this->setBodyTag('<body onload="MapViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));

		$this->setTitle("Ştiri \"Imobil Moldova\": ".$n->title);
		$this->setDescription($n->description);
		$this->setKeywords($n->keywords);
		//$this->setLogoTitle
		//$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Categorie:",$this->getNewsCategories($this->categ)));
		$this->setLeftContainer($this->getGroupBoxH3("Filtru pe Localităţi:",$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getGroupBoxH1($n->title,$this->getNewsById($n)));
		$this->setCenterContainer($this->getGroupBoxH3("",$this->getNewsDetails($n)));
		$this->setCenterContainer($this->getLocalitatiByNews($n));	
		$this->setCenterContainer($this->getVideos($n));
		if ($n->lat!=0){
			$this->setCenterContainer($this->getGroupBoxH3("Harta: ".$n->map_title,$this->getMap($n)));
		}
		$this->setCenterContainer($this->getGroupBoxH3("Imagini:",$this->getFiles()));		
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii:",Comment::getComments($this,'n',$n->id)));
		$this->setCenterContainer($this->getGroupBoxH3("Stiri din aceasta categorie:",$n->getNewsByCategory()));		
		$this->setRightContainer($this->getGroupBoxH3("Stiri in format RSS:",$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("Filtru pe Surse de Stiri:",$this->getNewsCompanies()));		
		$this->show();
		//delete property from cache
		User::delCurrentFiles();					
		
	}
	function show($out=''){
		$out="";
		$out.='<div class="container">';
		$out.='<div class="row">';
		$out.='<div id="left" class="col-xs-3 col-md-3 col-lg-3">';
		$out.=$this->getLeftContainer();
		$out.='</div>';
		$out.='<div id="center" class="col-xs-6 col-md-6 col-lg-6">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right" class="col-xs-3 col-md-3 col-lg-3">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='</div>';
		$out.='</div>';
		MainWebPage::show($out);
	}	
	function show1(){		
		$out='<div id="container">';
		$out.='<div id="left" class="container left" style="width:198px;">';
		$out.=$this->getLeftContainer();
		$out.='</div>';		
		$out.='<div id="center" class="container center" style="width:600px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right" class="container right" style="width:198px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}		
	function _show1($html=""){
		$out='<div id="container">';
		$out.='<div id="left">';
		$out.=$this->getLeftContainer();
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right">';
		$out.=$this->getRightContainer();		
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		//LocationFilterWebPage::show($out);
		MainWebPage::show($out);
	}	
	function _getMain(){
		$out="";
		//if (isset($this->id)){
		//	$out.=$this->getNewsById($this->id);	
		//	$out.=$this->getLocalitatiByNews($this->id);
		//	$out.=Comment::getComments($this,'n',$this->id);
		//	$out.=$this->getNewsByNewsCategory($this->id);
		//} else {
			$out.=$this->getNews($this->page,$this->rowsperpage,$this->categ,$this->raion,$this->localitate,$this->sursa);
		//}		
		return $out;
	}
	function getRightContainer1(){
		$out="";
		$out.=$this->getNewsCompanies();
		$out.=$this->getRssLink();		
		return $out;				
	}		
	function getNewsCategories($categid=0){
		$nc=new NewsCategory();
		$ncs=$nc->getAll();
		//$out="<div class=\"groupbox\">";
		//$out.="<h2 class=\"localitatigroup_h2\">Filtru pe Categorie:</h2>";			
		$out='<ul>';
		foreach ($ncs as $nc){
			if ($categid==$nc->id){
				$out.='<li><a style="border-bottom: 2px solid rgb(194, 0, 0);" href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcategorie&categ='.$nc->id).'" title="'.$nc->getFieldValueByName("description").'">'.$nc->getFieldValueByName("name").'</a></li>';
			} else {
				$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcategorie&categ='.$nc->id).'" title="'.$nc->getFieldValueByName("description").'">'.$nc->getFieldValueByName("name").'</a></li>';
			}
		}
		$out.="</ul>";
		//$out.="</div>";
		return $out;
	}
	function getNewsCompanies($sursa=0){
		$nc=new NewsCategory;
		$sql="SELECT company_id as id, (select name from company where id=company_id) as name, (select contacturl from company where id=company_id) as website, count(*) as cnt FROM `news` group by company_id  order by cnt desc";
		//$ncs=$nc->getAll();
		$ncs=$nc->doSql($sql);
		//$out="<div class=\"groupbox\">";
		//$out.="<h2 class=\"localitatigroup_h2\">Surse de Ştiri:</h2>";
		$out='<ul>';
		foreach ($ncs as $nc){
			//$out.="<li><a href=\"".Config::$companiesite."/index.php?id=".$nc->id."\" title=\"".$nc->website."\">".$nc->name."</a></li>";
			//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php').'action=viewsursa&sursa='.$nc->id.'" title="'.$nc->website.'">'.$nc->name.'</a></li>';
			if ($sursa==$nc->id){
				//$out.='<li><a style="border-bottom: 2px solid rgb(194, 0, 0);" href="'.$this->getUrlWithSpecialCharsConverted("index.php").'action=viewsursa&sursa='.$nc->id.'" title="'.$nc->website.'">'.$nc->name.'</a></li>';
			} else {
				$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewsursa&sursa='.$nc->id).'" title="'.$nc->website.'">'.$nc->name.'</a></li>';
			}						
		}
		$out.='</ul>';
		//$out.="</div>";
		return $out;
	}	
	function getRaions($raionid=0){
		$r=new Raion;
		$rs=$r->getAll("","`municipiu` desc,`order`,`name`");
		//$out="<div class=\"groupbox\">";
		//$out.="<h2 class=\"localitatigroup_h2\">Filtru pe Localităţi:</h2>";		
		$out='<ul>';
		foreach ($rs as $r){
			if ($raionid==$r->id){
				$out.='<li><a style="border-bottom: 2px solid rgb(194, 0, 0);" href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewraion&raion='.$r->id).'" title="'.$r->getFullNameDescription().'">'.$r->getFullName().'</a></li>';
			} else {
				$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewraion&raion='.$r->id).'" title="'.$r->getFullNameDescription().'">'.$r->getFullName().'</a></li>';
			}
		}
		$out.='</ul>';
		//$out.="</div>";
		return $out;
	}		
	function getRssLink(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="rss.php" title="Ştiri în format RSS">Ştiri în format RSS <img src="'.Config::$commonsite.'/img/rss.png" alt="Companii in format RSS" title="Comapnii in format RSS"/></a></li>';
		$out.='</ul>';
		return $out;
	}
	function getNews(){
		$out="";
		$sql="select news.id as news_id, title, left(news.text,850) as t, url, date, company.id as company_id, company.name as company_name, news_category.id as news_category_id, news_category.name_ro as news_category_name, company.contacturl as website,news.contor as news_contor from news ";
		$sql.=" inner join company on news.company_id=company.id ";
		$sql.=" inner join news_category on news.news_category=news_category.id ";
		$sql.=" where news.valid=1";
		$t="Ştiri \"Imobil Moldova\": ";
		if ($this->categ!=0){
			$sql.=" and news_category=".$this->categ;
			$nc=new NewsCategory();
			$nc->loadById($this->categ);
			$t.=$nc->description_ro;
			$this->setTitle($t);
			$out.="<div class=\"groupbox\">";
			$out.="<h2 class=\"news_title\" align=\"center\">Ştiri Imobiliare din categoria ".$nc->name_ro."</h2>";
			$out.="</div>";			
		}
		if ($this->raion!=0){
			$sql.=" and news.id in (select news_id from `news_localitate` inner join localitate on localitate.id=localitate_id where raion_id=".$this->raion.")";
			$r=new Raion();
			$r->loadById($this->raion);
			$t.=$r->getFullNameDescription();
			$this->setTitle($t);
			$out.="<div class=\"groupbox\">";
			$out.='<h2 class="news_title" align="center">Ştiri Imobiliare relevante la <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite.'/index.php','action=viewraion&id='.$r->id).'">'.$r->getFullNameDescription().'</h2>';
			$out.="</div>";
		}
		if ($this->localitate!=0){
			$sql.=" and news.id in (select news_id from `news_localitate` where localitate_id=".$this->localitate.")";
			$l=new Location();
			$l->loadById($this->localitate);
			$t.=$l->getFullNameDescription();
			$this->setTitle($t);
			$out.="<div class=\"groupbox\">";
			$out.='<h2 class="news_title" align="center">Ştiri Imobiliare relevante la <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite.'/index.php','action=viewlocalitate&id='.$l->id).'">'.$l->getFullNameDescription().'</h2>';
			$out.='</div>';						
		}
		if ($this->sursa!=0){
			$sql.=" and company_id=".$this->sursa;
			$c=new Company();
			$c->loadById($this->sursa);
			$t.=$c->name;
			$this->setTitle($t);
			$out.="<div class=\"groupbox\">";
			$out.='<h2 align="center">Ştiri Imobiliare relevante la <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/index.php','action=viewcompany&id='.$c->id).'" target="_self">'.$c->name.'</a></h2>';
			$out.="</div>";						
		}		
		$sql.=" order by date desc limit ".$this->page*$this->rowsperpage.",".$this->rowsperpage;
	
		$n=new News();
		$ns=$n->doSql($sql);	
		
		foreach($ns as $n){

		   		$s='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewnews&id='.$n->news_id).'" class="title" target="_self">'.$n->title.'</a>';
		  		$b='<table style="width:100%"><tr><td style="padding-bottom:5px;">';
		  		$i=Image::getMainImageByRefType('n', $n->news_id);
		  		if ($i!=null){
		  			$b.='<table align="left" style="margin-right:6px;"><tr><td><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewnews&id='.$n->news_id).'"><img src="data/t'.$i->imagepath.'" alt="'.$i->imagenote.'" style="hight:100px; width:150px;" class="imageborder"/></a><p class="news_image_tag"><a href="'.System::getURLAmpReplace($i->imageurl).'" target="_blank">'.System::getDomainFromURL($i->imageurl).'</a></p></td></tr></table>';
		  		}
		  		$b.='<span >'.$n->t.'</span> <a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewnews&id='.$n->news_id).'" target="_self">mai mult</a></td></tr>';
		  		$b.='<tr><td>'.$this->getGroupBoxH3('','<table style="font-size:85%;width:100%"><tr><td style="align:left;">Sursa: <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/index.php','action=viewcompany&id='.$n->company_id).'" target="_self">'.$n->company_name.'</a></td><td>Data: '.date("Y-m-d", strtotime($n->date)).'</td><td>Categorie:<a href="index.php?categ='.$n->news_category_id.'" target="_self">'.$n->news_category_name.'</a></td><td>Vizite:'.$n->news_contor.'</td><td style="align:right;"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewnews&id='.$n->news_id).'" target="_self">ştirea în cache</a></td></tr></table>').'</td></tr>';
		  		$b.='</table>';
		  		$out.=$this->getGroupBoxH2($s,$b);		  		
		}

		$sql="select count(*) as cnt from news where valid=1";
		if ($this->categ!=0){
			$sql.=" and news_category=".$this->categ;
			$url="categ=".$this->categ;
		}
		if ($this->raion!=0){
			$sql.=" and news.id in (select news_id from `news_localitate` inner join localitate on localitate.id=localitate_id where raion_id=".$this->raion.")";
			$url="raion=".$this->raion;
		}
		if ($this->localitate!=0){
			$sql.=" and news.id in (select news_id from news_localitate where localitate_id=".$this->localitate.")";
			$url="localitate=".$this->localitate;
		}
		$c=new News();
		$cs=$c->doSql($sql);
		
		$out.='<div class="groupbox">';
		$out.="<table width=\"100%\"><tr><td align=\"right\">";
		foreach($cs as $c){
			$cnt=$c->cnt;
		}
		if ($this->page!=0){
			$out.="<a href=\"index.php?page=".($this->page-1)."\" class=\"link_button\">< ştiri mai noi</a>";
		}
		$out.=" ";
		if ((($this->page+1)*$this->rowsperpage)<$cnt){
			$out.="<a href=\"index.php?page=".($this->page+1)."\" class=\"link_button\">ştiri mai vechi ></a>";
		}
		$out.="</td></tr></table>";
		$out.='</div>';
		return $out;
	}

	function getNewsById($n){
		$out='';
		$out.='<table><tr><td style="padding-bottom:5px;">';
		$i=Image::getMainImageByRefType('n', $n->id);
		if ($i!=null){
			$out.='<table style="margin-right:8px;align:left;"><tr><td class="imageborder"><a href="'.System::getURLAmpReplace($i->imageurl).'" class="imga"><img src="data/t'.$i->imagepath.'" alt="'.$i->imagenote.'" class="imageborder"/></a><p class="news_image_tag"><a href="'.System::getURLAmpReplace($i->imageurl).'" target="_blank">'.System::getDomainFromURL($i->imageurl).'</a></p></td></tr></table>';
		}
		$out.='<span id="spantext2">'.$n->text.'</span></td></tr>';
		$out.='</table>';				
		return $out;
	}	
	function getNewsDetails($n){
		$out='<table style="font-size:85%;width:100%"><tr><td style="align:left;">Sursa: <a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite.'/index.php','action=viewcompany&id='.$n->company_id).'" target="_self">'.$n->getCompany()->name.'</a></td><td>Data: '.date("Y-m-d", strtotime($n->date)).'</td><td>Categorie:<a href="index.php?categ='.$n->news_category.'" target="_self">'.$n->getNewsCategory()->name_ro.'</a></td><td>Vizite:'.$n->contor.'</td><td style="align:right;"><a href="redirect.php?id='.$n->id.'" target="_blank">ştirea originală</a></td></tr></table>';				
		return $out;
	}
	function getLocalitatiByNews($n){
		//$sql="SELECT raion.name as r_name, localitate.id as l_id, localitate.name_ro as l_name FROM `news_localitate` INNER JOIN localitate ON news_localitate.localitate_id = localitate.id INNER JOIN raion ON localitate.raion_id = raion.id WHERE news_id =$id";
		//$l=new Location;
		//$ls=$l->doSql($sql);	
		$ls=$n->getNewsLocalitati();
		$out="";
		if (count($ls)!=0){
			//$out.="<div class=\"groupbox\">";
			//$out.="<h2 class=\"localitatigroup_h2\">Localitaţi in ştire:</h2>";
			$out.="<table  style1=\"font-size:85%;width:100%\"><tr><td>";
			foreach($ls as $l){
				if ($l->r_name==$l->l_name){
						$out.='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewlocalitate&localitate='.$l->l_id).'" target="_self" title="'.$l->l_name.'">'.$l->l_name.';</a>  ';
				} else {
						$out.='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewlocalitate&localitate='.$l->l_id).'" target="_self" title="'.$l->r_name.'-'.$l->l_name.'">'.$l->r_name.'-'.$l->l_name.';</a>  ';		
				}
			}
			$out.="</td></tr></table>";
			$out=$this->getGroupBoxH3("Localitaţi in ştire:",$out);
		}
	
		return $out;
	}

	function getVideos($n){
		//$v=new Video();
		//$vs=$v->doSql('select * from `video` inner join news_video on video.id=news_video.video_id where news_video.news_id='.$newsid);
		$vs=$n->getNewsVideos();
		$out='';
		if (count($vs)!=0){
			//$out.='<div class="groupbox">';
			foreach ($vs as $v){
				$s='Video '.$v->title;
				$b='<table width="100%">';
				$b.='<tr><td align="center">'.$v->text.'</td></tr>';
				$b.='</table>';
				$out.=$this->getGroupBoxH3($s,$b);
			}
			//$out.="</div>";				
		}		
		return $out;
	}
}
$n=new NewsWebPage();

?>
