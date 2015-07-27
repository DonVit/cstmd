<?php
require_once(__DIR__ . '/../main/loader.php');
 
class PhotosWebPage extends MainWebPage {
	public $months = array(
		1 => "Ianuarie", 
		2 => "Februarue", 
		3 => "Martie", 
		4 => "Aprilie", 
		5 => "Mai", 
		6 => "Iunie", 
		7 => "Iulie", 
		8 => "August", 
		9 => "Septembrie", 
		10 => "Octombrie", 
		11 => "Noiembrie", 
		12 => "Decembrie"
		);
	public $seasons = array(
		1 => "Iarna",
		2 => "Primavara",
		3 => "Vara",
		4 => "Toamna"
	);	
	public $rowsperpage=21;
	function __construct(){
		parent::__construct();
		$this->setLogoTitle("IMAGINI DIN REPUBLICA MOLDOVA");	
		$this->create();		
	}
	function actionDefault(){
		$this->setTitle($this->getConstants("IndexPhotosWebPageRaioaneTitle"));
		if (isset($this->id)){
			$this->redirect($this->getUrl(Config::$imagessite.'/index.php','action=viewimage&id='.$this->id));
		}		
		if(!isset($this->page)){
			$this->page=0;
		}
		
		$out=$this->getImagesByAll($this->page,$this->rowsperpage);
		$out.=$this->getPagesByAll($this->page,$this->rowsperpage,$this->getUrlWithSpecialCharsConverted("index.php","action=viewimages"));
			
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRaions"),$this->getRaions()));		
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageLocations"),$this->getLocations()));
		$this->setCenterContainer($this->getGroupBoxH2($this->getConstants("IndexPhotosWebPageRaioaneTitle"),$out));
		$this->setRightContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Ani:',$this->getYears()));				
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Anotimpuri:',$this->getSeasons()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Luni:',$this->getMonths()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRssLink"),$this->getRssLink()));		
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii recente:",Comment::getAllComments()));
		$this->show();
	}
	function actionViewRaionImages(){

		if(!isset($this->id)){
			$this->id=Raion::getTopFirstRaion()->id;
		}
		if(!isset($this->page)){
			$this->page=0;
		}

		$raion=new Raion();
		$raion->loadById($this->id);
		
		$title="Imagini ".$raion->getFullNameDescription();

		$this->setTitle($title);
		//$this->setLogoTitle($title);
		//$this->setLogoTitle("Fotografii, Imagini din Republica Moldova");


		$out=$this->getImagesByRaion($this->id,$this->page,$this->rowsperpage);
		$out.=$this->getPagesByRaion($this->id,$this->page,$this->rowsperpage,$this->getUrlWithSpecialCharsConverted("index.php","action=viewraionimages"));
	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRaions"),$this->getRaions()));		
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageLocations"),$this->getLocations()));
		$this->setCenterContainer($this->getGroupBoxH2($title,$out));
		$this->setRightContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Ani:',$this->getYears()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Anotimpuri:',$this->getSeasons()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Luni:',$this->getMonths()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRssLink"),$this->getRssLink()));
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii recente:",Comment::getAllComments()));
		
		$this->show();
	}
	function actionViewLocalitateImages(){

		if(!isset($this->id)){
			$this->id=Location::getTopFirstLocationByRaionId(Raion::getTopFirstRaion()->id)->id;
		}
		if(!isset($this->page)){
			$this->page=0;
		}

		$localitate=new Location();
		$localitate->loadById($this->id);
		
		$title="Imagini ".$localitate->getFullNameDescription();

		$this->setTitle($title);

		$out=$this->getImagesByLocalitate($this->id,$this->page,$this->rowsperpage);
		$out.=$this->getPagesByLocalitate($this->id,$this->page,$this->rowsperpage,$this->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitateimages"));
	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRaions"),$this->getRaions()));		
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageLocations"),$this->getLocations()));
		$this->setCenterContainer($this->getGroupBoxH2($title,$out));
		$this->setRightContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));		
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Ani:',$this->getYears()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Anotimpuri:',$this->getSeasons()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Luni:',$this->getMonths()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRssLink"),$this->getRssLink()));		
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii recente:",Comment::getAllComments()));
				
