<?php
require_once('loader.php');
class Properties extends MainWebPage {
	function __construct() {
		parent::__construct();
		$this->setCSS("styles/index.css");

		$t="ANUNŢURI CHIRII DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);

		if (isset($this->scopid)){
			User::setChirieCurrentScop($this->scopid);
		}
		if (isset($this->tipimobil)){
			User::setChirieCurrentTipImobil($this->tipimobil);
		}
		if (isset($this->subtipimobil)){
			User::setChirieCurrentSubTipImobil($this->subtipimobil);
		}
		if (!isset($this->page)){
			$this->page=0;
		}			
		if (isset($this->raionid)){
			$r=new Raion();
			$o=$r->getById($this->raionid);

			if (is_null($o)){
				$dr=new Raion();
				$dr->id=0;
				$dr->name="Toate";
				User::setChirieCurrentRaion($dr);				
			} else {	
				User::setChirieCurrentRaion($o);
				$t="Anunțuri Chirii din ";
				$t=$t.$o->getFullNameDescription();
				$this->setTitle($t);
				$this->setDescription($t);							
			}			
		}
		if (isset($this->locationid)){
			$l=new Location();
			$o=$l->getById($this->locationid);

			if (is_null($o)||(User::getChirieCurrentRaion()->id==0)){
				$dl=new Location();
				$dl->id=0;
				$dl->name="Toate";
				User::setChirieCurrentLocation($dl);				
			} else {
				User::setChirieCurrentLocation($o);
				$t="Anunțuri Chirii din ";
				$t=$t.$o->getFullNameDescription();
				$this->setTitle($t);
				$this->setDescription($t);
			}		
		}
		if (isset($this->sectorid)){
			$l=new Sector();
			$o=$l->getById($this->sectorid);
			if (is_null($o)||(User::getChirieCurrentLocation()->id==0)){
				$ds=new Sector();
				$ds->id=0;
				$ds->name="Toate";
				User::setChirieCurrentSector($ds);				
			} else {
				User::setChirieCurrentSector($o);
			}
		}
	

		$this->create();	
	}
	function actionDefault(){
		$this->setLeftContainer($this->getGroupBoxH3("",$this->getAdd()));
		$this->setLeftContainer($this->getGroupBoxH3("",$this->getMenu()));
		$this->setLeftContainer($this->getGroupBoxH3("",$this->getRssLink()));
		$this->setRightContainer($this->getGroupBoxH3("",$this->getMain()));
		$this->show();
	}		
	function show($out=''){
		$out="";
		$out=$this->getLocation();
		$out.='<div id="container" style="font-size:85%;">';		
		$out.='<div id="left" class="container left" style="width:198px;">';
		$out.=$this->getLeftContainer();
		$out.='</div>';		
		//$out.='<div id="center" class="container center" style="width:798px;">';
		//$out.=$this->getCenterContainer();
		//$out.='</div>';
		$out.='<div id="right" class="container right" style="width:800px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}		
	function getAdd(){	
		//$out='<div id="leftmenu1" class="groupbox">';
		$out='<ul>';
		$out.='<li><a href="'.$this->getUrlWithSpecialCharsConverted("add.php").'">Adauga Anunt</a></li>';
		$out.='</ul>';
		//$out.='</div>';
		return $out;
	}
	function getMain(){
		$out=$this->getImobilList($this->page,20);
		$out.=$this->getPages($this->page,20);
		return $out;
	}
	function getMenu(){	
		$out='';

		$f="";
		if (User::getChirieCurrentRaion()->id!=0){
			$f.=" and raion_id=".User::getChirieCurrentRaion()->id;
		}
		if (User::getChirieCurrentLocation()->id!=0){
			$f.=" and localitate_id=".User::getChirieCurrentLocation()->id;
		}	
		if (User::getChirieCurrentSector()->id!=0){
			$f.=" and sector_id=".User::getChirieCurrentSector()->id;
		}

		//$s=new Scop();
		$sql="select imobil.scop_id, scop.menu_name as scop_name, count(*) as cnt from imobil join scop on imobil.scop_id=scop.id join tipimobil on imobil.tipimobil_id=tipimobil.id join subtipimobil on imobil.subtipimobil_id=subtipimobil.id where imobil.status = 0 and imobil.scop_id in (2,4)";			
		$sql.=$f." group by imobil.scop_id order by imobil.scop_id";
		$ss=DBManager::doSql($sql);
		
		//$t=new TipImobil();
		$sql="select imobil.scop_id, scop.menu_name as scop_name, imobil.tipimobil_id, tipimobil.menu_name as tipimobil_name, count(*) as cnt from imobil join scop on imobil.scop_id=scop.id join tipimobil on imobil.tipimobil_id=tipimobil.id join subtipimobil on imobil.subtipimobil_id=subtipimobil.id where imobil.status = 0 and imobil.scop_id in (2,4)";			
		$sql.=$f." group by imobil.scop_id, imobil.tipimobil_id order by imobil.scop_id, imobil.tipimobil_id";
		$ts=DBManager::doSql($sql);

		//$st=new SubTipImobil();
		$sql="select imobil.scop_id, scop.menu_name as scop_name, imobil.tipimobil_id, tipimobil.menu_name as tipimobil_name, imobil.subtipimobil_id, subtipimobil.name as subtipimobil_name, count(*) as cnt from imobil join scop on imobil.scop_id=scop.id join tipimobil on imobil.tipimobil_id=tipimobil.id join subtipimobil on imobil.subtipimobil_id=subtipimobil.id where imobil.status = 0 and imobil.scop_id in (2,4)";			
		$sql.=$f." group by imobil.scop_id, imobil.tipimobil_id, imobil.subtipimobil_id order by imobil.scop_id, imobil.tipimobil_id, imobil.subtipimobil_id";
		$sts=DBManager::doSql($sql);

		if (count($ss)!=0) {
			//$out.='<div id="leftmenu2" class="groupbox">';
			$out.='<ul class="leftmenulist">';
			foreach ($ss as $s){
				if ($s->scop_id==User::getChirieCurrentScop()){
					$out.='<li style="color: #C20000;"><a href="/'.$this->getBaseName().'?scopid='.$s->scop_id.'&tipimobil=0&subtipimobil=0&raionid='.User::getChirieCurrentRaion()->id.'&locationid='.User::getChirieCurrentLocation()->id.'&sectorid='.User::getChirieCurrentSector()->id.'" style="color: #C20000;">'.$s->scop_name.' ('.$s->cnt.')</a></li>';
				}else{
					$out.='<li><a href="/'.$this->getUrlWithSpecialCharsConverted('index.php','scopid='.$s->scop_id.'&tipimobil=0&subtipimobil=0&raionid='.User::getChirieCurrentRaion()->id.'&locationid='.User::getChirieCurrentLocation()->id.'&sectorid='.User::getChirieCurrentSector()->id).'">'.$s->scop_name.' ('.$s->cnt.')</a></li>';
				}
				$out.='<ul class="leftsubmenulist">';
				foreach ($ts as $t){				
					if ($s->scop_id==$t->scop_id){
						if ($t->tipimobil_id!=User::getChirieCurrentTipImobil()){
							//$out.='<li><a href="/'.$this->getBaseName().'?scopid='.$s->scop_id.'&tipimobil='.$t->tipimobil_id.'&subtipimobil=0&raionid='.User::getChirieCurrentRaion()->id.'&locationid='.User::getChirieCurrentLocation()->id.'&sectorid='.User::getChirieCurrentSector()->id.'">'.$t->tipimobil_name.' ('.$t->cnt.')</a></li>';
							$out.='<li><a href="/'.$this->getUrlWithSpecialCharsConverted('index.php','scopid='.$s->scop_id.'&tipimobil='.$t->tipimobil_id.'&subtipimobil=0&raionid='.User::getChirieCurrentRaion()->id.'&locationid='.User::getChirieCurrentLocation()->id.'&sectorid='.User::getChirieCurrentSector()->id).'">'.$t->tipimobil_name.' ('.$t->cnt.')</a></li>';						
						}else{
							$out.='<li style="color: #C20000;"><a href="/'.$this->getUrlWithSpecialCharsConverted('index.php','scopid='.$s->scop_id.'&tipimobil='.$t->tipimobil_id.'&subtipimobil=0&raionid='.User::getChirieCurrentRaion()->id.'&locationid='.User::getChirieCurrentLocation()->id.'&sectorid='.User::getChirieCurrentSector()->id).'" style="color: #C20000;">'.$t->tipimobil_name.' ('.$t->cnt.')</a></li>';
							$out.='<ul class="leftsubsubmenulist">';
							foreach($sts as $st){
								if ($t->tipimobil_id==$st->tipimobil_id){
									if ($st->subtipimobil_id==User::getChirieCurrentSubTipImobil()){
										$out.='<li style="color: #C20000;"><a href="/'.$this->getUrlWithSpecialCharsConverted('index.php','scopid='.$s->scop_id.'&tipimobil='.$t->tipimobil_id.'&subtipimobil='.$st->subtipimobil_id.'&raionid='.User::getChirieCurrentRaion()->id.'&locationid='.User::getChirieCurrentLocation()->id.'&sectorid='.User::getChirieCurrentSector()->id).'" style="color: #C20000;">'.$st->subtipimobil_name.' ('.$st->cnt.')</a></li>';
									}else{
										$out.='<li><a href="/'.$this->getUrlWithSpecialCharsConverted('index.php','scopid='.$s->scop_id.'&tipimobil='.$t->tipimobil_id.'&subtipimobil='.$st->subtipimobil_id.'&raionid='.User::getChirieCurrentRaion()->id.'&locationid='.User::getChirieCurrentLocation()->id.'&sectorid='.User::getChirieCurrentSector()->id).'">'.$st->subtipimobil_name.' ('.$st->cnt.')</a></li>';
									}
								}
							}
							$out.='</ul>';												
						}
					}
				}
				$out.='</ul>';		
			}					
						
			$out.='</ul>';	
			//$out.='</div>';	
		}
		return $out;
	}		
	function getRssLink(){
		//$out='<div class="groupbox">';
		$out='<ul class="leftmenulist1">';
		$out.='<li><a href="rss.php" title="Anuţuri în format RSS">Anuţuri în RSS <img src="'.Config::$commonsite.'/img/rss.png" alt="Companii in format RSS" title="Comapnii in format RSS"/></a></li>';
		$out.='</ul>';
		//$out.='</div>';
		return $out;
	}
	function getImobilList($page,$rowsperpage){	
	
		$p=new Property();
		$rs=$p->getChirieByPage(0,User::getChirieCurrentScop(),User::getChirieCurrentTipImobil(),User::getChirieCurrentSubTipImobil(),User::getChirieCurrentRaion()->id,User::getChirieCurrentLocation()->id,User::getChirieCurrentSector()->id,$page,$rowsperpage);
		$imobil_result_output='<div class="groupboxtable">';
		$imobil_result_output.='<table style="width:100%;border: 0;cellpadding:0;cellspacing:1;" class="grid">';
		$imobil_result_output.='<tr>';
		$imobil_result_output.='<th class="gridth" style="align:center;">Link</th>';
		$imobil_result_output.='<th class="gridth" style="align:center;">Foto</th>';
		$imobil_result_output.='<th class="gridth" style="align:center;">Harta</th>';
		$imobil_result_output.='<th class="gridth" style="align:center;">Data Pub.</th>';
		$imobil_result_output.='<th class="gridth" style="align:center;">Tip Anunt</th>';
		$imobil_result_output.='<th class="gridth" style="align:center;">Tip Imobil</th>';
		$imobil_result_output.='<th class="gridth" style="align:center;">Addresa</th>';
		$imobil_result_output.='<th class="gridth" style="align:center;">Aria</th>';
		$imobil_result_output.='<th class="gridth" style="align:center;">Pret</th>';
		$imobil_result_output.='</tr>';
		$i=0;
		if(count($rs)!=0){
			foreach($rs as $row){
				$i++;
				$imobil_result_output.= "" .
				//print_r($row);
				"<tr class=\"gridtr".($i & 1)."\" onclick=\"ImobilTableRowClick(".$row->id.");\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"<td class=\"gridtd\" style=\"align:center;\">".$this->getFunctions($row->id)."</td>" .
				"<td class=\"gridtd\" style=\"align:center;\">".$this->getAlbum($row)."</td>" .
				"<td class=\"gridtd\" style=\"align:center;\">".$this->getMap($row)."</td>" .
				"<td class=\"gridtd\" style=\"align:center;\">".date("Y-m-d", strtotime($row->data))."</td>" .
		  		"<td class=\"gridtd\" style=\"align:left;\">".$row->scop_name."</td>" .
				"<td class=\"gridtd\" style=\"align:left;\">".$row->tipimobil_name." - ".$row->subtipimobil_name."</td>" .
				"<td class=\"gridtd\" style=\"align:left;\">".$this->getAdress($row)."</td>" .
				"<td class=\"gridtd\" style=\"align:center;\">".$this->getAria($row)."</td>" .
				"<td class=\"gridtd\" style=\"align:center;\">".$this->getPret($row)."</td>";
			}
		}
		$imobil_result_output.="</table>";
		$imobil_result_output.="</div>";
		return $imobil_result_output;
	}
function getPages($page,$rowsperpage){
	//global $filter;
	//Numarul de requesturi la ficare pagina vizualizata poate fi optimizat adaugind countul in session
	//$imobil= new DBImobil();
	$p=new Property();
	//$cnt=$p->getImobilCount();
	$cnt=$p->getChirieCount(0,User::getChirieCurrentScop(),User::getChirieCurrentTipImobil(),User::getChirieCurrentSubTipImobil(),User::getChirieCurrentRaion()->id,User::getChirieCurrentLocation()->id,User::getChirieCurrentSector()->id);
	//$cnt=0;	
	$out='<div class="container groupbox">';;
	$out.="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" class=\"pages1\">";
	$out.="<tr>";
	$out.="<td align=\"left\"><a href=\"add.php\" class=\"link_button\">Adauga Anunt</a></td>";
	$out.="<td align=\"right\">";

	$url=$this->getBaseName()."?scopid=".User::getChirieCurrentScop()."&tipimobil=".User::getChirieCurrentTipImobil()."&subtipimobil=".User::getChirieCurrentSubTipImobil()."&raionid=".User::getChirieCurrentRaion()->id."&locationid=".User::getChirieCurrentLocation()->id."&sectorid=".User::getChirieCurrentSector()->id;
	if ($cnt==0){
		return "";
	}
	if ($page!=0){
		$out.="<a href=/".$url."&page=".($page-1)." class=\"link_button\"><<<</a>";
	}
	if ((($page+1)*$rowsperpage)>$cnt){
		$out.=" ".(($page*$rowsperpage)+1)." - ".$cnt." din ".$cnt." ";
	} else {
		$out.=" ".(($page*$rowsperpage)+1)." - ".(($page+1)*$rowsperpage)." din ".$cnt." ";
	}
	if ((($page+1)*$rowsperpage)<$cnt){
		$out.="<a href=/".$url."&page=".($page+1)." class=\"link_button\">>>></a>";
	}
	$out.="</td>";
	$out.="</tr>";  
	$out.="</table>";
	$out.="</div>";	
	return $out;
}

function getFunctions($imobilid){
		return '<a href="'.$this->getUrlWithSpecialCharsConverted('add.php','id='.$imobilid).'"><img src="'.Config::$commonsite.'/img/view.jpg" style="border:0;align:middle" alt="deschide anutul"></a>';
}
function getAlbum($row){
	if (is_numeric($row->image_id)){
		return '<a href="'.$this->getUrlWithSpecialCharsConverted('add.php','id='.$row->id).'"><img src="'.Config::$commonsite.'/img/photo.gif" style="border:0;align:middle" alt="deschide anutul cu foto"></a>';
		
	} else {
		return '';
	}
}
function getMap($row){
	if (($row->lat!=0)&&($row->lng!=0)){
		return '<a href="'.$this->getUrlWithSpecialCharsConverted('add.php','id='.$row->id).'"><img src="'.Config::$commonsite.'/img/map.png" style="border:0;align:middle" alt="deschide anutul cu harta"></a>';
	} else {
		return "";
	}
}
function getAdress($row){
		if ($row->municipiu){
			$address="m. ".$row->raion_name;
		} else {
			$address="r. ".$row->raion_name;
		}
		if ($row->localitate_name!=""){
				$address.=", ".$row->localitate_tip." ".$row->localitate_name;
		}
		if ($row->sector_name!=""){
				$address.=", sect. ".$row->sector_name;
		}
		return $address;
}
function getTelefon($row){
		$telefon="";
		if ($row->contact_phone!=""){
			$telefon.=$row->contact_phone.", ";
		}
		if ($row->contact_mobile!=""){
			$telefon.=$row->contact_mobile;
		}
		return $telefon;
}
function getPret($row){
		$pret="";
		if ($row->pret!=0){
			$pret=number_format($row->pret,0,"."," ")." ".$row->valuta_name."/".$row->masura_name;
		}
		return $pret;
}
function getAria($row){
		$aria="";
		if ($row->aria_lot!=0){
			if ($row->aria_lot!=0){
				$aria.=number_format($row->aria_lot,1,"."," ")." ".$row->aria_masura_name;	
			} else {
				$aria.="";
			}
		} else {
			if ($row->aria_totala!=0){
				$aria.=number_format($row->aria_totala,1,"."," ")." m2";	
			} else {
				$aria.="";
			}
		}
		return $aria;
}
function getAriaTotala($row){
		$ariatotala="";
		if ($row->aria_totala!=0){
			$ariatotala.=number_format($row->aria_totala,1,"."," ");
		}
		return $ariatotala;
}
	function getLocation(){	
		$lsrs="";
		if (isset($this->lsearch)){
			$l=new Location();
			$r=new Raion();
			$ls=DBManager::doSql("SELECT `localitate`.id as location_id, `localitate`.name as location_name  FROM `localitate` WHERE `localitate`.deleted=0 AND `localitate`.name like '%".$this->lsearch."%' LIMIT 0,30");
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
		$out='<div id="location" class="container groupbox" style="margin:0px;margin-top:2px;font-size:85%;">';
		$out.='<form id="locationform" name="locationform" method="post">';
		$out.='<div id="location-raion">Municipiu/Raion:'.$this->getRaionDropDown(User::getChirieCurrentRaion()->id).'</div>';
		//if (User::getImobilCurrentRaion()->id!=0){
			$out.='<div id="location-localitate">Oras/Sat:'.$this->getLocationDropDown(User::getChirieCurrentRaion()->id,User::getChirieCurrentLocation()->id).'</div>';
		//}
		//if (User::getImobilCurrentLocation()->id!=0){
			$out.='<div id="location-sector">Sector:'.$this->getSectorDropDown(User::getChirieCurrentLocation()->id,User::getChirieCurrentSector()->id).'</div>';
		//}
		//$out.='<div id="location-search-label">Cauta Localitate:</div>';
		$out.='<div id="location-search-box">Cauta Localitate:<input type="text" name="lsearch" value="'.(isset($this->lsearch)?$this->lsearch:'').'"><input type="submit" class="button" value="Cauta"><br>'.$lsrs.'</div>';
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
$p=new Properties();
//phpinfo();
?>
