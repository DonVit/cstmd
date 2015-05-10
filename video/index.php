<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');

class VideoWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		//$this->setBodyTag('<body onload="SmallViewOnMapLoad()" onunload="GUnload()">');
		//$this->setJavascript("http://maps.google.com/maps?file=api&amp;v=2&amp;hl=ro&amp;sensor=false&amp;key=".Config::getMapKey($this->getServerName()));
		$this->setCSS("style/maps.css");
		$this->setJavascript("js/scripts.js");
		$this->create();		
	}
	function actionDefault(){
		$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));		
		$this->setCenterContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageRaioaneTitle"),$this->getMain()));
		$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRightMenu()));		
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
	function getMain(){
		//$out=$this->getLocation();
		$out.='<div id="container">';
		$out.='<div id="left" style="width:204;">';
		$out.='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php#1" title="Populatia">Lista de Municipii si Raioane</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista de Orase</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai mari sate</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai mici sate</a></li>';		
		$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai jos amplasate localitati</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai sus amplasate localitati</a></li>';		
		$out.='<li><a href="index.php#3" title="Imobil">Lista celor 30 cele mai populate Localitati</a></li>';				
		$out.='<li><a href="index.php#4" title="Imagini">Lista celor 30 cele mai putin populate Localitati</a></li>';
		$out.='<li><a href="index.php#4" title="Imagini">Populatia pe Municipii si Raioane</a></li>';		
		$out.='</ul>';
		$out.='</div>';
		$out.='</div>';
		$out.='<div id="center" style="width:774;">';
		$out.='<div class="groupbox">';		
		$out.='<h2>Lista de Municipii si Raioane</h2>';			
		$r=new Raion();
		$rs=$r->getAll("","municipiu desc, name");
		if (count($rs)!=0){			
			foreach($rs as $r){
				$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';	
			}
		}
		
		//$out.='<br/>';
		//$out.=$this->getElevation($rs[0]->lat,$rs[0]->lng);
		
		$out.='</div>';
		$out.='</div>';
		//$out.='<div id="right">';
		//$out.=$this->getRightContainer();		
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $out;
	}