		$this->show();
	}
	function actionViewYearImages(){
	
		if(!isset($this->id)){
			$this->id=date('Y');
		}
		if(!isset($this->page)){
			$this->page=0;
		}

		$title='Imagini din Anul '.$this->id.', Republica Moldova';
	
		$this->setTitle($title);
	
		$out=$this->getImagesByYear($this->id,$this->page,$this->rowsperpage);
		$out.=$this->getPagesByYear($this->id,$this->page,$this->rowsperpage,$this->getUrlWithSpecialCharsConverted("index.php","action=viewyearimages"));
	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRaions"),$this->getRaions()));
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageLocations"),$this->getLocations()));
		$this->setCenterContainer($this->getGroupBoxH2($title,$out));
		$this->setRightContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Ani:',$this->getYears()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Anotimpuri:',$this->getSeasons()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Luni:',$this->getMonths()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRssLink"),$this->getRssLink()));		
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii recente:",Comment::getAllComments()));
		
		$this->show();
	}
	function actionViewMonthImages(){
	
		if(!isset($this->id)){
			$this->id=date('n');
		}
		if(!isset($this->page)){
			$this->page=0;
		}
	
		//$localitate=new Location();
		//$localitate->loadById($this->id);
	
		$title="Imagini de ".$this->months[$this->id].", Republica Moldova";
	
		$this->setTitle($title);
		//$this->setLogoTitle($title);
		//$this->setLogoTitle("Fotografii, Imagini din Republica Moldova");
	
		$out=$this->getImagesByMonth($this->id,$this->page,$this->rowsperpage);
		$out.=$this->getPagesByMonth($this->id,$this->page,$this->rowsperpage,$this->getUrlWithSpecialCharsConverted("index.php","action=viewmonthimages"));
	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRaions"),$this->getRaions()));
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageLocations"),$this->getLocations()));
		$this->setCenterContainer($this->getGroupBoxH2($title,$out));
		$this->setRightContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Ani:',$this->getYears()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Anotimpuri:',$this->getSeasons()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Luni:',$this->getMonths()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRssLink"),$this->getRssLink()));		
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii recente:",Comment::getAllComments()));
		
		$this->show();
	}
	function actionViewSeasonImages(){
	
		if(!isset($this->id)){
			$this->id=1;
		}
		if(!isset($this->page)){
			$this->page=0;
		}
		$title="Imagini de ".$this->seasons[$this->id].", Republica Moldova";
	
		$this->setTitle($title);
		
		$out=$this->getImagesBySeason($this->id,$this->page,$this->rowsperpage);
		$out.=$this->getPagesBySeason($this->id,$this->page,$this->rowsperpage,$this->getUrlWithSpecialCharsConverted("index.php","action=viewseasonimages"));
	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRaions"),$this->getRaions()));
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageLocations"),$this->getLocations()));
		$this->setCenterContainer($this->getGroupBoxH2($title,$out));
		$this->setRightContainer($this->getGroupBoxH3("Adauga:",$this->getAddMenu()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Ani:',$this->getYears()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Anotimpuri:',$this->getSeasons()));
		$this->setRightContainer($this->getGroupBoxH3('Imagini pe Luni:',$this->getMonths()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexPhotosWebPageRssLink"),$this->getRssLink()));		
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii recente:",Comment::getAllComments()));
		
		$this->show();
		
	}					
	function actionViewImage(){

		$p=new Photo();
		if ($p->loadById($this->id)){
			//$this->redirect(Config::$imagessite);
			$p->count();
		}

		$this->setTitle($p->getLongTitle());

		if(!isset($this->id)){
			$this->id=Location::getTopFirstLocationByRaionId(Raion::getTopFirstRaion()->id)->id;
		}
		
		$this->setCenterContainer($this->getGroupBoxH2($p->getLongTitle(),$this->getImage($p)));
		$this->setCenterContainer($this->getGroupBoxH3("Alte Date:",$this->getSystemDetails($p)));
		$this->setCenterContainer($this->getGroupBoxH3("Taguri:",$this->getTags($p)));
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii:",Comment::getComments($this,'p',$p->id)));
		$this->setRightContainer($this->getGroupBoxH3("Pozitia pe harta a imaginii:",$this->getMap($p)));
		$this->setRightContainer($this->getGroupBoxH3("Imagini din jur:",$this->getImagesAround($p)));
		$this->setRightContainer($this->getGroupBoxH3("Imagini recente:",$this->getLatestImages($p)));
		$this->setRightContainer($this->getGroupBoxH3("Comentarii recente:",Comment::getAllComments()));						
		$this->showImage();
	}
	function actionViewFullImage(){

		$p=new Photo();
		if ($p->loadById($this->id)){
			//$this->redirect(Config::$imagessite);
			$p->count();
		}

		$this->setTitle($p->getLongTitle());

		if(!isset($this->id)){
			$this->id=Location::getTopFirstLocationByRaionId(Raion::getTopFirstRaion()->id)->id;
		}
		
		$this->setCenterContainer($this->getGroupBoxH1($p->getLongTitle(),$this->getFullImage($p)));
		$this->setCenterContainer($this->getGroupBoxH3("Alte Date:",$this->getSystemDetails($p)));
		$this->setCenterContainer($this->getGroupBoxH3("Pozitia pe harta a imaginii:",$this->getMap($p)));
		$this->setCenterContainer($this->getGroupBoxH3("Taguri:",$this->getTags($p)));		
		$this->setCenterContainer($this->getGroupBoxH3("Imagini din jur:",$this->getImagesAround($p,8)));
		$this->setCenterContainer($this->getGroupBoxH3("Imagini recente:",$this->getLatestImages($p,8)));
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii:",Comment::getComments($this,'p',$p->id)));
		$this->setCenterContainer($this->getGroupBoxH3("Comentarii recente:",Comment::getAllComments()));						
		$this->showFullImage();
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
	function showImage(){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="center" class="container center" style="width:600px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right" class="container right" style="width:398px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function showFullImage(){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="center" class="container center" style="width:998px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}	
	
	function getRightMenu(){
		$out='<ul>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'" title="Populatia">Lista si numarul de Municipii</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">'.$this->getConstants("IndexLocationsWebPageRaioaneTitle").'</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=vieworase").'" >'.$this->getConstants("IndexLocationsWebPageOraseTitle").'</a></li>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=vieworase").'" title="Stiri">Lista a 30 cele mai jos amplasate localitati</a></li>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=vieworase").'" title="Stiri">Lista a 30 cele mai sus amplasate localitati</a></li>';
		//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=vieworase").'" title="Stiri">Lista a 30 cele mai sus amplasate localitati</a></li>';	
		//$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai mari sate</a></li>';
		//$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai mici sate</a></li>';		
		//$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai jos amplasate localitati</a></li>';
		//$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai sus amplasate localitati</a></li>';		
		//$out.='<li><a href="index.php#3" title="Imobil">Lista celor 30 cele mai populate Localitati</a></li>';				
		//$out.='<li><a href="index.php#4" title="Imagini">Lista celor 30 cele mai putin populate Localitati</a></li>';
		//$out.='<li><a href="index.php#4" title="Imagini">Populatia pe Municipii si Raioane</a></li>';		
		$out.='</ul>';
		return $out;		
	}
	function getLocations(){
		$lt=new Location();
		$lts=$lt->doSql("SELECT l.id FROM photos AS p INNER JOIN localitate AS l ON p.localitate_id = l.id GROUP BY l.id");
		$st='';
		foreach($lts as $lt){	
			$st.=$lt->id.',';			
		}
		$st.='0';
		
		$l=new Location();
		$ls=$l->getAll("id in (".$st.")","oras desc, `order`, name");
		$out="<ul>";
		foreach($ls as $l){	
			$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewlocalitateimages&id='.$l->id).'">'.$l->getFullName().'</a></li>';			
		}
		$out.="</ul>";
		return $out;
	}
	function getRaions(){
		$rt=new Raion();
		$rts=$rt->doSql("SELECT r.id FROM photos AS p INNER JOIN raion AS r ON p.raion_id = r.id GROUP BY r.id");
		$st='';
		foreach($rts as $rt){	
			$st.=$rt->id.',';			
		}
		$st.='0';
		
		$r=new Raion();
		$rs=$r->getAll("id in (".$st.")","municipiu desc, `order`, name");
		$out="<ul>";
		foreach($rs as $r){	
			$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewraionimages&id='.$r->id).'">'.$r->getFullName().'</a></li>';			
		}
		$out.="</ul>";
		return $out;
	}
	function getYears(){
		$y=new DBManager();
		$ys=$y->doSql("SELECT year(data) as year, count(*) as cnt FROM photos GROUP BY year(data)");
		$out="<ul>";
		foreach($ys as $y){	
			$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewyearimages&id='.$y->year).'">'.$y->year.' ('.$y->cnt.')</a></li>';			
		}
		$out.="</ul>";
		return $out;
	}
	function getMonths(){
		$y=new DBManager();
		$ys=$y->doSql("SELECT month(data) as month, count(*) as cnt FROM photos GROUP BY month(data)");
		$out="<ul>";
		foreach($ys as $y){
			$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewmonthimages&id='.$y->month).'">'.$this->months[$y->month].' ('.$y->cnt.')</a></li>';
		}
		$out.="</ul>";
		return $out;
	}
	function getSeasons(){
		$y=new DBManager();
		$ys=$y->doSql("SELECT month(data) as month, count(*) as cnt FROM photos GROUP BY month(data)");
		$out="<ul>";
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewseasonimages&id=1').'">'.$this->seasons[1].' ('.($ys[11]->cnt+$ys[0]->cnt+$ys[1]->cnt).')</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewseasonimages&id=2').'">'.$this->seasons[2].' ('.($ys[2]->cnt+$ys[3]->cnt+$ys[4]->cnt).')</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewseasonimages&id=3').'">'.$this->seasons[3].' ('.($ys[5]->cnt+$ys[6]->cnt+$ys[7]->cnt).')</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewseasonimages&id=4').'">'.$this->seasons[4].' ('.($ys[8]->cnt+$ys[9]->cnt+$ys[10]->cnt).')</a></li>';						
		$out.="</ul>";
		return $out;
	}		
	function getSeasonByMonth($month){

		if(in_array($month,array('3','4','5'))){
			return $this->seasons[2];
		}		
		if(in_array($month,array('6','7','8'))){
			return $this->seasons[3];
		}		
		if(in_array($month,array('9','10','11'))){
			return $this->seasons[4];
		}
		return $this->seasons[1];
	}
	function getSeasonIdByMonth($month){
	
		if(in_array($month,array('3','4','5'))){
			return 2;
		}
		if(in_array($month,array('6','7','8'))){
			return 3;
		}
		if(in_array($month,array('9','10','11'))){
			return 4;
		}
		return 1;
	}							
	function getRssLink(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="rss.php">Imagini in format RSS <img src="img/rss.png" alt="Foto in format RSS" title="Foto in format RSS"/></a></li>';
		$out.='</ul>';
		return $out;
	}
	function getImagesByAll($page,$rowsperpage){
		$sql="select id, title, file from photos where deleted=0 order by data desc";
		$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
		return $this->getImages($sql);
	}
	function getImagesByRaion($raionid,$page,$rowsperpage){
		$sql='select id, title, file from photos where deleted=0 and raion_id='.$raionid.' order by data desc';
		$sql.=' limit '.$page*$rowsperpage.','.$rowsperpage;
		return $this->getImages($sql);
	}
	function getImagesByLocalitate($localitateid,$page,$rowsperpage){
		$sql='select id, title, file from photos where deleted=0 and localitate_id='.$localitateid.' order by data desc';
		$sql.=' limit '.$page*$rowsperpage.','.$rowsperpage;
		return $this->getImages($sql);
	}
	function getImagesByYear($year,$page,$rowsperpage){
		$sql='select id, title, file from photos where deleted=0 and year(data)='.$year.' order by data desc';
		$sql.=' limit '.$page*$rowsperpage.','.$rowsperpage;
		return $this->getImages($sql);
	}
	function getImagesByMonth($month,$page,$rowsperpage){
		$sql='select id, title, file from photos where deleted=0 and month(data)='.$month.' order by data desc';
		$sql.=' limit '.$page*$rowsperpage.','.$rowsperpage;
		return $this->getImages($sql);
	}
	function getImagesBySeason($season,$page,$rowsperpage){
		
		$seasoncond='12,1,2';
		if($season==2){
			$seasoncond='3,4,5';
		}
		if($season==3){
			$seasoncond='6,7,8';
		}		
		if($season==4){
			$seasoncond='9,10,11';
		}		
		$sql='select id, title, file from photos where deleted=0 and month(data) in ('.$seasoncond.') order by data desc';
		$sql.=' limit '.$page*$rowsperpage.','.$rowsperpage;
		return $this->getImages($sql);
	}				
	function getImages($sql){
		$p=new Photo();
		$ps=$p->doSql($sql);
		$out='<table style="width:100%">';
		$i=1;
		$o='';
		foreach($ps as $p){
	  		if ($i==1){
	  			$o='<tr>';
  			}

		  	$o.='<td  style="align:center;padding:10px;vertical-align:top;width:33%">';
	  		$o.='<div><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewimage&id='.$p->id).'"><img src="files/t'.$p->file.'" alt="'.System::getHtmlSpecialChars($p->title).'" class="imageborder"/><p style="font-size:80%;">'.System::getHtmlSpecialChars($p->title).'</p></a></div>';	  		
	  		$o.='</td>';
	  		
	  		if ($i==3){
	  			$out.=$o.'</tr>';
	  			$o='';
	  			$i=0;  			}
	  		$i=$i+1;
		}
		if ($i!=1){
			$out.=$o.'</tr>';
		}
		$out.='</table>';
		return $out;
	}	
	function getImage($p){
		$out='<table style="width:100%" ><tr><td style="text-align:center" >';
  		$out.='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewfullimage&id='.$p->id).'"><img src="files/s'.$p->file.'" alt="'.$p->getLongTitle().'" class="imageborder"/></a>';
		$prev=$p->getPrevPhotoId();
		$tmp='';
		if ($prev!=0){
			$tmp='<a style="margin-right:5px;" href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewimage&id='.$prev).'"><img src="img/media_previous_arrow.gif" alt="Precedenta" /></a>';	
		}
		$next=$p->getNextPhotoId();
		if ($next!=0){
			$tmp.='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewimage&id='.$next).'"><img src="img/media_next_arrow.gif" alt="Urmatoarea" /></a>';	
		}
		$out.='</td></tr><tr><td style="text-align:center">'.$tmp.'</td></tr></table>';
		return $out;
	}
	function getFullImage($p){
		$out='<table style="width:100%" ><tr><td style="text-align:center" >';
  		$out.='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewfullimage&id='.$p->id).'"><img src="files/'.$p->file.'" alt="'.$p->getLongTitle().'" class="imageborder" style="width:900px;"/></a>';
		$prev=$p->getPrevPhotoId();
		$tmp='';
		if ($prev!=0){
			$tmp='<a style="margin-right:5px;" href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewfullimage&id='.$prev).'"><img src="img/media_previous_arrow.gif" alt="Precedenta" /></a>';	
		}
		$next=$p->getNextPhotoId();
		if ($next!=0){
			$tmp.='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','&action=viewfullimage&id='.$next).'"><img src="img/media_next_arrow.gif" alt="Urmatoarea" /></a>';	
		}
		$out.='</td></tr><tr><td style="text-align:center">'.$tmp.'</td></tr></table>';
		return $out;
	}	
	function getImagesAround($p,$colums=3){
		$ps=$p->getPhotosInRadius($colums*2);
		$out='<table style="width:100%" >';
		$i=1;
		$o="";
		foreach($ps as $p){
		  	$o.='<td style="padding:5px;vertical-align:top;align:center;">';
	  		$o.='<div><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewimage&id='.$p->id).'"><img src="files/t'.$p->file.'" alt="'.$p->title.'" class="imageborder" style="width:100px;"/></a></div>';	  		
	  		$o.='</td>';
	  		
	  		if (($i % $colums)==0){
	  			$out.='<tr>'.$o.'</tr>';
	  			$o='';
	  		}
	  		$i=$i+1;
		}
		$out.='</table>';
		return $out;
	}
	function getLatestImages($p, $columns=3){
		$ps=$p->getLatestPhotos($columns*2);
		$out='<table style="width:100%" >';
		$i=1;
		$o="";
		foreach($ps as $p){
			$o.='<td style="padding:5px;vertical-align:top;align:center">';
			$o.='<div><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewimage&id='.$p->id).'"><img src="files/t'.$p->file.'" alt="'.System::getHtmlSpecialChars($p->title).'" class="imageborder" style="width:100px;"/></a></div>';
			$o.='</td>';
		  
			if (($i % $columns)==0){
				$out.='<tr>'.$o.'</tr>';
				$o='';
			}
			$i=$i+1;
		}
		$out.='</table>';
		return $out;
	}	
	function getTags($p){
		$r=new Raion();
		$r->loadById($p->raion_id);
		$l=new Location();
		$l->loadById($p->localitate_id);
		
		$out='<a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/index.php','action=viewraionimages&id='.$r->id).'" target="_self" title="Foto '.$r->getFullNameDescription().'">Foto '.$r->getFullNameDescription().'</a>';
		$out.='<br/><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/index.php','action=viewlocalitateimages&id='.$l->id).'" target="_self" title="Foto '.$l->getFullNameDescription().'">Foto '.$l->getFullNameDescription().'</a>';
		$out.='<br/><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/index.php','action=viewyearimages&id='.date('Y',strtotime($p->data))).'" target="_self" title="Foto Anul '.date('Y',strtotime($p->data)).'">Foto Anul '.date('Y',strtotime($p->data)).'</a>';
		$out.='<br/><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/index.php','action=viewmonthimages&id='.date('n',strtotime($p->data))).'" target="_self" title="Foto de '.$this->months[date('n',strtotime($p->data))].'">Foto de '.$this->months[date('n',strtotime($p->data))].'</a>';
		$out.='<br/><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$imagessite.'/index.php','action=viewseasonimages&id='.$this->getSeasonIdByMonth(date('n',strtotime($p->data)))).'" target="_self" title="Foto de '.$this->getSeasonByMonth(date('n',strtotime($p->data))).'">Foto de '.$this->getSeasonByMonth(date('n',strtotime($p->data))).'</a>';
		$out.='<br/>';
		$out.='<br/><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite.'/index.php','action=viewraion&id='.$r->id).'" target="_self" title="'.$r->getFullName().'">'.$r->getFullNameDescription().'</a>';
		$out.='<br/><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite.'/index.php','action=viewlocalitate&id='.$l->id).'" target="_self" title="'.$l->getFullName().'">'.$l->getFullNameDescription().'</a>';						
		return $out;
	}
	function getSystemDetails($p){
		$out='<div>';
		$out.='<div id="property-view-dateq1" style="float:left">';
		$out.='Data si Timpul fotografierii: <b>'.$p->data.'</b>';
		$out.='</div>';
		$out.='<div id="property-view-dateq2" style="float:right">';
		$out.='Vizualizari: <b>'.$p->contor.'</b>';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $out;
	}	
	function getPagesByAll($page,$rowsperpage,$url){
		$sql="select count(*) as cnt from photos where deleted=0";
		return $this->getPages($sql,$page,$rowsperpage,$url);
	}
	function getPagesByRaion($raionid,$page,$rowsperpage,$url){
		$sql="select count(*) as cnt from photos where deleted=0 and raion_id=".$raionid;
		return $this->getPages($sql,$page,$rowsperpage,$url,$raionid);
	}
	function getPagesByLocalitate($localitateid,$page,$rowsperpage,$url){
		$sql='select count(*) as cnt from photos where deleted=0 and localitate_id='.$localitateid;
		return $this->getPages($sql,$page,$rowsperpage,$url,$localitateid);
	}
	function getPagesByYear($year,$page,$rowsperpage,$url){
		$sql='select count(*) as cnt from photos where deleted=0 and year(data)='.$year;
		return $this->getPages($sql,$page,$rowsperpage,$url,$year);
	}
	function getPagesByMonth($month,$page,$rowsperpage,$url){
		$sql='select count(*) as cnt from photos where deleted=0 and month(data)='.$month;
		return $this->getPages($sql,$page,$rowsperpage,$url,$month);
	}
	function getPagesBySeason($season,$page,$rowsperpage,$url){
		$seasoncond='12,1,2';
		if($season==2){
			$seasoncond='3,4,5';
		}
		if($season==3){
			$seasoncond='6,7,8';
		}
		if($season==4){
			$seasoncond='9,10,11';
		}
		$sql='select count(*) as cnt from photos where deleted=0 and month(data) in ('.$seasoncond.') order by data desc';
		

		return $this->getPages($sql,$page,$rowsperpage,$url,$season);
	}									
	function getPages($sql,$page,$rowsperpage,$url="",$id=0){
		$p=new Photo();
		$ps=$p->doSql($sql);
		$out='<table style="width:100%" ><tr><td style="align:center" >';
	   	foreach($ps as $p){
	   		$cnt=$p->cnt;
		}
		if ($id!=0){
			$url=$url."&id=".$id;
		}
		if ($page!=0){
			$out.='<a href="'.$url.'&page='.($page-1).'" class="link_button">< Inapoi</a>';
		}
		$out.=" ";
		if ((($page+1)*$rowsperpage)<$cnt){
			$out.='<a href="'.$url.'&page='.($page+1).'" class="link_button">Inainte ></a>';
		}
		$out.='</td></tr></table>';
		return $out;
	}								

}
$n=new PhotosWebPage();

?>
