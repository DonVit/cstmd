<?php
require_once(__DIR__ . '/../main/loader.php');
 class CompaniesWebPage extends MainWebPage {
	public $rowsperpage=10;
	public $page;
	function __construct(){
		parent::__construct();
		$t="COMPANII DIN REPUBLICA MOLDOVA";
		$this->setLogoTitle($t);		
		$this->create();		
	}
	function actionDefault(){
		$t="COMPANII, TIPURI COMPANII DIN REPUBLICA MOLDOVA";
		if (isset($this->id)){
			$this->redirect($this->getUrl(Config::$companiesite.'/index.php','action=viewcompany&id='.$this->id));
		}
		$this->setTitle($t);	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->setCenterContainer($this->getGroupBoxH1($t,$this->getCompanyTypes()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		$this->setLeftContainer($this->getGroupBoxH3("Companii pe Raioane:",$this->getRaions(0)));
		$this->show();
	}
	function actionViewCompany(){

		$c=new Company();
		if ($c->loadById($this->id)){
			$c->count();
		}
		$i=new Image();
		$is=$i->getAll("reftype='f' and reftypeid=".$c->id);

		$fs=User::getCurrentFiles();
		for ($i = 0; $i <= 4; $i++) {
			if (isset($is[$i])){
				$fs[$i]=$is[$i];
				$fs[$i]->error=0;
			}
		}
		User::setCurrentFiles($fs);		

		$this->setTitle('Companii in Moldova: '.$c->name);
		$this->setDescription($c->description);
		$this->setLeftContainer($this->getGroupBoxH3("Menu:",$this->getMenu()));
		$this->setCenterContainer($this->getGroupBoxH1($this->getCompanyTitle($c),$this->getCompanyDetails($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a id="2"></a>Adresa:',$this->getAdress($c)));
		
		$this->setCenterContainer($this->getGroupBoxH3('<a id="3"></a>Contacte:',$this->getContacts($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a id="4"></a>Pozitia pe Harta:',$this->getOsm($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a id="5"></a>Imagini:',$this->getFiles()));
		$n=new News();
		
		//$this->setCenterContainer($this->getGroupBoxH3('<a id="6"></a>Ştiri ce aparţin acestei surse:',$n->getNewsByCompany($c)));
		$this->setCenterContainer($this->getGroupBoxH3('Alte date:',$this->getSystemDetails($c)));
		
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$c='<a id="10"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'c',$this->id)));
		
		$this->show();
		User::delCurrentFiles();					
		User::delCurrentCompany();
	}		
	function actionViewCompanies(){

		if (isset($this->id)){
			$ct=new CompanyType();
			$ct->loadById($this->id);
			$this->setTitle('Companii in Moldova: '.$ct->name);
			$this->setDescription($ct->description);
		} else {
			$this->id=0;
			$this->setTitle('Companii in Moldova: ');			
		}
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->setCenterContainer($this->getCompanies($this->id,$this->page));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		$this->setLeftContainer($this->getGroupBoxH3("Companii pe Raioane:",$this->getRaions(0)));
		$this->show();
	}	
	function actionViewCompaniesByRaion(){

		if (isset($this->id)){
			$r=new Raion();
			$r->loadById($this->id);
			$this->setTitle('Companii in Moldova: '.$r->getFullNameDescription());
			$this->setDescription('Companii in Moldova: '.$r->getFullNameDescription());
		} else {
			$this->id=0;
			$this->setTitle('Companii in Moldova: ');			
		}
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->setCenterContainer($this->getCompaniesByRaion($this->id,$this->page));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		$this->setLeftContainer($this->getGroupBoxH3("Companii pe Raioane:",$this->getRaions($this->id)));
		$this->show();
	}
	function actionViewCompaniesByLocation(){

		if (isset($this->id)){
			$l=new Location();
			$l->loadById($this->id);
			$this->setTitle('Companii in Moldova: '.$l->getFullNameDescription());
			$this->setDescription('Companii in Moldova: '.$l->getFullNameDescription());
		} else {
			$this->id=0;
			$this->setTitle('Companii in Moldova: ');			
		}
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->setCenterContainer($this->getCompaniesByLocation($this->id,$this->page));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		$this->setLeftContainer($this->getGroupBoxH3("Companii pe Raioane:",$this->getLocations($this->id)));
		$this->show();
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
	function getMenu(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompany&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompany&id=".$this->id."#2").'">Adresa</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompany&id=".$this->id."#3").'">Contacte</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompany&id=".$this->id."#4").'">Harta</a></li>';	
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompany&id=".$this->id."#5").'">Imagini</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompany&id=".$this->id."#6").'">Stiri</a></li>';		
		$out.='</ul>';
		return $out;
	}
	function getMenuLinks(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("add.php").'">Adauga Companie</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Lista Tipuri Companii</a></li>';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies").'">Lista Companii</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("activities.php","action=viewcompaniesbyraion&id=100").'">Lista Companiilor pe Localitati</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("activities.php","action=viewactivitiesbyraion&id=100").'">Lista Activitatilor Economice Licentiate</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("activities.php","action=viewnotlicencedactivitiesbyraion&id=100").'">Lista Activitatilor Economice Nelicentiate</a></li>';		
		$out.='</ul>';
		return $out;
	}
	function getRssLink(){
		$out='<ul>';
		$out.='<li><a href="rss.php">Companii in RSS <img src="'.Config::$commonsite.'/img/rss.png" alt="Companii in format RSS" title="Comapnii in format RSS"/></a></li>';
		$out.='</ul>';
		return $out;
	}
	function getCompanyTypes(){
		$c=new CompanyType;
		$cs=$c->getAll("","`name`");
		$out='';
		foreach ($cs as $c){
			$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies&id=".$c->id);
			$out.='<h3><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies&id=".$c->id).'">'.$c->name.'</a></h3>';		
		}
		return $out;
	}	
	function getCompanies($type,$page){	
		$p=new Company();
		if ($type!=0){
			$ps=$p->getAll("valid=1 and company_type_id=".$type,"created_date desc",$page,$this->rowsperpage);
		} else {
			$ps=$p->getAll("valid=1","created_date desc",$page,$this->rowsperpage);
		}		
		$out='';
		foreach($ps as $p){
	
			$o='<table style="width: 100%"><tr><td style="align:center">';
			$o.='<table  style="width: 100%" class="source">';
			$img=Image::getMainImageByRefType('f', $p->id);
			if ($img!=""){
				$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" style="align:right;valign:top;"><img src="data/t'.$img->imagepath.'" alt="'.$img->imagenote.'" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
			} else {
				$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" style="align:right;valign:top;"><img src="'.Config::$commonsite.'/img/no_image_100x100.jpg" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
			}
			$o.="<tr><td>Adresa:</td><td>".$p->getAdresa()."</td></tr>";
			$o.="<tr><td></td><td></td></tr>";
			$o.='<tr><td colspan="2"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompany&id='.$p->id).'">vezi mai multe detalii aici</a></td></tr>';
			$o.="</table>"; 			
			$o.="</td></tr></table>";
			$h='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompany&id='.$p->id).'">'.$p->name.'</a>';			
			//$f='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'action=viewcompany&id='.$p->id.'">vezi mai mult detalii aici</a>';
			$out.=$this->getGroupBoxH1($h,$o);	
		}
		$sql="select count(*) as cnt from company where deleted=0 and valid=1";
		if ($type!=0){
			$sql.=" and company_type_id=".$type;
		}	
		$p=new Company();
		$ps=$p->doSql($sql);
		$cnt=0;
	   	foreach($ps as $p){
	   		$cnt=$p->cnt;
		}
		//echo $cnt."-".$rowsperpage;
		if ($cnt>$this->rowsperpage){
			$out.='<div class="groupbox">';
			$out.='<table style="width:100%" ><tr><td style="align:center;" >';	
			if ($page!=0){
				$out.='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies&id=".$type."&page=".($page-1)).'" class="link_button">< Inapoi</a>';
			}
			$out.=" ";
			if ((($page+1)*$this->rowsperpage)<$cnt){
				$out.='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies&id=".$type."&page=".($page+1)).'" class="link_button">Inainte ></a>';
			}
			$out.='</td></tr></table>';
			$out.='</div>';			
		}
		return $out;
	}	
	function getCompaniesByRaion($raionid,$page){	
		$p=new Company();
		if ($raionid!=0){
			$ps=$p->getAll("valid=1 and raion_id=".$raionid,"created_date desc",$page,$this->rowsperpage);
		} else {
			$ps=$p->getAll("valid=1","created_date desc",$page,$this->rowsperpage);
		}		
		$out='';
		foreach($ps as $p){
	
			$o='<table style="width: 100%"><tr><td style="align:center">';
			$o.='<table  style="width: 100%" class="source">';
			$img=Image::getMainImageByRefType('f', $p->id);
			if ($img!=null){
				$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" style="align:right;valign:top;"><img src="data/t'.$img->imagepath.'" alt="'.$img->imagenote.'" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
			} else {
				$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" style="align:right;valign:top;"><img src="'.Config::$commonsite.'/img/no_image_100x100.jpg" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
			}
			$o.="<tr><td>Adresa:</td><td>".$p->getAdresa()."</td></tr>";
			$o.="<tr><td></td><td></td></tr>";
			$o.='<tr><td colspan="2"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompany&id='.$p->id).'">vezi mai multe detalii aici</a></td></tr>';
			$o.="</table>"; 			
			$o.="</td></tr></table>";
			$h='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompany&id='.$p->id).'">'.$p->name.'</a>';			
			//$f='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'action=viewcompany&id='.$p->id.'">vezi mai mult detalii aici</a>';
			$out.=$this->getGroupBoxH1($h,$o);	
		}
		$sql="select count(*) as cnt from company where deleted=0 and valid=1";
		if ($raionid!=0){
			$sql.=" and raion_id=".$raionid;
		}	
		$p=new Company();
		$ps=$p->doSql($sql);
		$cnt=0;
	   	foreach($ps as $p){
	   		$cnt=$p->cnt;
		}
		//echo $cnt."-".$rowsperpage;
		if ($cnt>$this->rowsperpage){
			$out.='<div class="groupbox">';
			$out.='<table style="width:100%" ><tr><td style="align:center;" >';	
			if ($page!=0){
				$out.='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompaniesbyraion&id=".$raionid."&page=".($page-1)).'" class="link_button">< Inapoi</a>';
			}
			$out.=" ";
			if ((($page+1)*$this->rowsperpage)<$cnt){
				$out.='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompaniesbyraion&id=".$raionid."&page=".($page+1)).'" class="link_button">Inainte ></a>';
			}
			$out.='</td></tr></table>';
			$out.='</div>';			
		}
		return $out;
	}
	function getCompaniesByLocation($locationid,$page){	
		$p=new Company();
		if ($locationid!=0){
			$ps=$p->getAll("valid=1 and localitate_id=".$locationid,"created_date desc",$page,$this->rowsperpage);
		} else {
			$ps=$p->getAll("valid=1","created_date desc",$page,$this->rowsperpage);
		}		
		$out='';
		if (count($ps)!=0){
			foreach($ps as $p){
		
				$o='<table style="width: 100%"><tr><td style="align:center">';
				$o.='<table  style="width: 100%" class="source">';
				$img=Image::getMainImageByRefType('f', $p->id);
				if ($img!=null){
					$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" style="align:right;valign:top;"><img src="data/t'.$img->imagepath.'" alt="'.$img->imagenote.'" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
				} else {
					$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" style="align:right;valign:top;"><img src="'.Config::$commonsite.'/img/no_image_100x100.jpg" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
				}
				$o.="<tr><td>Adresa:</td><td>".$p->getAdresa()."</td></tr>";
				$o.="<tr><td></td><td></td></tr>";
				$o.='<tr><td colspan="2"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompany&id='.$p->id).'">vezi mai multe detalii aici</a></td></tr>';
				$o.="</table>"; 			
				$o.="</td></tr></table>";
				$h='<a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompany&id='.$p->id).'">'.$p->name.'</a>';			
				//$f='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'action=viewcompany&id='.$p->id.'">vezi mai mult detalii aici</a>';
				$out.=$this->getGroupBoxH1($h,$o);	
			}
		
			$sql="select count(*) as cnt from company where deleted=0 and valid=1";
			if ($locationid!=0){
				$sql.=" and localitate_id=".$locationid;
			}	
			$p=new Company();
			$ps=$p->doSql($sql);
			$cnt=0;
		   	foreach($ps as $p){
		   		$cnt=$p->cnt;
			}
			//echo $cnt."-".$rowsperpage;
			if ($cnt>$this->rowsperpage){
				$out.='<div class="groupbox">';
				$out.='<table style="width:100%" ><tr><td style="align:center;" >';	
				if ($page!=0){
					$out.='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompaniesbylocation&id=".$locationid."&page=".($page-1)).'" class="link_button">< Inapoi</a>';
				}
				$out.=" ";
				if ((($page+1)*$this->rowsperpage)<$cnt){
					$out.='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompaniesbylocation&id=".$locationid."&page=".($page+1)).'" class="link_button">Inainte ></a>';
				}
				$out.='</td></tr></table>';
				$out.='</div>';			
			}
		}
		return $out;
	}		
	function getCompanyTitle($c){
		$out='<a id="1"></a>'.$c->name;
		return $out;
	}
	function getCompanyDetails($c){
		$out='<table style="width:100%" >';	
		$i=Image::getMainImageByRefType('f', $c->id);
		if ($i!=null){
			$out.='<tr><td style="width:20%">Descriere:</td><td style="text-align: justify;vertical-align: top;">'.nl2br($c->text).'</td><td style="align:right;valign:top;"><img src="data/t'.$i->imagepath.'" alt="'.$i->imagenote.'" class="imageborder"/></td></tr>';
		} else {
			$out.='<tr><td style="width:20%">Descriere:</td><td style="text-align: justify;vertical-align: top;">'.nl2br($c->text).'</td><td style="align:right;valign:top;"><img src="'.Config::$commonsite.'/img/no_image_100x100.jpg" class="imageborder"/></td></tr>';
		}
		$out.='</table>';		
		$out.='<table style="width:100%" >';		
		$out.='<tr><td style="width:20%">Tip Companie:</td><td><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies&id=".$c->company_type_id).'" target="_self">'.$c->getCompanyType()->name.'</a></td></tr>';		
		$out.='</table>';		
		
		return $out;
	}
	function getCompanyAddress1($c){
		$out='<table style="width:100%" >';		
		$out.='<tr><td style="width:20%">Municipiu/Raion:</td><td><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewraion").'&id='.$c->getRaion()->id.'" >'.$c->getRaion()->getFullName().'</a></td></tr>';
		$out.='<tr><td>Oras/Sat:</td><td><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate").'&id='.$c->getLocalitate()->id.'" >'.$c->getLocalitate()->getFullName().'</a></td></tr>';
		$out.='<tr><td>Sector/Regiune:</td><td>'.$c->getSector()->name.'</td></tr>';				
		$out.='<tr><td>Strada:</td><td>'.$c->strada.'</td></tr>';
		$out.='</table>';		
		$out.='</fieldset>';
 		return $out;
 	}	
	function getCompanyContacts($c){
		$out='<table style="width:100%" >';
		if ($c->phone2==''){
			$out.='<tr><td style="width:20%">Telefon:</td><td>'.$c->phone1.'</td></tr>';
		} else {
			$out.='<tr><td style="width:20%">Telefon:</td><td>'.$c->phone1.', '.$c->phone2.'</td></tr>';
		}
		$out.='<tr><td>Mobil:</td><td>'.$c->mobile.'</td></tr>';
		$out.='<tr><td>Fax:</td><td>'.$c->fax.'</td></tr>';
		if (substr($c->website,0,7)!='http://'){
			$c->website='http://'.$c->website;
		}
		$out.='<tr><td>Web Site:</td><td><a href='.$c->website.' target="_blank">'.$c->website.'</a></td></tr>';
		$out.='<tr><td>Email:</td><td><a href="mailto:'.$c->email.'">'.$c->email.'</a></td></tr>';
		$out.='</table>';		
		//$out.='</fieldset>';
		return $out;
	}
	function getCompanyNews1($c){
		//$sql="SELECT id, title, image_file, image_url, image_description FROM `news`WHERE news.valid=1 AND `company_id`=$id AND `image_file` != \"\" AND id!=$id ORDER BY `date` DESC LIMIT 0,8 ";
		//$n=new News;
		$out='';
		$ns=$c->getNews();
		if (count($ns)!=0){		
			//$out="<div class=\"groupbox\">";
			//$out.="<h2 class=\"newsgroup_h2\">Ştiri ce aparţin acestei surse:</h2>";
			$out.='<table>';
			$i=0;
			foreach($ns as $n){
				if ($i==0){
					$out.='<tr>';
				}
				if ($i==4){
					$out.='</tr><tr>';
				}
				$img=Image::getMainImageByRefType('n', $n->id);
				if($img!=null){
					$out.='<td><div><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$newssite."/index.php","action=viewnews&id=".$n->id).'"><img src="'.Config::$newssite.'/data/t'.$img->imagepath.'" alt="'.$img->imagenote.'" style="width:120px;"/><p>'.$n->title.'</p></a></div></td>';
				}else{
					$out.='<td><div><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$newssite."/index.php","action=viewnews&id=".$n->id).'"><img src="'.Config::$commonsite.'/img/no_image_100x100.jpg" alt="no image" style="width:120px;"/><p>'.$n->title.'</p></a></div></td>';
				}
				//$out.="<td class=\"newsgroup_td\"><div><a href=\"".$this->getBaseName()."?id=$n->id\"><img src=\"".Config::$imagessite."/thumbs/".$n->image_file."\" alt=\"".$n->image_description."\" class=\"newsgroup_img\"/><p class=\"newsgroup_p\">$n->title</p></a></div></td>";
				
				if ($i==8)	{
					$out.='</tr>';
				}
				$i=$i+1;
			}
			$out.='</table>';
		}
		return $out;
	}
	function show1($html=""){
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
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';	
		MainWebPage::show($out);
	}
	function getLeftContainer1(){
		$out="";
		$out.=$this->getAddCompany();
		$out.=$this->getCompanyTypeList();	
		$out.=$this->getCompanyList();	

		return $out;	
	}
	function getRaions($raionid){		
		$r=new Raion();
		$rs=$r->getAll("","municipiu desc");
		$out="<ul>";
		foreach($rs as $r){	
			if ($r->id==$raionid){
				$out.='<li style="font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompaniesbyraion&id='.$r->id).'">'.$r->getFullName().'</a></li>';			
				$out.=$this->getLocationsByRaion($raionid);
			} else {
				$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompaniesbyraion&id='.$r->id).'">'.$r->getFullName().'</a></li>';
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
				$out.='<li style="list-style-type: circle;font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompaniesbylocation&id='.$l->id).'">'.$l->getFullName().'</a></li>';
			} else {
				$out.='<li style="list-style-type: circle;"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompaniesbylocation&id='.$l->id).'">'.$l->getFullName().'</a></li>';
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
				$out.='<li style="font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompaniesbyraion&id='.$r->id).'">'.$r->getFullName().'</a></li>';			
				$out.=$this->getLocationsByRaion($l->raion_id,$locationid);
			} else {
				$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted('index.php','action=viewcompaniesbyraion&id='.$r->id).'">'.$r->getFullName().'</a></li>';
			}
		}
		$out.="</ul>";
		return $out;
	}				
	function getSystemDetails($c){
		$out='<div>';
		$out.='<div id="property-view-dateq1" style="float:left">';
		$out.='Data publicarii: '.$c->getData();
		$out.='</div>';
		$out.='<div id="property-view-dateq2" style="float:right">';
		$out.='Vizualizari: '.$c->contor;
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $out;
	}
}
$n=new CompaniesWebPage();

?>
