<?php
require_once(__DIR__ . '/../main/loader.php');
 class CompaniesWebPage extends MainWebPage {
	public $rowsperpage=100;
	public $page=0;
	public $list_title="";
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
		} else {
			$this->redirect($this->getUrl(Config::$companiesite.'/index.php'));
		}
		$this->setTitle($t);	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->setCenterContainer($this->getGroupBoxH1($t,$this->getCompanyTypes()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		$this->setLeftContainer($this->getGroupBoxH3("Companii pe Raioane:",$this->getRaions(0)));
		$this->show();
	}
	function actionViewCompany(){

		$c=new CoCompany();
		if ($c->loadById($this->id)){
			$c->count();
		}

		$this->setTitle('Companii din Moldova: '.$c->nume_scurt);
		$this->setDescription($c->nume_lung);
		$this->setLeftContainer($this->getGroupBoxH3("Menu:",$this->getMenu()));
		$this->setCenterContainer($this->getGroupBoxH1($this->getCompanyTitle($c),$this->getCompanyDetails($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a id="2"></a>Adresa:',$this->getAdress($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a id="3"></a>Lista Conducatorilor si Fondatorilor:',$this->getListConducatorilorFondatorilor($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a id="4"></a>Lista de Activitati Licentiate:',$this->getCompanyActivitatiLicentiate($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a id="5"></a>Lista de Activitati Nelicentiate:',$this->getCompanyActivitatiNelicentiate($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a id="6"></a>Alte Date:',$this->getSystemDetails($c)));
		
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$c='<a id="7"></a>Forum/Comentarii:';
		$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'i',$this->id)));
		
		$this->show();
	}
	function actionViewNotLicencedActivityCompaniesByRaion(){
	
		$c=new CoCaemNL();
		$c->loadById($this->id);
		
		$r=new Raion();
		$r->loadById($this->rid);
		
		$title="Lista companiilor din ".$r->getFullNameDescription()." care practica activitatea nelicentiata de tip \"".$c->denumire."\"";
		$this->setTitle($title);
		$this->setDescription($title);
		$this->setLeftContainer($this->getGroupBoxH3("Menu:",$this->getMenu()));
		$this->setCenterContainer($this->getGroupBoxH1($this->getActivityTitle($title),$this->getNotLicencedActivityCompaniesByRaion($c->id,$r->id)));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->showList();
	}
	function actionViewNotLicencedActivityCompaniesByLocalitate(){
	
		$c=new CoCaemNL();
		$c->loadById($this->id);
	
		$l=new Location();
		$l->loadById($this->lid);
	
		$title="Lista companiilor din ".$l->getFullNameDescription()." care practica activitatea nelicentiata de tip \"".$c->denumire."\"";
		$this->setTitle($title);
		$this->setDescription($title);
		$this->setLeftContainer($this->getGroupBoxH3("Menu:",$this->getMenu()));
		$this->setCenterContainer($this->getGroupBoxH1($this->getActivityTitle($title),$this->getNotLicencedActivityCompaniesByLocalitate($c->id,$l->id)));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->showList();
	}	
	function actionViewLicencedActivityCompaniesByRaion(){
	
		$c=new CoCaemL();
		$c->loadById($this->id);
	
		$r=new Raion();
		$r->loadById($this->rid);
	
		$title="Lista companiilor din ".$r->getFullNameDescription()." care practica activitatea licentiata de tip \"".$c->denumire."\"";
		$this->setTitle($title);
		$this->setDescription($title);
		$this->setLeftContainer($this->getGroupBoxH3("Menu:",$this->getMenu()));
		$this->setCenterContainer($this->getGroupBoxH1($this->getActivityTitle($title),$this->getLicencedActivityCompaniesByRaion($c->id,$r->id)));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->showList();
	}
	function actionViewLicencedActivityCompaniesByLocalitate(){
	
		$c=new CoCaemL();
		$c->loadById($this->id);
	
		$l=new Location();
		$l->loadById($this->lid);
	
		$title="Lista companiilor din ".$l->getFullNameDescription()." care practica activitatea licentiata de tip \"".$c->denumire."\"";
		$this->setTitle($title);
		$this->setDescription($title);
		$this->setLeftContainer($this->getGroupBoxH3("Menu:",$this->getMenu()));
		$this->setCenterContainer($this->getGroupBoxH1($this->getActivityTitle($title),$this->getLicencedActivityCompaniesByLocalitate($c->id,$l->id)));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->showList();
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
	function showList($out=''){
		$out="";
		$out.='<div id="container">';
// 		$out.='<div id="left" class="container left" style="width:198px;">';
// 		$out.=$this->getLeftContainer();
// 		$out.='</div>';
		$out.='<div id="center" class="container center" style="width:996px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
// 		$out.='<div id="right" class="container right" style="width:198px;">';
// 		$out.=$this->getRightContainer();
// 		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}	
	function getMenu(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("companies.php","action=viewcompany&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("companies.php","action=viewcompany&id=".$this->id."#2").'">Adresa</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("companies.php","action=viewcompany&id=".$this->id."#3").'">Lista Conducatori</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("companies.php","action=viewcompany&id=".$this->id."#3").'">Lista Fondatori</a></li>';	
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("companies.php","action=viewcompany&id=".$this->id."#4").'">Activitati Licentiate</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("companies.php","action=viewcompany&id=".$this->id."#5").'">Activitati Nelicentiate</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("companies.php","action=viewcompany&id=".$this->id."#6").'">Vizualizari</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("companies.php","action=viewcompany&id=".$this->id."#7").'">Forum/Comentariie</a></li>';		
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
	function getActivityTitle($c){
		$out='<a id="1"></a>'.$c;
		return $out;
	}	
	function getNotLicencedActivityCompaniesByRaion($activityid, $raionid){
		$p=new CoCompany();
		if ($raionid!=0){
			$ps=$p->getCompaniesByRaionAndNotLicencedActivity($raionid,$activityid,"2 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarCompaniesByRaionAndNotLicencedActivity($raionid,$activityid);
		}
	
		$table=new Table();
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($this->rowsperpage);
		$table->setPage($this->page);
		$navigationlink=function(){
			return $this->getUrlWithSpecialCharsConverted("companies.php","action=viewnotlicencedactivitycompaniesbyraion&rid=".$this->rid."&id=".$this->id);
		};
		$table->setNavigationLink($navigationlink);
	
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('companies.php','action=viewcompany&rid='.$this->id.'&id='.$row->id).'">'.$row->nume_scurt.'</a>';
		};
	
		$table->addField(new TableField(1, "Denumirea Companiei", "nume_scurt", "",$openlink));
		$table->addField(new TableField(2, "Data inregistrarii", "data_inregistrarii", "text-align: center;",""));
		$statusvalue=function($row){
			if ($row->statutul=="") {
				return "Activa";
			} else {
				return "Lichidata";
			}
		};		
		$table->addField(new TableField(3, "Statutul", "statutul", "text-align: center;",$statusvalue));
		
	
		$out=$table->show();
		return $this->getGroupBoxH1($this->list_title,$out);
		
	}
	function getNotLicencedActivityCompaniesByLocalitate($activityid, $localitateid){
		$p=new CoCompany();
		if ($localitateid!=0){
			$ps=$p->getCompaniesByLocalitateAndNotLicencedActivity($localitateid,$activityid,"2 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarCompaniesByLocalitateAndNotLicencedActivity($localitateid,$activityid);
		}
	
		$table=new Table();
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($this->rowsperpage);
		$table->setPage($this->page);
		$navigationlink=function(){
			return $this->getUrlWithSpecialCharsConverted("companies.php","action=viewnotlicencedactivitycompaniesbylocalitate&lid=".$this->lid."&id=".$this->id);
		};
		$table->setNavigationLink($navigationlink);
	
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('companies.php','action=viewcompany&lid='.$this->id.'&id='.$row->id).'">'.$row->nume_scurt.'</a>';
		};
	
		$table->addField(new TableField(1, "Denumirea Companiei", "nume_scurt", "",$openlink));
		$table->addField(new TableField(2, "Data inregistrarii", "data_inregistrarii", "text-align: center;",""));
		$statusvalue=function($row){
			if ($row->statutul=="") {
				return "Activa";
			} else {
				return "Lichidata";
			}
		};
		$table->addField(new TableField(3, "Statutul", "statutul", "text-align: center;",$statusvalue));
	
	
		$out=$table->show();
		return $this->getGroupBoxH1($this->list_title,$out);
	
	}	
	function getLicencedActivityCompaniesByRaion($activityid, $raionid){
		$p=new CoCompany();
		if ($raionid!=0){
			$ps=$p->getCompaniesByRaionAndLicencedActivity($raionid,$activityid,"2 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarCompaniesByRaionAndLicencedActivity($raionid,$activityid);
		}
	
		$table=new Table();
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($this->rowsperpage);
		$table->setPage($this->page);
		$navigationlink=function(){
			return $this->getUrlWithSpecialCharsConverted("activities.php","action=viewcompaniesbyraion&id=".$this->id);
		};
		$table->setNavigationLink($navigationlink);
	
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('companies.php','action=viewcompany&lid='.$this->id.'&id='.$row->id).'">'.$row->nume_scurt.'</a>';
		};
	
		$table->addField(new TableField(1, "Denumirea Companiei", "nume_scurt", "",$openlink));
		$table->addField(new TableField(2, "Data inregistrarii", "data_inregistrarii", "text-align: center;",""));
		$statusvalue=function($row){
			if ($row->statutul=="") {
				return "Activa";
			} else {
				return "Lichidata";
			}
		};
		$table->addField(new TableField(3, "Statutul", "statutul", "text-align: center;",$statusvalue));
	
	
		$out=$table->show();
		return $this->getGroupBoxH1($this->list_title,$out);
	
	}
	function getLicencedActivityCompaniesByLocalitate($activityid, $localitateid){
		$p=new CoCompany();
		if ($localitateid!=0){
			$ps=$p->getCompaniesByLocalitateAndLicencedActivity($localitateid,$activityid,"2 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarCompaniesByLocalitateAndLicencedActivity($localitateid,$activityid);
		}
	
		$table=new Table();
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($this->rowsperpage);
		$table->setPage($this->page);
		$navigationlink=function(){
			return $this->getUrlWithSpecialCharsConverted("activities.php","action=viewcompaniesbyraion&id=".$this->id);
		};
		$table->setNavigationLink($navigationlink);
	
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('companies.php','action=viewcompany&lid='.$this->id.'&id='.$row->id).'">'.$row->nume_scurt.'</a>';
		};
	
		$table->addField(new TableField(1, "Denumirea Companiei", "nume_scurt", "",$openlink));
		$table->addField(new TableField(2, "Data inregistrarii", "data_inregistrarii", "text-align: center;",""));
		$statusvalue=function($row){
			if ($row->statutul=="") {
				return "Activa";
			} else {
				return "Lichidata";
			}
		};
		$table->addField(new TableField(3, "Statutul", "statutul", "text-align: center;",$statusvalue));
	
	
		$out=$table->show();
		return $this->getGroupBoxH1($this->list_title,$out);
	
	}	
	function getCompanyTitle($c){
		$out='<a id="1"></a>'.$c->nume_lung;
		return $out;
	}
	function getCompanyDetails($c){
		$out='<table style="width:100%" >';	
		$out.='<tr><td>Denumirea Scurta:</td><td>'.$c->nume_scurt.'</td></tr>';
		$out.='<tr><td>Denumirea Lunga:</td><td>'.$c->nume_lung.'</td></tr>';
		$out.='<tr><td>Data inregistraii:</td><td>'.$c->data_inregistrarii.'</td></tr>';
		$out.='<tr><td>Cod Fiscal (IDNO):</td><td>'.$c->idno_cod_fiscal.'</td></tr>';
		$out.='<tr><td>Forma Juridica:</td><td>'.$c->forma_juridica.'</td></tr>';
		$out.='<tr><td>Statutul:</td><td>'.($c->statutul ==null?'Activa':'Lichidata').'</td></tr>';		
		$out.='</table>';
		return $out;
	}
	function getAdress($c){
		$out='<table style="width:100%" >';		
		$out.='<tr><td>Municipiu/Raion:</td><td><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewraion").'&id='.$c->getRaion()->id.'" >'.$c->getRaion()->getFullName().'</a></td></tr>';
		$out.='<tr><td>Oras/Sat:</td><td><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate").'&id='.$c->getLocation()->id.'" >'.$c->getLocation()->getFullName().'</a></td></tr>';
		$out.='<tr><td>Adresa Lunga:</td><td>'.$c->adresa.'</td></tr>';				
		$out.='</table>';		
 		return $out;
 	}
 	function getListConducatorilorFondatorilor($c){
 		$out='<table style="width:100%">';
 		$out.='<tr><td style="white-space: nowrap;">List Conducatorilor:</td><td>'.$c->lista_conducatorilor.'</td></tr>';
 		$out.='<tr><td style="white-space: nowrap;">List Fondatorilor:</td><td>'.$c->lista_fondatorilor.'</td></tr>';
 		$out.='</table>';
 		$out.='</fieldset>';
 		return $out;
 	}
 	function getCompanyActivitatiLicentiate($c){
 		$out='';
		$canl=new CoCaemL();
		$canls=$canl->getActivitatiByCompanie($c->id);
		$out.='<table>';
 		if (count($canls)!=0){
 			foreach($canls as $anl){
 				$out.='<tr><td>'.$anl->denumire.'</td></tr>';
 			}
 		} else {
 			$out.='<tr><td>Nu exista!</td></tr>';
 		}
 		$out.='</table>';
 		return $out;
 	}
 	function getCompanyActivitatiNelicentiate($c){
 		$out='';
 		$canl=new CoCaemNL();
 		$canls=$canl->getActivitatiByCompanie($c->id);
 		$out.='<table>';
 		if (count($canls)!=0){
 			foreach($canls as $anl){
 				$out.='<tr><td>'.$anl->denumire.'</td></tr>';
 			}
 		} else {
 			$out.='<tr><td>Nu exista!</td></tr>';
 		}
 		$out.='</table>';
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
			
	function getSystemDetails($c){
		$out='<div>';
		$out.='<div id="property-view-dateq1" style="float:left">';
		$out.='Data publicarii: '.$c->created_at;
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
