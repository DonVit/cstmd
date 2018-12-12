<?php
class LocationFilterWebPage extends MainWebPage {

	
	function __construct() {
		parent::__construct();

		if (isset($this->raionid)){
			$r=new Raion();
			$o=$r->getById($this->raionid);
			if (!is_null($o)){
				User::setCurrentRaion($o);
			} else {
				$dr=new Raion();
				$dr->id=0;
				$dr->name="Toate";
				User::setCurrentRaion($dr);
			}
		}
		if (isset($this->locationid)){
			$l=new Location();
			$o=$l->getById($this->locationid);
			if (is_null($o)||(User::getCurrentRaion()->id==0)){
				$dl=new Location();
				$dl->id=0;
				$dl->name="Toate";
				User::setCurrentLocation($dl);				
			} else {
				User::setCurrentLocation($o);
			}
		}
		if (isset($this->sectorid)){
			$l=new Sector();
			$o=$l->getById($this->sectorid);
			if (is_null($o)||(User::getCurrentLocation()->id==0)){
				$ds=new Sector();
				$ds->id=0;
				$ds->name="Toate";
				User::setCurrentSector($ds);				
			} else {
				User::setCurrentSector($o);
			}
		}
	}
	
	function show($html="LocationFilterWebPageHTML"){
		$out=$this->getLocation();
		$out.=$html;
		MainWebPage::show($out);
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
		$out.='<div id="location-raion">Municipiu/Raion:'.$this->getRaionDropDown(User::getCurrentRaion()->id).'</div>';
		//if (User::getCurrentRaion()->id!=0){
			$out.='<div id="location-localitate">Oras/Sat:'.$this->getLocationDropDown(User::getCurrentRaion()->id,User::getCurrentLocation()->id).'</div>';
		//}
		//if (User::getCurrentLocation()->id!=0){
			$out.='<div id="location-sector">Sector:'.$this->getSectorDropDown(User::getCurrentLocation()->id,User::getCurrentSector()->id).'</div>';
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
					$out.= "<option value=".$ll->id." selected>".$ll->tip." ".$ll->name."</option>";
				} else {
					$out.= "<option value=".$ll->id.">".$ll->tip." ".$ll->name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}	
	function getSectorDropDown($locationid,$sectorid){
		$s=new Sector();
		$ss=$s->getAll("localitate_id=".$locationid,"`order`,`name`");
		$out="<select id=\"sectorid\" name=\"sectorid\" class=\"select\" size=\"1\" onchange=\"javascript:FilterOnSectorChange('".($this->getBaseName())."')\">";
		$out.="<option value=\"0\">Toate</option>";
		if (!is_null($ss)){
			foreach($ss as $s){
				if ($s->id==$sectorid){
					$out.= "<option value=".$s->id." selected>".$s->name."</option>";
				} else {
					$out.= "<option value=".$s->id.">".$s->name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}	
}
//$b=new WebPage();
//phpinfo();
?>
