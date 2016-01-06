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
		}
		$this->setTitle($t);	
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$this->setCenterContainer($this->getGroupBoxH1($t,$this->getCompanyTypes()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		$this->setLeftContainer($this->getGroupBoxH3("Companii pe Raioane:",$this->getRaions(0)));
		$this->show();
	}

	
	function actionViewCompaniesByRaion(){
		if (isset($this->id)){
			$r=new Raion();
			$r->loadById($this->id);
			$this->list_title='Lista agentilor economici din '.$r->getFullNameDescription();
			$this->setTitle('Agenti Economici din Moldova: '.$this->list_title);
			$this->setDescription('Agenti Economici din Moldova: '.$this->list_title);
		} else {
			$this->id=0;
			$this->setTitle('Agenti Economici din Moldova: ');			
		}
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));		
		$rl=new RaionList();
		$rl->setRaionId($this->id);
		$raionlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewcompaniesbyraion&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setRaionLink($raionlink);
		$locationlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewcompaniesbylocation&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setLocationLink($locationlink);		
		$this->setLeftContainer($this->getGroupBoxH3("Agenti pe Raioane:",$rl->show()));
		$this->setCenterContainer($this->getCompaniesByRaion($this->id));		
		$this->show();
	}
	function actionViewCompaniesByLocation(){
		if (isset($this->id)){
			$l=new Location();
			$l->loadById($this->id);
			$this->list_title='Lista agentilor economici din '.$l->getFullNameDescription();
			$this->setTitle('Agenti Economici din Moldova: '.$this->list_title);
			$this->setDescription('Agenti Economici din Moldova: '.$this->list_title);
		} else {
			$this->id=0;
			$this->setTitle('Agenti Economici din Moldova: ');
		}
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$rl=new RaionList();
		$rl->setRaionId($this->id);
		$raionlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewcompaniesbyraion&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setRaionLink($raionlink);
		$locationlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewcompaniesbylocation&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setLocationLink($locationlink);
		$this->setLeftContainer($this->getGroupBoxH3("Agenti pe Raioane:",$rl->show()));
		$this->setCenterContainer($this->getCompaniesByLocation($this->id));
		$this->show();
	}	
	function actionViewActivitiesByRaion(){
		if (isset($this->id)){
			$r=new Raion();
			$r->loadById($this->id);
			$this->list_title='Lista activitatilor economice licentiate din '.$r->getFullNameDescription();
			$this->setTitle('Activitati Economice Licentiate din Moldova: '.$this->list_title);
			$this->setDescription('Activitati Economice Licentiate din Moldova: '.$this->list_title);
		} else {
			$this->id=0;
			$this->setTitle('Activitati Economice din Moldova: ');
		}
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$rl=new RaionList();
		$rl->setRaionId($this->id);
		$raionlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewactivitiesbyraion&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setRaionLink($raionlink);
		$locationlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewactivitiesbylocation&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setLocationLink($locationlink);
		$this->setLeftContainer($this->getGroupBoxH3("Activitati pe Raioane:",$rl->show()));
		$this->setCenterContainer($this->getActivitiesByRaion($this->id));
		$this->show();
	}
	function actionViewActivitiesByLocation(){
	
		if (isset($this->id)){
			$l=new Location();
			$l->loadById($this->id);
			$this->list_title='Lista activitatilor economice licentiate din '.$l->getFullNameDescription();
			$this->setTitle('Activitati Economice din Moldova: '.$this->list_title);
			$this->setDescription('Activitati Economice din Republica Moldova: '.$this->list_title);
		} else {
			$this->id=0;
			$this->setTitle('Activitati Economice din Moldova: ');
		}
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$rl=new RaionList();
		$rl->setLocationId($this->id);
		$raionlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewactivitiesbyraion&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setRaionLink($raionlink);
		$locationlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewactivitiesbylocation&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setLocationLink($locationlink);
	
		$this->setLeftContainer($this->getGroupBoxH3("Activitati pe Raioane:",$rl->show()));
		$this->setCenterContainer($this->getActivitiesByLocation($this->id));
		$this->show();
	}	
	function actionViewNotLicencedActivitiesByRaion(){
		if (isset($this->id)){
			$r=new Raion();
			$r->loadById($this->id);
			$this->list_title='Lista activitatilor economice nelicentiate din '.$r->getFullNameDescription();
			$this->setTitle('Activitati Economice Nelicentiate din Moldova: '.$this->list_title);
			$this->setDescription('Activitati Economice Nelicentiate din Moldova: '.$this->list_title);
		} else {
			$this->id=0;
			$this->setTitle('Activitati Economice din Moldova: ');
		}
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		$rl=new RaionList();
		$rl->setRaionId($this->id);
		$raionlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewnotlicencedactivitiesbyraion&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setRaionLink($raionlink);
		$locationlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewnotlicencedactivitiesbylocation&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setLocationLink($locationlink);		
		$this->setLeftContainer($this->getGroupBoxH3("Activitati pe Raioane:",$rl->show()));
		$this->setCenterContainer($this->getNotLicencedActivitiesByRaion($this->id));
		$this->show();
	}	
	function actionViewNotLicencedActivitiesByLocation(){
	
		if (isset($this->id)){
			$l=new Location();
			$l->loadById($this->id);
			$this->list_title='Lista activitatilor economice nelicentiate din '.$l->getFullNameDescription();
			$this->setTitle('Activitati Economice Nelicentiate din Moldova: '.$this->list_title);
			$this->setDescription('Activitati Economice Nelicentiate din Republica Moldova: '.$this->list_title);
		} else {
			$this->id=0;
			$this->setTitle('Activitati Economice din Moldova: ');
		}
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));		
		$rl=new RaionList();
		$rl->setLocationId($this->id);
		$raionlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewnotlicencedactivitiesbyraion&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setRaionLink($raionlink);
		$locationlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('activities.php','action=viewnotlicencedactivitiesbylocation&id='.$row->id).'">'.$row->getFullName().'</a>';
		};
		$rl->setLocationLink($locationlink);		
		$this->setLeftContainer($this->getGroupBoxH3("Activitati pe Raioane:",$rl->show()));
		$this->setCenterContainer($this->getNotLicencedActivitiesByLocation($this->id));
		$this->show();
	}	
	function show($out=''){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="left" class="container left" style="width:198px;">';
		$out.=$this->getLeftContainer();
		$out.='</div>';		
		$out.='<div id="center" class="container center" style="width:798px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
// 		$out.='<div id="right" class="container right" style="width:198px;">';
// 		$out.=$this->getRightContainer();
// 		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}			
	function getMenuLinks(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("activities.php","action=viewcompaniesbyraion&id=100").'">Lista Companiilor pe Localitati</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("activities.php","action=viewactivitiesbyraion&id=100").'">Lista Activitatilor Economice Licentiate</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("activities.php","action=viewnotlicencedactivitiesbyraion&id=100").'">Lista Activitatilor Economice Nelicentiate</a></li>';
		$out.='</ul>';
		return $out;
	}
	function getActivitiesByRaion($raionid){	
 		$p=new CoCaemL();
		if ($raionid!=0){
			$ps=$p->getActivitatiByRaion($raionid,"3 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarActivitatiByRaion($raionid);
		}		

		$table=new Table();
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($this->rowsperpage);
		$table->setPage($this->page);
		$navigationlink=function(){
			return $this->getUrlWithSpecialCharsConverted("activities.php","action=viewactivitiesbyraion&id=".$this->id);
		};
		$table->setNavigationLink($navigationlink);
	
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('companies.php','action=viewlicencedactivitycompaniesbyraion&rid='.$this->id.'&id='.$row->id).'">'.$row->denumire.'</a>';
		};
		
		$table->addField(new TableField(1, "Denumirea Activitatii", "denumire", "",$openlink));
		$table->addField(new TableField(2, "Numarul de activitati", "contor", "text-align: center;",""));
		
		$out=$table->show();
		return $this->getGroupBoxH1($this->list_title,$out);
	}
	function getActivitiesByLocation($locationid){
		$p=new CoCaemL();
		if ($locationid!=0){
			$ps=$p->getActivitatiByLocalitate($locationid,"3 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarActivitatiByLocalitate($locationid);
		}
	
		$table=new Table();
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($this->rowsperpage);
		$table->setPage($this->page);
		$navigationlink=function(){
			return $this->getUrlWithSpecialCharsConverted("activities.php","action=viewactivitiesbylocation&id=".$this->id);
		};
		$table->setNavigationLink($navigationlink);
	
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('companies.php','action=viewlicencedactivitycompaniesbylocalitate&lid='.$this->id.'&id='.$row->id).'">'.$row->denumire.'</a>';
		};
	
		$table->addField(new TableField(1, "Denumirea Activitatii", "denumire", "",$openlink));
		$table->addField(new TableField(2, "Numarul de activitati", "contor", "text-align: center;",""));
	
		$out=$table->show();
		return $this->getGroupBoxH1($this->list_title,$out);
	}	
	function getNotLicencedActivitiesByRaion($raionid){
		$p=new CoCaemNL();
		if ($raionid!=0){
			$ps=$p->getActivitatiByRaion($raionid,"3 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarActivitatiByRaion($raionid);
		}
	
		$table=new Table();
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($this->rowsperpage);
		$table->setPage($this->page);
		$navigationlink=function(){
			return $this->getUrlWithSpecialCharsConverted("companies.php","action=viewnotlicencedactivitycompaniesbyraion&rid=".$this->id);
		};
		$table->setNavigationLink($navigationlink);
	
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('companies.php','action=viewnotlicencedactivitycompaniesbyraion&rid='.$this->id.'&id='.$row->id).'">'.$row->denumire.'</a>';
		};
	
		$table->addField(new TableField(1, "Denumirea Activitatii", "denumire", "",$openlink));
		$table->addField(new TableField(2, "Numarul de activitati", "contor", "text-align: center;",""));
	
		$out=$table->show();
		return $this->getGroupBoxH1($this->list_title,$out);
	}
	function getNotLicencedActivitiesByLocation($locationid){
		$p=new CoCaemNL();
		if ($locationid!=0){
			$ps=$p->getActivitatiByLocalitate($locationid,"3 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarActivitatiByLocalitate($locationid);
		}
	
		$table=new Table();
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($this->rowsperpage);
		$table->setPage($this->page);
		$navigationlink=function(){
			return $this->getUrlWithSpecialCharsConverted("activities.php","action=viewnotlicencedactivitiesbylocation&id=".$this->id);
		};
		$table->setNavigationLink($navigationlink);
	
		$openlink=function($row){
			return '<a href="'.$this->getUrlWithSpecialCharsConverted('companies.php','action=viewnotlicencedactivitycompaniesbylocalitate&lid='.$this->id.'&id='.$row->id).'">'.$row->denumire.'</a>';
		};
	
		$table->addField(new TableField(1, "Denumirea Activitatii", "denumire", "",$openlink));
		$table->addField(new TableField(2, "Numarul de activitati", "contor", "text-align: center;",""));
	
		$out=$table->show();
		return $this->getGroupBoxH1($this->list_title,$out);
	}
	function getCompaniesByRaion($raionid){
		$p=new CoCompany();
		if ($raionid!=0){
			$ps=$p->getCompaniesByRaion($raionid,"2 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarCompaniesByRaion($raionid);
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
	function getCompaniesByLocation($locationid){
		$p=new CoCompany();
		if ($locationid!=0){
			$ps=$p->getCompaniesByLocalitate($locationid,"2 desc",$this->page,$this->rowsperpage);
			$cnt=$p->getNumarCompaniesByLocalitate($locationid);
		}
	
		$table=new Table();
		$table->setDataSet($ps);
		$table->setRowsCount($cnt);
		$table->setRowsPerPage($this->rowsperpage);
		$table->setPage($this->page);
		$navigationlink=function(){
			return $this->getUrlWithSpecialCharsConverted("activities.php","action=viewcompaniesbylocation&id=".$this->id);
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
}
$n=new CompaniesWebPage();

?>
