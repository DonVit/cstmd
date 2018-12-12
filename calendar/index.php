<?php
require_once(__DIR__ . '/../main/loader.php');


class IndexLocationsWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setCSS("style/maps.css");
		$this->setLogoTitle("CALENDAR IN REPUBLICA MOLDOVA");

		if (isset($this->location_id)){
			$l=new Location();
			$l->loadById($this->location_id);
			$this->location=$l;
			$r=new Raion();
			$r->loadById($this->location->raion_id);
			$this->raion=$r;
		} else{
			$r=new Raion();
			$this->raion=$r;
			$this->raion->loadById(Raion::getTopFirstRaion()->id);
			$l=new Location();
			$l->loadById(Location::getTopFirstLocationByRaionId($this->raion->id)->id);
			$this->location=$l;
		}
		
		Logger::setLogs($this->raion);
		Logger::setLogs($this->location);				
		
		$this->create();		
	}
	function actionDefault(){
		$this->setTitle('Calendar Moldova: Data de Azi');	
		$this->setCenterContainer($this->getCurrentDateDescription());	
		$c='<a name="11"></a>Forum/Comentarii:';
		$this->setLeftContainer($this->getSelectDate());
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));		
		$this->setRightContainer($this->getLeftMenu());
		$this->show();
	}		
	function actionViewDate(){
		if (!((isset($this->id))&&(strlen($this->id)==8))){
			$this->redirect(Config::$numesite);
		}
		$this->setTitle('Calendar Moldova: Data de '.$this->id.'');
		$this->setCenterContainer($this->getDateDescription($this->id));
		$c='<a name="11"></a>Forum/Comentarii:';
		$this->setLeftContainer($this->getSelectDate());
		$this->setLeftContainer($this->getGroupBoxH3("Cauta Localitate:",$this->getSearchLocation()));
		$this->setRightContainer($this->getLeftMenu());
		$this->show();
	}
														
	function show1($out=''){
		$out="";
		$out.='<div id="container1" class="pure-g">';
		$out.='<div id="left1" class="pure-u-1-5">';
		$out.=$this->getLeftContainer();
		$out.='</div>';		
		$out.='<div id="center1" class="pure-u-1-1">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right1" class="pure-u-1-5">';
		$out.=$this->getRightContainer();
		$out.='</div>';
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
		
	function getMain(){
		$n=new Nume();
		$ns=$n->getAll("","suma desc","0","100");
		$out='<div class="groupboxtable">';
		$out.='<table style="width:100%;" class="pure-table pure-table-bordered">';
		$out.='<thead><tr><th style="width:20%;">Nr</th><th style="width:50%;">Nume de familie</th><th style="width:30%;text-align:center">Numarul total de familii</th></tr></thead>';		
		$out.="<tbody>";
		if (count($ns)!=0){			
			$c=1;
			foreach($ns as $n){
				$url=$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$n->id);
				$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$n->name.'</a></td><td>'.$n->suma.'</td></tr>';
				$c=$c+1;	
			}
		}
		$out.="</tbody>";
		$out.="</table>";
		$out.="</div>";		
		
		return $out;
	}	
	function getMenuNume(){
		$out='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$this->id."#2").'">Distributia pe Harta</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$this->id."#3").'">Top Localitati</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$this->id."#4").'">Forum/Comentarii</a></li>';
		$out.='</ul>';
		return $out;
	}

	function getLeftMenu(){
		$outm='<div class="pure-menu pure-menu-open">';
		$outm.='<ul>';
		$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Top 100 cele mai populare nume de familie</a></li>';
		$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtop100namesgeograficlylocated").'">Top 100 cele mai raspindite geografic nume de familie</a></li>';
		$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtop100prenume").'">Top 100 cele mai populare prenume</a></li>';
		$outm.='</ul>';
		$outm.='</div>';
		$out="";
		$out.=$this->getGroupBoxH3("Despre Calendar:",$this->getInfo());
		return $out;		
	}
	function getInfo(){
		$out='Calendarul contine informatie despre toate zilele incepind cu data de 01/01/1900 inclusiv in viitor pina la data de 31/12/2099';
		return $out;
	}
	function getCurrentDateDescription(){
		$now=new DateTime();
		$this->id=$now->format('dmY');
		return $this->getDateDescription($this->id);
	}			
	function getDateDescription($str_date){

		$day=substr($str_date,0,2);
		$month=substr($str_date,2,2);
		$year=substr($str_date,4,4);
		
		//is valid date		
		if (!checkdate($month,$day,$year)){
			$this->redirect(Config::$calendarsite."/index.php");
		}
		
		$dt=new DateTime();
		$dt->setTimestamp(mktime(0,0,0,$month,$day,$year));
		
		if (!Day::doesDayExists($dt)){
			Day::addDaysByYear($year);
		}
		$nrfd=Day::getFreeDaysByYear($year);
		$nrfdfd=Day::getFreeDaysFromDate($year,$dt);		
		
		$out="";
		$o1s='<a name="1"></a>Data '.$str_date.' in '.$this->location->getFullNameDescription();

		$o1b='';
		$o1b.='Ziua '.$day.'Luna '.$month.'Anul '.$year.'<br>';
		
		$jd=cal_to_jd(CAL_GREGORIAN, $month,$day, $year);
		
		$o1b.='Ziua din saptamina: '.jddayofweek ($jd , 1 ).'<br>';
		$o1b.='Luna: '.jdmonthname($jd , 1 ).'<br>';
		$o1b.='Calendar Evreiesc: '.jdtojewish($jd).'<br>';
		$o1b.='Calendar Iulian:'.jdtojulian($jd).'<br>';
		$o1b.='Ziua de pasti:'.date("M-d-Y",easter_date($year)).'<br>';
		$o1b.='Calendar Iulian:'.easter_days($year).'<br>';
		
		
		$o1b.='Ziua a '.$this->getDayNumber($dt).' din an.<br>';
		$dl=$this->getDaysLeft($dt);
		$o1b.='Au mai ramas '.$dl.' zile pina la sfirsit de an.<br>';
		
		$o1b.='Saptamina a '.(int)$dt->format('W').'a <br>';
		$sun_info = date_sun_info($dt->getTimestamp(),$this->location->lat,$this->location->lng);
		$o1b.='<br>';
		$o1b.='Zorii de zi incep la ora '.date("H:i:s", $sun_info['civil_twilight_begin']).'<br>';		
		$o1b.='Rasaritul soarelui are loc la ora '.date("H:i:s", $sun_info['sunrise']).'<br>';
		$o1b.='Zorii de zi dureaza: '.date("H:i:s", ($dt->getTimestamp()+($sun_info['sunrise']-$sun_info['civil_twilight_begin']))).'<br>';
		$o1b.='<br>';
		$o1b.='Soarele cel mai sus pe cer va fi la ora: '.date("H:i:s", $sun_info['transit']).'<br>';
		$o1b.='<br>';
		$o1b.='Asfintitul soarelui are loc la ora '.date("H:i:s", $sun_info['sunset']).'<br>';
		$o1b.='Amurgul dureaza: '.date("H:i:s", ($dt->getTimestamp()+($sun_info['civil_twilight_end']-$sun_info['sunset']))).'<br>';
		$o1b.='Noatpea incepe la ora '.date("H:i:s", $sun_info['civil_twilight_end']).'<br>';		
		$o1b.='<br>';
		$o1b.='Ziua dureaza: '.date("H:i:s", ($sun_info['sunset']-$sun_info['sunrise'])).'<br>';
		$o1b.='Noaptea dureaza: '.date("H:i:s", (mktime(24,0,0,0,0,0)-($sun_info['sunset']-$sun_info['sunrise']))).'<br>';
		$o1b.='<br>';
		$z=Zodiac::getZodiacByDate($dt);
		
		$o1b.='Zodia: '.$z->name.' '.$z->sign.'<br>';
		$y=Year::getYearByDate($dt);
		$o1b.='Animalul asociat acestui an este: '.$y->animal.'<br>';
		$o1b.='Elementul asociat acestui an este: '.$y->element.'<br>';
		
		$o1b.='Numarul de zile libere in an: '.$nrfd.'<br>';
		$o1b.='Numarul de zile lucratoare in an: '.(365-$nrfd).'<br>';
		$o1b.='Numarul de zile libere ramase: '.$nrfdfd.'<br>';
		$o1b.='Numarul de zile lucratoare ramase: '.($dl-$nrfd).'<br>';
		
		$out.=$this->getGroupBoxH3($o1s,$o1b);


		return $out;
	}
	function getDayNumber($dt){
		$startdt=new DateTime();
		$startdt->setTimestamp(mktime(0,0,0,1,1,$dt->format("Y")));
		return ($dt->diff($startdt)->days)+1;
	}
	function getDaysLeft($dt){
		$enddt=new DateTime();
		$enddt->setTimestamp(mktime(0,0,0,12,31,$dt->format("Y")));
		return ($enddt->diff($dt)->days);
	}	
	function getSelectDate(){
		return $this->getGroupBoxH3("Alege Data:",$this->getSelectDateForm());	
	}
	function getSelectDateForm(){
		$nsrs="";
	
		if ((!empty($_POST['searchnameformpost']))&&(isset($this->nsearch))){
			$l=new Location();
			$r=new Raion();
			$ns=DBManager::doSql("SELECT id,name FROM `family` WHERE deleted=0 and name like '".mysql_real_escape_string($this->nsearch,DBConnection::getConnection())."%' LIMIT 0,30");
			if (!is_null($ns)){
				foreach ($ns as $v){
					//$l->loadById($v->location_id);
					//$r->loadById($l->raion_id);
					$nurl=$this->getUrlWithSpecialCharsConverted(Config::$numesite."/index.php","action=viewnume&id=".$v->id);
					$nsrs.="<a href=".$nurl." target=\"_blank\">".$v->name."</a><br>";
				}
			}else {
				$nsrs="Numele de familie *".$this->nsearch."* nu exista!";
			}
		}
		$out='<div id="name">';
		$out.='<form id="searchnameform" name="searchnameform" method="post">';
		$out.='<div id="location-search-box1">';
		$out.='<select type="text" name="day">';
		$out.='<option>01</option>';
		$out.='</select>';
		$out.='<select type="text" name="month">';
		$out.='<option>01</option>';
		$out.='</select>';		
		$out.='<select type="text" name="year">';
		$out.='<option>2013</option>';
		$out.='</select>';
		$out.='<input type="submit" name="gotodate" class="button" style="width:60px;" value="Cauta">';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}
	function getSearchLocation(){
		$lsrs="";
		if ((!empty($_POST['searchlocationformpost']))&&(isset($this->lsearch))){
			$l=new Location();
			$r=new Raion();
			$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` WHERE `localitate`.deleted=0 AND `localitate`.name like '%".mysql_real_escape_string($this->lsearch,DBConnection::getConnection())."%' LIMIT 0,30");
			if (!is_null($ls)){
				foreach ($ls as $v){
					$l->loadById($v->location_id);
					$r->loadById($l->raion_id);
					$lurl=$this->getUrlWithSpecialCharsConverted(Config::$calendarsite."/index.php","action=viewdate&id=".$this->id."&location_id=".$l->id);
					$lsrs.="<a href=".$lurl." target=\"_blank\">".$r->getFullName()."->".$l->getFullName()."</a><br>";
				}
			}else {
				$lsrs="Localitati *".$this->lsearch."* nu exista!";
			}
		}
		$out='<div id="location">';
		$out.='<form id="searchlocationform" name="searchlocationform" method="post">';

		$out.='<table class1="property-table" align="center" style="width: 100%;">';
		$out.='<tr><td>Municipiul/Raionul:</td></tr>';
		$out.='<tr><td>'.Raion::getRaionDropDown($this->raion->id,"width:auto;").'</td></tr>';
		$out.='<tr><td>Oras/Sat:</td></tr>';
		$out.='<tr><td>'.Location::getLocationDropDown($this->raion->id,$this->location->id,"width:auto;").'</td></tr>';
		$out.='</table>';		
		
		$out.='<div id="location-search-box1"><input type="text" name="lsearch" value="'.(isset($this->lsearch)?$this->lsearch:'').'"><br><input type="submit" name="searchlocationformpost" class="button" style="width:60px;" value="Cauta"><br>'.$lsrs.'</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}	
}
$n=new IndexLocationsWebPage();
?>