/*
	function getRaion(){
		//$out=$this->getLocation();
		$out.='<div id="container">';
		$out.='<div id="left" style="width:204;">';
		$out.='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php#1" title="Populatia">Lista de Municipii si Raioane</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista de Orase</a></li>';
		$out.='<li><a href="index.php#3" title="Imobil">Lista celor 30 cele mai populate Localitati</a></li>';				
		$out.='<li><a href="index.php#4" title="Imagini">Lista celor 30 cele mai putin populate Localitati</a></li>';
		$out.='<li><a href="index.php#4" title="Imagini">Populatia pe Municipii si Raioane</a></li>';		
		$out.='</ul>';
		$out.='</div>';
		$out.='</div>';
		$out.='<div id="center" style="width:774;">';
		$out.='<div class="groupbox">';		
		$out.='<h2>'.$this->raion->getFullNameDescription().'</h2>';	
		$out.='</div>';		
		$out.='<div class="groupbox">';		
		$out.='<h3>Localitati in '.$this->raion->getFullNameDescription().':</h3>';	
		$ls=$this->raion->getLocations();	
		foreach($ls as $l){
			$out.='<a href="?lc='.$l->id.'">'.$l->getFullName().'</a><br>';	
		}
		$out.='</div>';		
		//$out.='</div>';		

		$out.=$this->getMap($this->raion->lat,$this->raion->lng,12,3,$this->raion->lat,$this->raion->lng,$this->raion->getFullNameDescription());

		$out.='</div>';
		//$out.='<div id="right">';
		//$out.=$this->getRightContainer();		
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $out;
	}
	function getLocationn(){
		//$out=$this->getLocation();
		$out.='<div id="container">';
		$out.='<div id="left" style="width:204;">';
		$out.='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php#1" title="Populatia">Lista de Municipii si Raioane</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista de Orase</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Lista celor 30 cele mai mari sate</a></li>';
		$out.='<li><a href="index.php#3" title="Imobil">Lista celor 30 cele mai populate Localitati</a></li>';				
		$out.='<li><a href="index.php#4" title="Imagini">Lista celor 30 cele mai putin populate Localitati</a></li>';
		$out.='<li><a href="index.php#4" title="Imagini">Populatia pe Municipii si Raioane</a></li>';		
		$out.='</ul>';
		$out.='</div>';
		$out.='</div>';
		$out.='<div id="center" style="width:774;">';
		$out.='<div class="groupbox">';		
		$out.='<h2>'.$this->location->getFullNameDescription().'</h2>';
		$out.='<h3><a href="?r='.$this->location->raion_id.'">'.$this->location->getRaion()->getFullNameDescription().'</a></h3>';		
		$out.='</div>';	
		$ls=$this->location->getLocations();
		if (count($ls)!=0){		
			$out.='<div class="groupbox">';		
			$out.='<h3>Localitati sub administrare:</h3>';		
			foreach($ls as $l){
				$out.='<a href="?lc='.$l->id.'">'.$l->getFullName().'</a><br>';	
			}
			$out.='</div>';		
		}			
		
		
		$out.=$this->getMap($this->location->lat,$this->location->lng,12,3,$this->location->lat,$this->location->lng, $this->location->getFullNameDescription());
		$out.='</div>';
		//$out.='<div id="right">';
		//$out.=$this->getRightContainer();		
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		return $out;
	}				
	function getLeftContainer(){
		$out="";
		$out.=$this->getLinks();
		$out.=$this->getAddLinks();
		return $out;	
	}		
	function getLinks(){
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php#1" title="Populatia">Populatia</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Stiri</a></li>';
		$out.='<li><a href="index.php#3" title="Imobil">Imobil</a></li>';				
		$out.='<li><a href="index.php#4" title="Imagini">Imagini</a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $out;
	}
	function getAddLinks(){
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="index.php#1" title="Populatia">Adauga Imobil</a></li>';
		$out.='<li><a href="index.php#2" title="Stiri">Adauga Comentariu</a></li>';
		$out.='<li><a href="index.php#3" title="Imobil">Imobil</a></li>';				
		$out.='<li><a href="index.php#4" title="Imagini">Imagini</a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $out;
	}	
	function getCenterContainer(){
		$out="";
		if (isset($this->location)){
			$out.=$this->getLocationGroup();
		}else{
			$out.=$this->getRaionGroup();
		}	
		return $out;
	}
	function getLocationGroup(){
		$out="";
		$out='<div class="groupbox">';		
		$out.='<h2>'.$this->location->getFullNameDescription().'</h2>';
		$out.='<h3><a href="?raionid='.$this->location->raion_id.'">'.$this->location->getRaion()->getFullNameDescription().'</a></h3>';		
		$out.='</div>';	
		$ls=$this->location->getLocations();
		if (count($ls)!=0){		
			$out.='<div class="groupbox">';		
			$out.='<h3>Localitati sub administrare:</h3>';		
			foreach($ls as $l){
				$out.='<a href="?locationid='.$l->id.'">'.$l->getFullName().'</a><br>';	
			}
			$out.='</div>';		
		}			
		
		
		$out.=$this->getMap($this->location->lat,$this->location->lng,12,3,$this->location->lat,$this->location->lng, $this->location->getFullNameDescription());
		return $out;
	}
	function getRaionGroup(){
		$out="";
		$out='<div class="groupbox">';		
		$out.='<h2>'.$this->raion->getFullNameDescription().'</h2>';	
		$out.='</div>';		
		$out.='<div class="groupbox">';		
		$out.='<h3>Localitati in '.$this->raion->getFullNameDescription().':</h3>';	
		$ls=$this->raion->getLocations();	
		foreach($ls as $l){
			$out.='<a href="?locationid='.$l->id.'">'.$l->getFullName().'</a><br>';	
		}
		$out.='</div>';		

		$out.=$this->getMap($this->raion->lat,$this->raion->lng,12,3,$this->raion->lat,$this->raion->lng,$this->raion->getFullNameDescription());
		return $out;
	}						
	function getMap($centerlat,$centerlng,$zoom,$maptype,$lat,$lng,$title){
		$out="";
		$out='<div class="groupbox">';	
		$out.="<h3 >Harta ".$title."</h3>";
		$out.="<table width=\"100%\">";
		$out.="<tr>";
		$out.="<td align=\"center\">";
		$out.="<input id=\"centerlat\" name=\"centerlat\" type=\"hidden\" value=\"$centerlat\" />";
		$out.="<input id=\"centerlng\" name=\"centerlng\" type=\"hidden\" value=\"$centerlng\" />";
		$out.="<input id=\"maptype\" name=\"maptype\" type=\"hidden\" value=\"$maptype\" />";
		$out.="<input id=\"zoom\" name=\"zoom\" type=\"hidden\" value=\"$zoom\" />";
		$out.="<input name=\"lat\" type=\"hidden\" id=\"lat\" readonly=\"true\" class=\"inptdisabled\" value=\"$lat\" /> ";
		$out.="<input name=\"lng\" type=\"hidden\" id=\"lng\" readonly=\"true\" class=\"inptdisabled\" value=\"$lng\" />";
		$out.="</td>";
		$out.="</tr>";
		$out.="<tr>";
		$out.="<td align=\"center\">";
		$out.="<div id=\"map\" style=\"width: 100%; height: 400px\"></div>";
		$out.="</td>";
		$out.="</tr>";
		$out.="</table>";
		$out.='</div>';		
		return $out;
	}		
	function getLocationDropDown1($localitateid=0){
		$l=new Location();
		$ls=$l->doSql("SELECT l.id as lid, l.name as lname, r.id as rid, r.name as rname FROM `localitate` as l inner join raion as r on l.raion_id=r.id where l.lat=0 order by r.id");
		$out="<select id=\"localitate_id\" name=\"localitate_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnDropDownChange()\">";
		//if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
		//	$out.= "<option value=\"0\">Nu Conteaza</option>";
		//}
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->lid==$localitateid){
					$out.= "<option value=".$ll->lid." selected>".$ll->rname."->".$ll->lname."</option>";
				} else {
					$out.= "<option value=".$ll->lid.">".$ll->rname."->".$ll->lname."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}
	function getNodeDropDown1($localitateid=0){
		$l=new Location();
		$ls=$l->doSql("select n.id, n.v from `node_tags` as n inner join localitate as l on l.id=$localitateid and n.v=l.name WHERE n.k='name'");
		$out="<select id=\"node_id\" name=\"node_id\" class=\"select\" size=\"5\" onchange=\"javascript:WizardOnDropDownChange()\">";
		//if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
		//	$out.= "<option value=\"0\">Nu Conteaza</option>";
		//}
		if (!is_null($ls)){
			foreach($ls as $ll){				
				$out.= "<option value=".$ll->id.">".$ll->id."->".$ll->v."</option>";
			}
		}
		$out.="</select>";
		return $out;
	}
	function getMap1(){
		$out='';
		$out.='<form id="poiform" name="poiform" method="post">';		
		$out.='<input id="centerlat" name="centerlat" type="hidden" value="'.$this->map->centerlat.'"/>';
		$out.='<input id="centerlng" name="centerlng" type="hidden" value="'.$this->map->centerlng.'"/>';
		$out.='<input id="maptype" name="maptype" type="hidden" value="'.$this->map->maptype.'"/>';
		$out.='<input id="zoom" name="zoom" type="hidden" value="'.$this->map->zoom.'"/>';
		$out.='<input name="lat" type="hidden" id="lat" readonly="true" class="inptdisabled" value="'.$this->map->lat.'"/>';
		$out.='<input name="lng" type="hidden" id="lng"  readonly="true" class="inptdisabled" value="'.$this->map->lng.'"/>';
		$out.='<input name="title" type="hidden" id="title"  readonly="true" class="inptdisabled" value="'.$this->map->title.'"/>';		
		$out.='<input name="description" type="hidden" id="description"  readonly="true" class="inptdisabled" value="'.$this->map->description.'"/>';		
		$out.='<div id="map"></div>';
		$out.='</form>';
		return $out;
	}
	function getLocation(){	
		if (isset($this->lsearch)){
			$l=new Location();
			$r=new Raion();
			$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` WHERE `localitate`.deleted=0 AND `localitate`.name like '%".$this->lsearch."%' LIMIT 0,30");
			$lsrs="";
			if (!is_null($ls)){
			foreach ($ls as $v){
				$l->loadById($v->location_id);
				$r->loadById($l->raion_id);
				$lsrs.="<a href=".$this->getBaseName()."?raionid=".$r->id."&locationid=".$l->id.">".$r->getFullName()."->".$l->getFullName()."</a><br>";
			}
			}else {
				$lsrs="Localitati *".$this->lsearch."* nu exista!";
			}
		}
		$out='<div id="location">';
		$out.='<form id="locationform" name="locationform" method="post">';
		$out.='<div id="location-raion">Municipiu/Raion:'.$this->getRaionDropDown(User::getImobilCurrentRaion()->id).'</div>';
		//if (User::getImobilCurrentRaion()->id!=0){
			$out.='<div id="location-localitate">Oras/Sat:'.$this->getLocationDropDown(User::getImobilCurrentRaion()->id,User::getImobilCurrentLocation()->id).'</div>';
		//}
		//if (User::getImobilCurrentLocation()->id!=0){
		//	$out.='<div id="location-sector">Sector:'.$this->getSectorDropDown(User::getImobilCurrentLocation()->id,User::getImobilCurrentSector()->id).'</div>';
		//}
		//$out.='<div id="location-search-label">Cauta Localitate:</div>';
		$out.='<div id="location-search-box">Cauta Localitate:<input type="text" name="lsearch" value="'.$this->lsearch.'"><input type="submit" class="button" value="Cauta"><br>'.$lsrs.'</div>';
		//$out.='<div id="location-search-box"><input type="text" id="lsearch" name="lsearch" value="Cauta Localitate" onblur="if (this.value==\'\') this.value=\'Cauta Localitate\';" onfocus="if (this.value==\'Cauta Localitate\') this.value=\'\';"><input type="submit" class="button" value="Cauta"><br>'.$lsrs.'</div>';
		//$out.='<div id="location-search">'.$lsrs.'</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</form>';
		$out.='</div>';
		return $out;
	}	

	function getRaionDropDown($raionid){
		$r=new Raion();
		$rs=$r->getAll("","`municipiu` desc,`order`,`name`");
		$out="<select id=\"raionid\" name=\"raionid\" class=\"select\" size=\"1\" onchange=\"javascript:FilterOnRaionChange('".($this->getBaseName())."')\">";
		$out.="<option value=\"0\">Toate</option>";
		if (!is_null($rs)){
			foreach($rs as $rr){
				$name=($rr->municipiu==1)?"m. ".$rr->name:"r. ".$rr->name;
				if ($rr->id==$raionid){
					$out.= "<option value=".$rr->id." selected>".$name."</option>";
				} else {
					$out.= "<option value=".$rr->id.">".$name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}	
	function getLocationDropDown($raionid,$locationid){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$raionid,"`order`,`oras` desc,`name`");
		$out="<select id=\"locationid\" name=\"locationid\" class=\"select\" size=\"1\" onchange=\"javascript:FilterOnLocationChange('".($this->getBaseName())."')\">";
		$out.="<option value=\"0\">Toate</option>";
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->id==$locationid){
					$out.= "<option value=".$ll->id." selected>".$ll->tip." ".$ll->name_ro."</option>";
				} else {
					$out.= "<option value=".$ll->id.">".$ll->tip." ".$ll->name_ro."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}
*/												
}
$n=new VideoWebPage();
?>
