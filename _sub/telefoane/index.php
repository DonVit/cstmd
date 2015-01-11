<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 class TelefoaneWebPage extends MainWebPage {
	public $rowsperpage=10;
	public $page;
	function __construct(){
		parent::__construct();
		//$this->setCSS("style/styles.css");
		//$t="COMPANII, TIPURI COMPANII DIN REPUBLICA MOLDOVA";
		//$this->setTitle($t);
		//$this->setLogoTitle($t);
		$t="PREFIXE, TELEFOANE DIN REPUBLICA MOLDOVA";
		$this->setLogoTitle($t);		
		$this->create();		
	}
	function actionDefault(){
		$t="LISTA PREFIXELOR TELEFONICE PE MUNICIPII SI RAIOANE";
		if (isset($this->id)){
			$this->redirect($this->getUrl(Config::$telefoanesite.'/index.php','action=viewprefixbylocation&id='.$this->id));
		}
		$this->setTitle($t);	
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyTypeList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getGroupBoxH1($t,$this->getMain()));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("Prefix dupa Localitate:",$this->getSearchPrefixByLocation()));
		$this->setRightContainer($this->getGroupBoxH3("Localitate dupa Telefon:",$this->getSearchLocationByTelefone()));		
		$this->setLeftContainer($this->getGroupBoxH3("Prefixe pe Localitati:",$this->getRaions(0)));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getNewsCompanies()));		
		$this->show();
	}

	function actionViewPrefixByRaion(){
		$t="Prefixele Telefonice ale Localitatilor";
		if (isset($this->id)){
			$r=new Raion();
			$r->loadById($this->id);
			$t.=" din ".$r->getFullNameDescription();
			$this->setTitle($t);
			$this->setDescription($t);
		} else {
			$this->id=0;
			$this->setTitle($t);			
		}
		//$this->setTitle($t);
		//$this->setBodyTag('<body onload="SmallViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));			

		
		//$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyTypeList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getGroupBoxH1($t,$this->getPrefixByRaion($this->id)));
		//$this->setCenterContainer($this->getGroupBoxH3("Adresa:",$this->getCompanyAddress($c)));
		//$this->setCenterContainer($this->getGroupBoxH3("Contacte:",$this->getCompanyContacts($c)));
		//$this->setCenterContainer($this->getGroupBoxH3("Pozitia pe Harta:",$this->getMap($c)));
		//$this->setCenterContainer($this->getGroupBoxH3("Ştiri ce aparţin acestei surse:",$this->getCompanyNews($c)));		
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("Prefix dupa Localitate:",$this->getSearchPrefixByLocation()));
		$this->setRightContainer($this->getGroupBoxH3("Localitate dupa Telefon:",$this->getSearchLocationByTelefone()));

		$this->setLeftContainer($this->getGroupBoxH3("Prefixe pe Localitati:",$this->getRaions($this->id)));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getNewsCompanies()));		
		$this->show();
	}
	function actionViewPrefixByLocation(){
		$t='Prefixe, Telefoane din ';
		if (isset($this->id)){
			$l=new Location();
			$l->loadById($this->id);
			$t.=$l->getFullNameDescription();
			$t.=', '.$l->getRaion()->getFullNameDescription();
			$this->setTitle($t);
			$this->setDescription($t);
		} else {
			$t.='Moldova';
			$this->id=0;
			$this->setTitle($t);
			$this->setDescription($t);
		}

		//$this->setBodyTag('<body onload="SmallViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=".Config::getMapKey($this->getServerName()));			

		
		//$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getMenuLinks()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyTypeList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRaions($this->raion)));				
		$this->setCenterContainer($this->getGroupBoxH1($t,''));
		$this->setCenterContainer($this->getGroupBoxH3("Prefixe:",$this->getPrefixByLocation($this->id,$this->page)));		
		$this->setCenterContainer($this->getGroupBoxH3("Telefoane:",$this->getCompaniesByLocation($this->id,$this->page)));
		//$this->setCenterContainer($this->getGroupBoxH3("Adresa:",$this->getCompanyAddress($c)));
		//$this->setCenterContainer($this->getGroupBoxH3("Contacte:",$this->getCompanyContacts($c)));
		//$this->setCenterContainer($this->getGroupBoxH3("Pozitia pe Harta:",$this->getMap($c)));
		//$this->setCenterContainer($this->getGroupBoxH3("Ştiri ce aparţin acestei surse:",$this->getCompanyNews($c)));		
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("Prefix dupa Localitate:",$this->getSearchPrefixByLocation()));
		$this->setRightContainer($this->getGroupBoxH3("Localitate dupa Telefon:",$this->getSearchLocationByTelefone()));
		
		//$this->setLeftContainer($this->getGroupBoxH3("Companii pe Raioane:",$this->getLocations($this->id)));
		$this->setLeftContainer($this->getGroupBoxH3("Prefixe pe Localitati:",$this->getLocations($this->id)));
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getNewsCompanies()));		
		$this->show();
	}						
	function _show($out=''){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="left" class="container left" style="width:698px;">';
		$out.=$this->getLeftContainer();
		$out.='</div>';		
// 		$out.='<div id="center" class="container center" style="width:600px;">';
// 		$out.=$this->getCenterContainer();
// 		$out.='</div>';
		$out.='<div id="right" class="container right" style="width:298px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
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
		$cs=$c->getAll("","`name`");
		//$out="<div class=\"groupbox\">";
		$out='';
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
			$ps=$p->getAll("valid=1 and company_type_id=".$type,"created_date desc",$page,$this->rowsperpage);
		} else {
			$ps=$p->getAll("valid=1","created_date desc",$page,$this->rowsperpage);
		}		
		$out='';
		foreach($ps as $p){
	
			$o="<table width=\"100%\"><tr><td align=\"center\">";
			$o.="<table  style=\"width: 100%\" class=\"source\">";
			$img=Image::getMainImageByRefType('f', $p->id);
			if ($img!=""){
				$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" align="right" valign="top"><img src="data/t'.$img->imagepath.'" alt="'.$img->imagenote.'" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
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
	function getCompanyTypes1(){
		$c=new CompanyType;
		$cs=$c->getAll("","`name`");
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
	function getCompaniesByRaion($raionid,$page){	
		$p=new Company();
		if ($raionid!=0){
			$ps=$p->getAll("valid=1 and raion_id=".$raionid,"created_date desc",$page,$this->rowsperpage);
		} else {
			$ps=$p->getAll("valid=1","created_date desc",$page,$this->rowsperpage);
		}		
		$out='';
		foreach($ps as $p){
	
			$o="<table width=\"100%\"><tr><td align=\"center\">";
			$o.="<table  style=\"width: 100%\" class=\"source\">";
			$img=Image::getMainImageByRefType('f', $p->id);
			if ($img!=null){
				$o.='<tr><td style="width: 20%">Descriere:</td><td style="text-align: justify">'.nl2br($p->text).'</td><td rowspan="7" align="right" valign="top"><img src="data/t'.$img->imagepath.'" alt="'.$img->imagenote.'" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
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
			$out.='<table width="100%"><tr><td align="center">';	
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
	function getPrefixByRaion($raionid){
		$sql="SELECT l.id, l.raion_id, name, prefix FROM localitate l INNER JOIN prefix p ON l.id = p.localitate_id where raion_id=".$raionid." order by oras desc, name";
		$l=new Location();
		$ls=$l->getAll("raion_id=".$raionid,"oras desc, name");
		$c=1;
		if (count($ls)!=0){
			$out='<div class="groupboxtable">';
			$out.='<table style="width:100%;">';
			$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>Denumire localitate</th><th>Prefix</th></th></tr>";
			foreach($ls as $l){
				$ps=$l->getPrefixes();
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewprefixbylocation&id=".$l->id);				
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->getFullNameDescription().'</a></td><td style="text-align:center;">';
				foreach($ps as $p){
					$out.='0-'.$p->prefix.'<br>';
				}
				$out.='</td></tr>';
				$c=$c+1;
			}
			$out.="</table>";
			$out.="</div>";
		}
		return $out;
	}	
	function getPrefixByLocation1($locationid,$page){	
		$p=new Prefix();
		//$p->loadById($locationid);
		$out='xcv';
		if ($p->loadById($locationid)){
			$out.='Prefixul:'.$p->prefix;
		}
		return $out;
	}
	function getPrefixByLocation($locationid,$page){
		$p=new Prefix();
		if ($locationid!=0){
			$ps=$p->getAll("localitate_id=".$locationid);
		} else {
			$ps=$p->getAll("valid=1","created_date desc",$page,$this->rowsperpage);
		}
		$out='';
		if (count($ps)!=0){
			foreach($ps as $p){
				$out.='<h3>0-'.$p->prefix.'</h3>';
			}
 		}
		return $out;
	}			
	function getCompanyTitle($c){
		$out='<a name="1"></a><h1 style="text-align: center;">'.$c->name.'</h1>';
		return $out;
	}
	function getCompanyDetails($c){

		//$out='<h2 style="text-align:left;border-bottom:2px #777777 solid; padding:2px;"><a href="'.$this->getBaseName().'?id='.$c->id.'" target="_self" class="sourcetitle">'.$c->name.'</a></h2>';

		$out='<table width="100%">';	
		$i=Image::getMainImageByRefType('f', $c->id);
		//if ($i!=null){
		//	$out.='<table align="left" style="margin-right:8px;"><tr><td class="imageborder"><a href="'.System::getURLAmpReplace($n->image_url).'" class="imga"><img src="data/t'.$i->imagepath.'" alt="'.$i->imagenote.'" class="imageborder"/></a><p class="news_image_tag"><a href="'.System::getURLAmpReplace($n->image_url).'" target="_blank">'.System::getDomainFromURL($n->image_url).'</a></p></td></tr></table>';
		//}			
		if ($i!=null){
			$out.='<tr><td style="width:20%">Descriere:</td><td style="text-align: justify;vertical-align: top;">'.nl2br($c->text).'</td><td align="right" valign="top"><img src="data/t'.$i->imagepath.'" alt="'.$i->imagenote.'" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
		} else {
			$out.='<tr><td style="width:20%">Descriere:</td><td style="text-align: justify;vertical-align: top;">'.nl2br($c->text).'</td><td align="right" valign="top"><img src="'.Config::$mainsite.'/common/img/no_image_100x100.jpg" style1="border: 2px solid #C3D9FF;padding:5px;" class="imageborder"/></td></tr>';
		}
		$out.='</table>';		
		$out.='<table width="100%">';		
		$out.='<tr><td style="width:20%">Tip Companie:</td><td><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewcompanies&id=".$c->company_type_id).'" target="_self">'.$c->getCompanyType()->name.'</a></td></tr>';		
		$out.='</table>';		
		
		return $out;
	}
	function getCompanyAddress1($c){
		$out='<table width="100%">';		
		$out.='<tr><td style="width:20%">Municipiu/Raion:</td><td><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewraion").'&id='.$c->getRaion()->id.'" >'.$c->getRaion()->getFullName().'</a></td></tr>';
		$out.='<tr><td>Oras/Sat:</td><td><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate").'&id='.$c->getLocalitate()->id.'" >'.$c->getLocalitate()->getFullName().'</a></td></tr>';
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
					$out.='<td><div><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$newssite."/index.php","action=viewnews&id=".$n->id).'"><img src="'.Config::$mainsite.'/common/img/no_image_100x100.jpg" alt="no image" style="width:120px;"/><p>'.$n->title.'</p></a></div></td>';
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
 	function getMain(){
		$r=new Raion();
		$rs=$r->getAll("","municipiu desc, `order`, name");
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;">';
		$out.="<tr><th>".$this->getConstants("IndexLocationsWebPageRaioaneNr")."</th><th>".$this->getConstants("IndexLocationsWebPageRaioaneName")."</th><th>Prefix</th></tr>";		
		if (count($rs)!=0){			
			$c=1;
			foreach($rs as $r){
				$ps=$r->getPrefixes();
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewprefixbyraion&id=".$r->id);
				//$out.='<li style="font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewcompaniesbyraion&id='.$r->id.'">'.$r->getFullName().'</a></li>';
				//$out.='<li style="font-weight:bold;"><a href="'.$url.'">'.$r->getFullName().'</a></li>';
				
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$r->getFullNameDescription().'</a></td><td>';
				foreach($ps as $p){
					$out.='0-'.$p->prefix.'<br>';
				}
				$out.='</td></tr>';
				$c=$c+1;	
			}
		}
		$out.="</table>";
		$out.="</div>";		
		
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
	function getRaions($raionid){		
		$r=new Raion();
		$rs=$r->getAll("","municipiu desc");
		$out="<ul>";
		foreach($rs as $r){	
			if ($r->id==$raionid){
				
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewprefixbyraion&id=".$r->id);
				//$out.='<li style="font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewcompaniesbyraion&id='.$r->id.'">'.$r->getFullName().'</a></li>';
				$out.='<li style="font-weight:bold;"><a href="'.$url.'">'.$r->getFullName().'</a></li>';
				$out.=$this->getLocationsByRaion($raionid);
			} else {
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewprefixbyraion&id=".$r->id);
				//$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewcompaniesbyraion&id='.$r->id.'">'.$r->getFullName().'</a></li>';
				$out.='<li><a href="'.$url.'">'.$r->getFullName().'</a></li>';
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
				$out.='<li style="list-style-type: circle;font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewprefixbylocation&id='.$l->id.'">'.$l->getFullName().'</a></li>';
			} else {
				$out.='<li style="list-style-type: circle;"><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewprefixbylocation&id='.$l->id.'">'.$l->getFullName().'</a></li>';
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
				$out.='<li style="font-weight:bold;"><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewprefixbyraion&id='.$r->id.'">'.$r->getFullName().'</a></li>';			
				$out.=$this->getLocationsByRaion($l->raion_id,$locationid);
			} else {
				$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=viewprefixbyraion&id='.$r->id.'">'.$r->getFullName().'</a></li>';
			}
		}
		$out.="</ul>";
		return $out;
	}				
	function getSearchPrefixByLocation(){
		$lsrs="";
	
		if ((!empty($_POST['searchprefixformpost']))&&(isset($this->lsearch))){
			$l=new Location();
			$r=new Raion();
			$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` WHERE `localitate`.deleted=0 AND `localitate`.name like '%".$this->lsearch."%' LIMIT 0,30");
			if (!is_null($ls)){
				foreach ($ls as $v){
					$l->loadById($v->location_id);
					$r->loadById($l->raion_id);
					$lurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewprefixbylocation&id=".$l->id);
					$lsrs.="<a href=".$lurl." target=\"_blank\">".$r->getFullName()."->".$l->getFullName()."</a><br>";
				}
			}else {
				$lsrs="Localitati *".$this->lsearch."* nu exista!";
			}
		}
		$out='<div id="prefixByLocation">';
		$out.='<form id="searchprefixform" name="searchprefixform" method="post">';
		$out.='<div id="prefix-search-box"><input type="text" name="lsearch" value="'.(isset($this->lsearch)?$this->lsearch:'').'"><br><input type="submit" name="searchprefixformpost" class="button" style="width:60px;" value="Cauta"><br>'.$lsrs.'</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}
	function getSearchLocationByTelefone(){
		$lsrs="";
	
		if ((!empty($_POST['searchlocationformpost']))&&(isset($this->psearch))){
			if (strlen($this->psearch)==9) {
				
				$l=new Location();
				$r=new Raion();
				$phonenumber=substr($this->psearch,1,8);
				//$phonenumber[0]='';
				$i=8;
				$ls=null;
				do {
				$i=$i-1;
				$phonenumber[$i]='X';
				//$lsrs.=$i."-[".$phonenumber."]"; 
				//$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` WHERE `localitate`.deleted=0 AND `localitate`.name like '%".$this->lsearch."%' LIMIT 0,30");
				//$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` INNER JOIN prefix on localitate.id=prefix.localitate_id WHERE `localitate`.deleted=0 AND MATCH (prefix) AGAINST('*".$this->psearch."*'  IN BOOLEAN MODE) LIMIT 0,30");
				//$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` WHERE `localitate`.deleted=0 AND `localitate`.name = '".$phonenumber."' LIMIT 0,30");
				$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` INNER JOIN prefix on localitate.id=prefix.localitate_id WHERE `localitate`.deleted=0 AND prefix = '".$phonenumber."' LIMIT 0,30");
				//if (!is_null($ls)){
				//	$lsrs.="-".count($ls);
				//}
				//$lsrs.="<br>";
				} while (($i>3)&&(is_null($ls)));
				//} while ($i>3);
				if (!is_null($ls)){
				//if (false){
					foreach ($ls as $v){
						$l->loadById($v->location_id);
						$r->loadById($l->raion_id);
						$lurl=$this->getUrlWithSpecialCharsConverted("index.php","action=viewprefixbylocation&id=".$l->id);
						$lsrs.="<a href=".$lurl." target=\"_blank\">".$r->getFullName()."->".$l->getFullName()."</a><br>";
					}
				}else {
					$lsrs.="Localitati cu prefixul *".$this->psearch."* nu exista!";
				}
			} else {
				$lsrs.="Numarul de telefon trebuie sa fie exact din 9 cifre! ca ex. 024977263";
			}
		}
		$out='<div id="locationByTelefone">';
		$out.='<form id="searchlocationform" name="searchlocationform" method="post">';
		$out.='<div id="location-search-box"><input type="text" name="psearch" value="'.(isset($this->psearch)?$this->psearch:'').'"><br><input type="submit" name="searchlocationformpost" class="button" style="width:60px;" value="Cauta"><br>'.$lsrs.'</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}	
	function getCompaniesByLocation($locationid,$page){
		$p=new Company();
		if ($locationid!=0){
			$ps=$p->getAll("valid=1 and localitate_id=".$locationid,"created_date desc",$page,$this->rowsperpage);
		} else {
			$ps=$p->getAll("valid=1","created_date desc",$page,$this->rowsperpage);
		}
		$o='';
		//
		$c=new Company();
		$cs=$c->doSql("SELECT companyid, concat_ws(', ',y.name,z.new_name,k.new_name) as name,subdivizionid,subsectorid,phoneprefix,phonenumber FROM `xsubcompany` x inner join xcompany y on x.companyid=y.id left join xsubdivizion z on x.subdivizionid=z.id left join xsubsector k on x.subsectorid=k.id WHERE `localitateid`=".$locationid." order by companyid limit 0,100");
		$o.='<div class="groupboxtable">';
		$o.='<table width="100%">';
		$o.='<tr><th style="text-align:center;width:20%">Nr. Telefon</th><th style="text-align:center;">Compania</th></tr>';
		if (count($ps)!=0){
			foreach($ps as $p){
				$o.='<tr>';
				$o.='<td style="text-align:center;">'.$p->phone.'</td>';
				$o.='<td><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite."/index.php").'&action=viewcompany&id='.$p->id.'">'.$p->name.'</a></td>';
				$o.='</tr>';
			}
		}
		if (count($cs)!=0){
			foreach($cs as $c){
				$o.='<tr>';
				$o.='<td style="text-align:center;">0-'.$c->phoneprefix.'-'.$c->phonenumber.'</td>';
				//$o.='<td><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$companiesite."/index.php").'&action=viewcompany&id='.$c->companyid.'">'.$c->name.'</a></td>';
				$o.='<td>'.$c->name.'</td>';
				$o.='</tr>';
			}
		}				
		$o.="</table>";
		$o.="</div>";

		return $o;
	}
}
$n=new TelefoaneWebPage();

?>
