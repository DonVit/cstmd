<?php
require_once('loader.php');
 
class FeedsWebPage extends MainWebPage {
	private $fi;
	
	function __construct(){
		parent::__construct();
		$this->setCSS(Config::$mainsite."/style/main.css");
		$t="TITLURI DE STIRI DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->fi=new FeedItem();
		$this->create();	
	}
	function actionDefault(){	
		$this->setLeftContainer($this->getGroupBoxH3("Utile:", $this->getMenuFeeds()));
		$this->setLeftContainer($this->getGroupBoxH3("Stiri pe Surse:", $this->fi->getTopCompanies()));		
		$todayDate=date("d-m-y");
		$title="Stiri de Azi";
		$this->setTitle($title);	
		$this->setCenterContainer($this->getGroupBoxH1($title,$this->fi->getNewsByDate($todayDate,2000)));
		$this->setRightContainer($this->getGroupBoxH3("Stire pe Localitati:",$this->getRaions(0)));
		$this->show();
	}
	function actionYesterday(){	
		$this->setLeftContainer($this->getGroupBoxH3("Utile:", $this->getMenuFeeds()));
		$this->setLeftContainer($this->getGroupBoxH3("Stiri pe Surse:", $this->fi->getTopCompanies()));
		$todayDate=date("d-m-y");
		$today=new DateTime();
		$yesterday=$today->sub(new DateInterval('P1D'));
		$title="Stiri de Ieri";
		$this->setTitle($title);	
		$this->setCenterContainer($this->getGroupBoxH1($title,$this->fi->getNewsByDate($yesterday->format('d-m-y'),2000)));
		$this->setRightContainer($this->getGroupBoxH3("Stire pe Localitati:",$this->getRaions(0)));
		$this->show();
	}	
	function actionCalendar(){
		$this->setLeftContainer($this->getGroupBoxH3("Utile:", $this->getMenuFeeds()));
		$this->setLeftContainer($this->getGroupBoxH3("Stiri pe Surse:", $this->fi->getTopCompanies()));
		$date=date("d-m-y",strtotime($this->date));	
		$title="Titluri de Stiri din data de ";
		$this->setTitle($title.$date);
		$this->setCenterContainer($this->getGroupBoxH1($title.$date,$this->fi->getNewsByDate($date),2000));
		$this->setRightContainer($this->getGroupBoxH3("Stire pe Localitati:",$this->getRaions(0)));		
		$this->show();
	}
	function actionCompany(){
		$this->setLeftContainer($this->getGroupBoxH3("Utile:", $this->getMenuFeeds()));
		$this->setLeftContainer($this->getGroupBoxH3("Stiri pe Surse:", $this->fi->getTopCompanies()));
		$c=new Company();
		$c->loadById($this->id);
		$title="Titluri de Stiri al ";
		$this->setTitle($title.$c->name);
		$this->setCenterContainer($this->getGroupBoxH1($title."\"".$c->name."\"",$this->fi->getNewsByCompany($c->id,2000)));
		$this->setRightContainer($this->getGroupBoxH3("Stire pe Localitati:",$this->getRaions(0)));		
		$this->show();
	}	
	function actionLocalitate(){
		$this->setLeftContainer($this->getGroupBoxH3("Utile:", $this->getMenuFeeds()));
		$this->setLeftContainer($this->getGroupBoxH3("Stiri pe Surse:", $this->fi->getTopCompanies()));
		$l=new Location();
		$l->loadById($this->id);
		$title="Titluri de Stiri din ";
		$this->setTitle($title.$l->getFullNameDescription());
		$this->setCenterContainer($this->getGroupBoxH1($title.$l->getFullNameDescription(),$this->fi->getNewsByLocalitate($l->id,2000)));
		$this->setRightContainer($this->getGroupBoxH3("Stire pe Localitati:",$this->getLocations($l->id)));		
		$this->show();
	}
	function actionRaion(){
		$this->setLeftContainer($this->getGroupBoxH3("Utile:", $this->getMenuFeeds()));
		$this->setLeftContainer($this->getGroupBoxH3("Stiri pe Surse:", $this->fi->getTopCompanies()));
		$r=new Raion();
		$r->loadById($this->id);
		$title="Titluri de Stiri din ";
		$this->setTitle($title.$r->getFullNameDescription());
		$this->setCenterContainer($this->getGroupBoxH1($title.$r->getFullNameDescription(),$this->fi->getNewsByRaion($r->id,2000)));
		$this->setRightContainer($this->getGroupBoxH3("Stire pe Localitati:",$this->getRaions($this->id)));
		$this->show();
	}
	function actionPrimarie(){
		$this->setLeftContainer($this->getGroupBoxH3("Utile:", $this->getMenuFeeds()));
		$this->setLeftContainer($this->getGroupBoxH3("Stiri pe Surse:", $this->fi->getTopCompanies()));
		$l=new Location();
		$l->loadById($this->id);
		$title="Titluri de Stiri din Primaria ";
		$this->setTitle($title.$l->getFullNameDescription());
		$this->setCenterContainer($this->getGroupBoxH1($title.$l->getFullNameDescription(),$this->fi->getNewsByPrimarie($l->id,2000)));
		$this->setRightContainer($this->getGroupBoxH3("Stire pe Localitati:",$this->getRaions(0)));		
		$this->show();
	}			
	function actionRedirect(){
			if (isset($this->id)&&(is_numeric($this->id))){
			$fi=new FeedItem();
			$fi->loadById($this->id);
			if ($fi->link!=""){
				$fi->count();
				$this->redirect($fi->link);
			} else {
				$this->redirect("index.php");
			}
		} else {
			$this->redirect("index.php");
		}
	}	
	function getMenuFeeds(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Stiri de Azi</a></li>';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=yesterday").'">Stiri de Ieri</a></li>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=top10today").'">Top 10 Read Today</a></li>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=top10today").'">Top 10 Read Yesterday</a></li>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=top10today").'">Top 10 Read Last Week</a></li>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=top10today").'">Top 10 Read Last Month</a></li>';								
		$out.='</ul>';
		return $out;
	}			
	function show($out=''){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="left" class="container left" style="width:198px;">';
		$out.=$this->getLeftContainer();
		$out.='</div>';		
		$out.='<div id="center" class="container center" style="width:600px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right" class="container right" style="width:198px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}			
	function getLastNews(){
		$out="";
		$n=new FeedItem();
		$ns=$n->doSql("select fi.id,title,link,fi.description,pubdate,companyid,c.name from feeditem as fi inner join company as c on fi.companyid=c.id where fi.status=1 order by fi.id desc");
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;" class="pure-table pure-table-bordered">';
		$out.='<thead><tr><th style="width:20%;">Nr</th><th style="width:20%;">Compania</th><th style="width:50%;">Title</th><th style="width:30%;text-align:center">Descriere</th><th style="width:30%;text-align:center">Data</th></tr></thead>';		
		$out.="<tbody>";
		if (count($ns)!=0){			
			$c=1;
			foreach($ns as $n){
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$n->id);
				$out.='<tr><td>'.$n->id.'</td><td>'.$n->name.'</td><td><a href="'.$n->link.'">'.$n->title.'</a></td><td>'.$n->description.'</td><td>'.$n->pubdate.'</td></tr>';
				$c=$c+1;	
			}
		}
		$out.="</tbody>";
		$out.="</table>";
		$out.="</div>";	

		
		return $out;
	}
	function getTodayNews(){
		$out="";
		$n=new FeedItem();
		$now=new DateTime();
		$ns=$n->doSql("select fi.id,title,link,fi.description,pubdate,companyid,c.name from feeditem as fi inner join company as c on fi.companyid=c.id where createdat>='".$now->format('Y-m-d')."' order by fi.id desc");
		$out.=$this->getNewsTable($ns);
		return $out;
	}
	function getYesterdayNews(){
		$out="";
		$n=new FeedItem();
		$now=new DateTime();
		$now=$now->sub(new DateInterval('P1D'));
		$ns=$n->doSql("select fi.id,title,link,fi.description,pubdate,companyid,c.name from feeditem as fi inner join company as c on fi.companyid=c.id where createdat>='".$now->format('Y-m-d')."' order by fi.id desc");
		$out.=$this->getNewsTable($ns);
		return $out;
	}
	function getLocalitatiNews(){
		$out="";
		$n=new FeedItem();
		$now=new DateTime();
		$now=$now->sub(new DateInterval('P1D'));
		$ns=$n->doSql("select fi.id,title,link,fi.description,pubdate,companyid,c.name from feeditem as fi inner join company as c on fi.companyid=c.id where createdat>='".$now->format('Y-m-d')."' order by fi.id desc");
		$out.=$this->getNewsTable($ns);
		return $out;
	}	
	function getNewsTable($ns){
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;" class="pure-table pure-table-bordered">';
		$out.='<thead><tr><th>Nr</th><th>Compania</th><th>Title</th><th>Data</th></tr></thead>';
		$out.='<tbody>';
		if (count($ns)!=0){
			$c=count($ns);
			foreach($ns as $n){
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=redirect&id=".$n->id);
				$out.='<tr><td>'.$c.'</td><td>'.$n->name.'</td><td><a href="'.$url.'" target="_blank">'.$n->title.'</a></td><td>'.$n->pubdate.'</td></tr>';
				$c=$c-1;
			}
		}
		$out.='</tbody>';
		$out.='</table>';
		$out.='</div>';	
		return $out;
	}
	function getRaions($raionid){		
		$r=new Raion();
		$rs=$r->getAll("","municipiu desc");
		$out="<ul>";
		foreach($rs as $r){	
			if ($r->id==$raionid){
				$out.='<li style="font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=raion&id='.$r->id).'">'.$r->getFullName().'</a></li>';			
				$out.=$this->getLocationsByRaion($raionid);
			} else {
				$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=raion&id='.$r->id).'">'.$r->getFullName().'</a></li>';
			}
		}
		$out.="</ul>";
		return $out;
	}
	function getLocationsByRaion($raionid,$locationid=0){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$raionid,"oras desc, `order`, name");
		$out="<ul style=\"margin:10px;\">";
		foreach($ls as $l){	
			if ($locationid==$l->id){
				$out.='<li style="list-style-type: circle;font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=localitate&id='.$l->id).'">'.$l->getFullName().'</a></li>';
			} else {
				$out.='<li style="list-style-type: circle;"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=localitate&id='.$l->id).'">'.$l->getFullName().'</a></li>';
			}
						
		}
		$out.="</ul>";
		return $out;
	}
	function getLocations($locationid){
		$l=new Location();
		$l->loadById($locationid);

		$r=new Raion();
		$rs=$r->getAll("","municipiu desc");
		$out="<ul>";
		foreach($rs as $r){	
			if ($r->id==$l->raion_id){
				$out.='<li style="font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=raion&id='.$r->id).'">'.$r->getFullName().'</a></li>';			
				$out.=$this->getLocationsByRaion($l->raion_id,$locationid);
			} else {
				$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=raion&id='.$r->id).'">'.$r->getFullName().'</a></li>';
			}
		}
		$out.="</ul>";
		return $out;
	}						
			
}
$n=new FeedsWebPage();
?>
