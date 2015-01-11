<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 class CompaniesWebPage extends MainWebPage {
	public $rowsperpage=10;
	function __construct(){
		parent::__construct();
		//$this->setCSS("style/styles.css");
		//$t="COMPANII, TIPURI COMPANII DIN REPUBLICA MOLDOVA";
		//$this->setTitle($t);
		//$this->setLogoTitle($t);
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
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyTypeList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getGroupBoxH1($t,$this->getCompanyTypes()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getNewsCompanies()));		
		$this->show();
	}
	function actionViewCompany(){

		$c=new Company();
		if ($c->loadById($this->id)){
			$c->count();
		}
		Logger::setLogs($c);
		$this->setBodyTag('<body onload="SmallViewOnMapLoad()" onunload="GUnload()">');
		$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));			

		$this->setTitle('Companii in Moldova: '.$c->name);
		$this->setDescription($c->description);
		
		//$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3("Menu:",$this->getMenu()));
		
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyTypeList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRaions($this->raion)));				
		//$this->setCenterContainer($this->getGroupBoxH3($this->getCompanyTitle($c)));
		$this->setCenterContainer($this->getGroupBoxH1($this->getCompanyTitle($c),$this->getCompanyDetails($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a name="2"></a>Adresa:',$this->getCompanyAddress($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a name="3"></a>Contacte:',$this->getCompanyContacts($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a name="4"></a>Pozitia pe Harta:',$this->getMap($c)));
		$this->setCenterContainer($this->getGroupBoxH3('<a name="5"></a>Ştiri ce aparţin acestei surse:',$this->getCompanyNews($c)));		
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getNewsCompanies()));		
		$this->show();
	}		
	function actionViewCompanies(){

		if (isset($this->id)){
			$ct=new CompanyType();
			$ct->loadById($this->id);
			$this->setTitle('Companii in Moldova: '.$ct->name);
			$this->setDescription($ct->description);
		} else {
			$this->setTitle('Companii in Moldova: ');			
		}

		//$this->setBodyTag('<body onload="SmallViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));			

		
		//$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyTypeList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getCompanies($this->id,$this->page));
		//$this->setCenterContainer($this->getGroupBoxH3("Adresa:",$this->getCompanyAddress($c)));
		//$this->setCenterContainer($this->getGroupBoxH3("Contacte:",$this->getCompanyContacts($c)));
		//$this->setCenterContainer($this->getGroupBoxH3("Pozitia pe Harta:",$this->getMap($c)));
		//$this->setCenterContainer($this->getGroupBoxH3("Ştiri ce aparţin acestei surse:",$this->getCompanyNews($c)));		
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getNewsCompanies()));		
		$this->show();
	}			
	function show(){
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
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompany&id=".$this->id."#5").'">Stiri</a></li>';		
		$out.='</ul>';
		return $out;
	}
	function getMenuLinks(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("add.php").'">Adauga Companie</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Lista Tipuri Companii</a></li>';		
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies").'">Lista Companii</a></li>';		
		$out.='</ul>';
		return $out;
	}
	function getRssLink(){
		$out='<ul>';
		$out.='<li><a href="rss.php">Companii in RSS <img src="'.Config::$mainsite.'/common/img/rss.png" alt="Companii in format RSS" title="Comapnii in format RSS"/></a></li>';
		$out.='</ul>';
		return $out;
	}
	function getCompanyTypes(){
		$c=new CompanyType;
		$cs=$c->getAll("","`order`");
		//$out="<div class=\"groupbox\">";
		//$out.="<ul class=\"leftmenulist\">";
		foreach ($cs as $c){
			//$out.='<li><a href="'.$this->getBaseName().'?view=companies&type='.$c->id.'">'.$c->name.'</a></li>';
			$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies&id=".$c->id);
			$out.='<h3><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies&id=".$c->id).'">'.$c->name.'</a></h3>';
			//$out.='<h3><a href="'.$this->getBaseName().'?view=companies&type='.$c->id.'">'.$c->name.'</a></h3>';
		
		}
		//$out.="</ul>";
		//$out.="</div>";
		return $out;
	}	
	function getCompanies($type,$page){
		$p=new Company();
		if ($type!=0){
			$ps=$p->getAll("valid=1 and company_type_id=".$type,"created_date desc",$page*$this->rowsperpage,$this->rowsperpage);
		} else {
			$ps=$p->getAll("valid=1","created_date desc",$page*$this->rowsperpage,$this->rowsperpage);
		}		
		$out='';
		foreach($ps as $p){
	
			$o="<table width=\"100%\"><tr><td align=\"center\">";
			$o.="<table  style=\"width: 100%\" class=\"source\">";
			if ($p->logo_file!=""){
				$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" align="right" valign="top"><img src="files/t'.$p->logo_file.'" alt="'.$p->logo_description.'" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
			} else {
				$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" align="right" valign="top"><img src="'.Config::$mainsite.'/common/img/no_image_100x100.jpg" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
			}
			$o.="<tr><td>Adresa:</td><td>".$p->getAdresa()."</td></tr>";
			$o.="<tr><td></td><td></td></tr>";
			$o.='<tr><td colspan="2"><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewcompany&id='.$p->id.'">vezi mai multe detalii aici</a></td></tr>';
			$o.="</table>"; 			
			$o.="</td></tr></table>";
			$h='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewcompany&id='.$p->id.'">'.$p->name.'</a>';			
			//$f='<a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewcompany&id='.$p->id.'">vezi mai mult detalii aici</a>';
			$out.=$this->getGroupBoxH1($h,$o);	
		}
		$sql="select count(*) as cnt from company where valid=1";
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
			$out.='<table width="100%"><tr><td align="center">';	
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
	function getCompanyTitle($c){
		$out='<a name="1"></a><h1 style="text-align: center;">'.$c->name.'</h1>';
		return $out;
	}
	function getCompanyDetails($c){

		//$out='<h2 style="text-align:left;border-bottom:2px #777777 solid; padding:2px;"><a href="'.$this->getBaseName().'?id='.$c->id.'" target="_self" class="sourcetitle">'.$c->name.'</a></h2>';

		$out.='<table width="100%">';		
		if ($c->logo_file!=""){
			$out.='<tr><td style="width:20%">Descriere:</td><td style="text-align: justify;vertical-align: top;">'.nl2br($c->text).'</td><td align="right" valign="top"><img src="files/t'.$c->logo_file.'" alt="'.$c->logo_description.'" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
		} else {
			$out.='<tr><td style="width:20%">Descriere:</td><td style="text-align: justify;vertical-align: top;">'.nl2br($c->text).'</td><td align="right" valign="top"><img src="'.Config::$mainsite.'/common/img/no_image_100x100.jpg" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
		}
		$out.='</table>';		
		$out.='<table width="100%">';		
		$out.='<tr><td style="width:20%">Tip Companie:</td><td><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies&id=".$c->company_type_id).'" target="_self">'.$c->getCompanyType()->name.'</a></td></tr>';		
		$out.='</table>';		
		
		return $out;
	}
	function getCompanyAddress($c){
		$out='<table width="100%">';		
		$out.='<tr><td style="width:20%">Municipiu/Raion:</td><td>'.$c->getRaion()->name.'</td></tr>';
		$out.='<tr><td>Oras/Sat:</td><td>'.$c->getLocalitate()->name.'</td></tr>';
		$out.='<tr><td>Sector/Regiune:</td><td>'.$c->getSector()->name.'</td></tr>';				
		$out.='<tr><td>Strada:</td><td>'.$c->strada.'</td></tr>';
		$out.='</table>';		
		$out.='</fieldset>';
 		return $out;
 	}	
	function getCompanyContacts($c){
		//$out.='<fieldset id="contacts">';
		//$out.='<legend>Contacte:</legend>';
		$out='<table width="100%">';
		$out.='<tr><td style="width:20%">Telefon:</td><td>'.$c->phone.'</td></tr>';
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
	function getCompanyNews($c){
		//$sql="SELECT id, title, image_file, image_url, image_description FROM `news`WHERE news.valid=1 AND `company_id`=$id AND `image_file` != \"\" AND id!=$id ORDER BY `date` DESC LIMIT 0,8 ";
		//$n=new News;
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
				//$out.="<td class=\"newsgroup_td\"><div><a href=\"".$this->getBaseName()."?id=$n->id\"><img src=\"".Config::$imagessite."/thumbs/".$n->image_file."\" alt=\"".$n->image_description."\" class=\"newsgroup_img\"/><p class=\"newsgroup_p\">$n->title</p></a></div></td>";
				$out.='<td><div><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$newssite."/index.php","action=viewnews&id=".$n->id).'"><img src="'.Config::$newssite.'/images/'.$n->image_file.'" alt="'.$n->image_description.'" style="width:120px;"/><p>'.$n->title.'</p></a></div></td>';
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
	function getMain1(){
		$out="";
		if (isset($this->view)){
			$rowsperpage=12;
			$out.=$this->getCompanies($this->type,$this->page,$rowsperpage);
		}elseif (isset($this->id)){
			$out.=$this->getCompanyById($this->id);	
			$out.=$this->getNewsByNewsCompany($this->id);		
		} else {
			$out.=$this->getCompanyTypes();
		}		
		return $out;
	}		
	function getCenterContainer1(){
		$out="";
		if (isset($this->view)){
			$rowsperpage=12;
			$out.=$this->getCompanies($this->type,$this->page,$rowsperpage);
		}elseif (isset($this->id)){
			$out.=$this->getCompanyById($this->id);	
			$out.=$this->getNewsByNewsCompany($this->id);		
		} else {
			$out.=$this->getCompanyTypes();
		}		
		return $out;
	}
	function getRightContainer1(){
		$out="";
		$out.=$this->getRssLink();
		return $out;
	}					
	function getCompanyList1(){
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php?view=companies">Lista Companii</a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $out;
	}
	function getCompanyTypeList1(){
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php">Lista Tipuri Companii</a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $out;
	}			

}
$n=new CompaniesWebPage();

?>
