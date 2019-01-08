<?php
require_once(__DIR__ . '/../main/loader.php');


class IndexCalendarWebPage extends MainWebPage {
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
	function setDate(){
		$this->day=substr($this->id,0,2);
		$this->month=substr($this->id,2,2);
		$this->year=substr($this->id,4,4);
		$this->dt=new DateTime();
		$this->dt->setTimestamp(mktime(0,0,0,$this->month,$this->day,$this->year));
	}
	function actionDefault(){
		$now=new DateTime();
		$this->id=$now->format('dmY');
		$this->setDate();
		$this->actionViewDate();
	}
	function actionSelectDate(){
		//is valid date
		if (!checkdate($this->month,$this->day,$this->year)){
			$this->redirect(Config::$calendarsite."/index.php");
		}
		$this->dt=new DateTime();
		$this->dt->setTimestamp(mktime(0,0,0,$this->month,$this->day,$this->year));
		$this->day=$this->dt->format('d');
		$this->month=$this->dt->format('m');
		$this->year=$this->dt->format('Y');
		$this->redirect(Config::$calendarsite.'/index.php?action=viewdate&id='.$this->day.$this->month.$this->year);
	}
	function actionViewDate(){
		if (!((isset($this->id))&&(strlen($this->id)==8))){
			$this->redirect(Config::$calendarsite."/index.php");
		}
        $this->setDate();

		//is valid date
		if (!checkdate($this->month,$this->day,$this->year)){
			$this->redirect(Config::$calendarsite."/index.php");
		}

		$this->setTitle('Calendar Moldova: Data de '.$this->id.'');
		$this->setCenterContainer($this->getDateDescription($this->id));
		$this->setCenterContainer($this->getMonth());
		$this->setCenterContainer($this->getYear());
		$this->setCenterContainer($this->getCalendars());
		$this->setCenterContainer($this->getSun());
		$this->setCenterContainer($this->getZodia());
		$this->setCenterContainer($this->getCalendarChinez());
		$this->setLeftContainer($this->getMenu());
		$this->setLeftContainer($this->getGroupBoxH3("Calendar pe Localitate:",$this->getSearchLocation()));
		$this->setLeftContainer($this->getSelectDate());
		$this->setRightContainer($this->getLeftMenu());
		$this->setCenterContainer($this->getNewsTitles());
		//$c='<a name="9"></a>Forum/Comentarii:';
		//$this->setCenterContainer($this->getGroupBoxH3($c,Comment::getComments($this,'l',$l->id)));
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
		$out='<div class="pure-menu pure-menu-open">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdate&id=".$this->id."#1").'">Descriere</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdate&id=".$this->id."#2").'">Luna</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdate&id=".$this->id."#3").'">Anul</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdate&id=".$this->id."#4").'">Calendare</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdate&id=".$this->id."#5").'">Soarele</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdate&id=".$this->id."#6").'">Zodia</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdate&id=".$this->id."#7").'">Calendarul Chinez</a></li>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewdate&id=".$this->id."#8").'">Stiri</a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $this->getGroupBoxH3("Menu:",$out);;
	}

	function getLeftMenu(){
		$outm='<div class="pure-menu pure-menu-open">';
		$outm.='<ul>';
		$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Top 100 cele mai populare nume de familie</a></li>';
		$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtop100namesgeograficlylocated").'">Top 100 cele mai raspindite geografic nume de familie</a></li>';
		$outm.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("index.php","action=viewtop100prenume").'">Top 100 cele mai populare prenume</a></li>';
		$outm.='</ul>';
		$outm.='</div>';
		return $this->getGroupBoxH3("Despre Calendar:",$this->getInfo());
	}
	function getInfo(){
		$out='Calendarul contine informatie despre toate zilele incepind cu data de 01/01/1900 inclusiv in viitor pina la data de 31/12/2099';
		return $out;
	}

	function getDateDescription(){

		if (!Day::doesDayExists($this->dt)){
			Day::addDaysByYear($this->year);
		}
		$nrfd=Day::getFreeDaysByYear($this->year);
		$nrfdfd=Day::getFreeDaysFromDate($this->year,$this->dt);
		
		$out='';
		$this->dateLongFormat = (int)$this->day.' '.Enum::getMonths()[(int)$this->month].' '.$this->year;
		$title=(int)$this->day.' '.Enum::getMonths()[(int)$this->month].' anul '.$this->year.' in '.$this->location->getFullNameDescription();
		$this->setTitle($title);
		$o1s='<a name="1"></a>'.$title;

		$o1b='';
		$o1b.='Ziua: '.(int)$this->day.'.<br>';
		$o1b.='Luna: '.(int)$this->month.'.<br>';
		$o1b.='Anul: '.$this->year.'.<br>';
		$jd=gregoriantojd($this->month,$this->day, $this->year);
		$o1b.='Ziua din săptămînă: '.Enum::getDays()[jddayofweek($jd)].'.<br>';
		$o1b.='Luna: '.Enum::getMonths()[(int)$this->month].'.<br>';
		$o1b.='Luna in calendarul popular: '.Enum::getPopMonths()[(int)$this->month].'<br>';
		$o1b.='Ziua a '.$this->getDayNumber($this->dt).'a din an.<br>';
		$o1b.='Saptamina a '.(int)$this->dt->format('W').'a <br>';
		$out.=$this->getGroupBoxH3($o1s,$o1b);
		return $out;
	}
	function getMonth(){
	    $o1s='<a name="2"></a>Luna '.Enum::getMonths()[(int)$this->month].' la data de '.$this->dateLongFormat;
		$jd=gregoriantojd($this->month,$this->day, $this->year);
		$o1b='Luna: '.Enum::getMonths()[(int)$this->month].'<br>';
		$o1b.='Luna in calendarul Popular: '.Enum::getPopMonths()[(int)$this->month].'<br>';
		$o1b.='Luna in calendarul Englez: '.jdmonthname($jd, CAL_MONTH_GREGORIAN_LONG).'<br>';
		$o1b.='Luna in calendarul Evreiesc: '.jdmonthname($jd, CAL_MONTH_JEWISH).'<br>';
	    return $this->getGroupBoxH3($o1s,$o1b);
	}
	function getYear(){
		$o1s='<a name="3"></a>Anul '.$this->year.' la data de '.$this->dateLongFormat;
		$nrfd=Day::getFreeDaysByYear($this->year);
        $nrfdfd=Day::getFreeDaysFromDate($this->year,$this->dt);
		$dl=$this->getDaysLeft($this->dt);
		$o1b='Ziua a '.$this->getDayNumber($this->dt).'a din an.<br>';
		$currentWeek=(int)$this->dt->format('W');
		$o1b.='Saptamina a '.$currentWeek.'a din an. <br>';
		$o1b.='Saptamini ramase in an: '.(52-$currentWeek).'.<br>';
		$o1b.='Au mai ramas '.$dl.' zile pina la sfirsit de an.<br>';
		$o1b.='Numarul de zile libere în an (Sîmbete și Duminici): '.$nrfd.'.<br>';
		$o1b.='Numarul de zile lucrătoare în an: '.(365-$nrfd).'.<br>';
		$o1b.='Numarul de zile libere rămase in acest an (Sîmbete și Duminici): '.$nrfdfd.'.<br>';
		$o1b.='Numarul de zile lucrătoare rămase in acest an: '.($dl-$nrfd).'.<br>';
		if ($this->year>1969 && $this->year<2038){
		    $o1b.='Ziua de pasti in acest an: '.date("M-d-Y",easter_date($this->year)).'.<br>';
		}
		return $this->getGroupBoxH3($o1s,$o1b);
	}
	function getCalendars(){
		$o1s='<a name="4"></a>Calendare';
		$jd=gregoriantojd($this->month,$this->day, $this->year);
		$o1b='Data in Calendarul Gregorian:'.jdtogregorian($jd).'<br>';
		$o1b.='Data in Calendarul Iulian:'.jdtojulian($jd).'<br>';
		$o1b.='Data in Calendarul Evreiesc: '.jdtojewish($jd).'<br>';
		return $this->getGroupBoxH3($o1s,$o1b);
	}
	function getSun(){
		$sun_info = date_sun_info($this->dt->getTimestamp(),$this->location->lat,$this->location->lng);
		$o1s='<a name="5"></a>Soarele in '.$this->location->getFullName().' la data de '.$this->dateLongFormat;
		$o1b='Zorii de zi incep la ora '.date("H:i:s", $sun_info['civil_twilight_begin']).'<br>';
		$o1b.='Rasaritul soarelui are loc la ora '.date("H:i:s", $sun_info['sunrise']).'<br>';
		$o1b.='Zorii de zi dureaza: '.date("H:i:s", ($this->dt->getTimestamp()+($sun_info['sunrise']-$sun_info['civil_twilight_begin']))).'<br>';
		$o1b.='<br>';
		$o1b.='Soarele cel mai sus pe cer va fi la ora: '.date("H:i:s", $sun_info['transit']).'<br>';
		$o1b.='<br>';
		$o1b.='Asfintitul soarelui are loc la ora '.date("H:i:s", $sun_info['sunset']).'<br>';
		$o1b.='Amurgul dureaza: '.date("H:i:s", ($this->dt->getTimestamp()+($sun_info['civil_twilight_end']-$sun_info['sunset']))).'<br>';
		$o1b.='Noatpea incepe la ora '.date("H:i:s", $sun_info['civil_twilight_end']).'<br>';
		$o1b.='<br>';
		$o1b.='Ziua dureaza: '.date("H:i:s", ($sun_info['sunset']-$sun_info['sunrise'])).'<br>';
		$o1b.='Noaptea dureaza: '.date("H:i:s", (mktime(24,0,0,0,0,0)-($sun_info['sunset']-$sun_info['sunrise']))).'<br>';
		return $this->getGroupBoxH3($o1s,$o1b);
	}
	function getZodia(){
		$o1s='<a name="6"></a>Zodia';
		$z=Zodiac::getZodiacByDate($this->dt);
		$dt_start=DateTime::createFromFormat("dmY",$z->startdate.$this->dt->format('Y'));
        $dt_end=DateTime::createFromFormat("dmY",$z->enddate.$this->dt->format('Y'));
		$o1b='Zodia: '.$z->name.' ('.$dt_start->format('d/M').' - '.$dt_end->format('d/M').')<br>';
		$o1b.='<div style="text-align:center;font-size:160px;">'.$z->sign.'</div>';
		return $this->getGroupBoxH3($o1s,$o1b);
	}
	function getCalendarChinez(){
		$o1s='<a name="7"></a>Calendarul Chinez';
		$y=Year::getYearByDate($this->dt);
		$o1b='Animalul asociat acestui an este: '.$y->animal_ro.'<br>';
		$o1b.='Elementul asociat acestui an este: '.$y->element.'<br>';
		$o1b.='<br>';
		$o1b.='<div style="text-align:center;font-size:60px;"><img src="'.$y->animal_url.'"></div>';
		$o1b.='<div style="text-align:center;font-size:160px;">'.$y->stem.'</div>';
		$o1b.='<div style="text-align:center;font-size:160px;">'.$y->branch.'</div>';
		return $this->getGroupBoxH3($o1s,$o1b);
	}

    function getNewsTitles(){
        $out="";
        $o2s='<a name="8"></a>Stiri la data de '.$this->dateLongFormat.' din '.$this->location->getFullName();
        $fi=new FeedItem();
        $o2b=$fi->getNewsByLocalitateAndDate($this->location->id, $this->dt->format(FeedItem::$dateFormat));
        $link=$this->getUrlWithSpecialCharsConverted(Config::$feedssite."/index.php","action=primarie&id=".$this->location->id);
        $o2f=$this->getFooterWithLink($link);
        return $this->getGroupBoxH3($o2s,$o2b,$o2f);
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
	function getWeeksInYear($dt){
		$enddt=new DateTime();
		$enddt->setTimestamp(mktime(0,0,0,12,25,$dt->format("Y")));
		return $enddt->format("W");
	}
	function getSelectDate(){
		return $this->getGroupBoxH3("Alege Data:",$this->getSelectDateForm());	
	}
	function getSelectDateForm(){
		$nsrs="";
		if (!empty($_POST['searchdateformpost'])){
            echo $this->day.$this->month.$this->year;
            //$this->redirect(Config::$calendarsite."/index.php?action=viewdate&id=".$this->day.$this->month.$this->year);
		}
		$out='<table id="name">';
		$out.='<form id="searchdateform" name="searchdateform" action="index.php?action=selectdate" method="post">';
		$out.='<tr>';
		$out.='<td>Anul:</td>';
		$out.='<td>'.$this->getYears().'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td>Luna:</td>';
		$out.='<td>'.$this->getMonths().'</td>';
		$out.='</tr>';
		$out.='<div>';
		$out.='<td>Ziua:</td>';
		$out.='<td>'.$this->getDays().'</td>';
		$out.='</tr>';
		$out.='<tr>';
		$out.='<td></td>';
		$out.='<td><input type="submit" name="searchdateformpost" class="button" style="width:60px;" value="Go"></td>';
		$out.='</tr>';
		$out.='</form>';
		$out.='</table>';
		return $out;
	}
	function getYears(){
		$out='<select type="text" name="year" id="year">';
		for ($y = 1900; $y <= 2099; $y++) {
            if ($this->year==$y) {
            $out.='<option selected>'.$y.'</option>';
            } else {
            $out.='<option>'.$y.'</option>';
            }
        }
		$out.='</select>';
		return $out;
	}
	function getMonths(){
		$out='<select type="text" name="month" id="month">';
		for ($m = 1; $m <= 12; $m++) {
            if ($this->month==$m) {
            $out.='<option selected value='.$m.'>'.Enum::getMonths()[$m].'</option>';
            } else {
            $out.='<option value='.$m.'>'.Enum::getMonths()[$m].'</option>';
            }
        }
		$out.='</select>';
		return $out;
	}
	function getDays(){
		$out='<select type="text" name="day">';
		$days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		for ($d = 1; $d <= $days; $d++) {
            if ($this->day==$d) {
            $out.='<option selected value='.$d.'>'.$d.'</option>';
            } else {
            $out.='<option value='.$d.'>'.$d.'</option>';
            }
        }
		$out.='</select>';
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
		//$out.='<table class1="property-table" align="center" style="width: 100%;">';
		//$out.='<tr><td>Municipiul/Raionul:</td></tr>';
		//$out.='<tr><td>'.Raion::getRaionDropDown($this->raion->id,"width:auto;").'</td></tr>';
		//$out.='<tr><td>Oras/Sat:</td></tr>';
		//$out.='<tr><td>'.Location::getLocationDropDown($this->raion->id,$this->location->id,"width:auto;").'</td></tr>';
		//$out.='</table>';
		
		$out.='<div>Cauta localitate.</div>';
		$out.='<div><input type="text" name="lsearch" value="'.(isset($this->lsearch)?$this->lsearch:'').'"></div>';
		$out.='<div><input type="submit" name="searchlocationformpost" class="button" style="width:60px;" value="Cauta"><br>'.$lsrs.'</div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}	
}
$p=new IndexCalendarWebPage();
?>
