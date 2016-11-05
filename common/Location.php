<?php
class Location extends DBManager {
	public $id;
	public $parent_id;
	public $raion_id;
	public $raion_center;	
	public $statut;
	public $tip;
	public $oras;
	public $name;
	public $name_ro;
	public $lat;
	public $lng;
	public $elevation;
	public $p;
	public $m;
	public $f;
	public $note;
	public $data;
	public $contor;
	public $order;
// 	public function __construct() {
// 		parent::__construct();
// 		Logger::setLogs("xyzparent_id".$this->parent_id);
// 	}
	function getTableName(){
		return "localitate";
	}
	function getLatShort(){
		return substr($this->lat,0,7);
	}
	function getLngShort(){
		return substr($this->lng,0,7);
	}
	public function getFullName(){
		$fn="";
		if (isset($this->id)){
			if ($this->oras==1){
				$fn="or. ".$this->getName();
			}else{
				$fn="s. ".$this->getName();
			}
		}
		return $fn;
	}
	public function getFullNameDescription(){
		$fn="";
		if (isset($this->id)){
// 			if ($this->oras==1){
// 				$fn="Orasul ".$this->getName();
// 			}else{
// 				$fn="Satul ".$this->getName();
// 			}
			$fn=Location::getLocationPrefix($this,$this->oras)." ".$this->getName();
		}
		return $fn;
	}
	public static function getLocationPrefix($parent,$oras){
		if ($oras==1){
			$fn='Orasul';
		}else{
			$fn='Satul';
		}
		return $fn;
	}	
	public function getPrimariaName(){
		$fn="";
		if (isset($this->id)){
			if ($this->oras==1){
				$fn="Primaria Orasului ".$this->getName();
			}elseif ($this->isComuna()){
				$fn="Primaria Comunei ".$this->getName();
				} else {
				$fn="Primaria Satului ".$this->getName();
			}	
			
		}
		return $fn;
	}
	public static function getPrimariaPrefix($parent,$oras){
		$fn="Primaria ";
		if (isset($this->id)){
			if ($this->oras==1){
				$fn.="Orasului ".$this->getName();
			}elseif ($this->isComuna()){
				$fn.="Comunei ".$this->getName();
			} else {
				$fn.="Satului ".$this->getName();
			}
				
		}
		return $fn;
	}	
	public function isComuna(){
		$r=false;
		if ($this->oras!=1){
			$o=DBManager::doSql("select * from localitate where parent_id=".$this->id);
			if (!is_null($o)){
				$r=true;
			}		
		}
		return $r;
	}
	function getPrimarieLocation(){
		$rt=$this;
		Logger::setLogs("xyz_id".$this->id);
		Logger::setLogs("xyzparent_id".$this->parent_id);
		Logger::setLogs("xyzraion_id".$this->raion_id);
		if (($this->statut==6)||($this->statut==9)){
			Logger::setLogs("xyzparent_id".$this->parent_id);
			$l=new Location();
			$l->loadById($this->parent_id);
			$rt=$l;
		}
		return $rt;
	}		
	public function getRaion(){
		$r=new Raion();
		$r->loadById($this->raion_id);
		return $r;
	}
	public function getLocations(){
		$l=new Location();
		$ls=$l->getAll("parent_id=".$this->id,"oras desc, name");
		return $ls;
	}
	public function getParentLocation(){
		$l=new Location();
		$l->loadById($this->parent_id);
		return $l;
	}			
	public static function getTopFirstLocationByRaionId($raionid){
		$o=DBManager::doSql("select * from localitate where raion_id=".$raionid." order by oras desc, `order` limit 0,1");
		if (!is_null($o)){
			return $o[0];
		} else {	
			return null;
		}		
	}
	public static function getLocationDropDown($raionid,$localitateid,$style=''){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$raionid,"`order`,`oras` desc,`name`");
		$out='<select id="localitate_id" name="localitate_id" style="'.$style.'" size="1" onchange="javascript:WizardOnDropDownChange()">';
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->id==$localitateid){
					$out.= "<option value=".$ll->id." selected>".$ll->tip." ".$ll->name_ro."</option>";
				} else {
					$out.= "<option value=".$ll->id.">".$ll->tip." ".$ll->name_ro."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}	
	function getLocationsInRadius(){	
		$sql="SELECT localitate.*, (6371*acos(cos(radians($this->lat))*cos(radians(lat))*cos(radians(lng)-radians($this->lng))+sin(radians($this->lat))*sin(radians(lat)))) AS distance FROM localitate where id!=$this->id and (6371*acos(cos(radians($this->lat))*cos(radians(lat))*cos(radians(lng)-radians($this->lng))+sin(radians($this->lat))*sin(radians(lat))))<10 ORDER BY distance ";
		$ls=$this->doSql($sql);
		return $ls;
	}
	function getPrimariiInRadius(){
		$sql="SELECT localitate.*, (6371*acos(cos(radians($this->lat))*cos(radians(lat))*cos(radians(lng)-radians($this->lng))+sin(radians($this->lat))*sin(radians(lat)))) AS distance FROM localitate inner join al_primari on localitate.id=al_primari.localitate_id where al_primari.alegeri_id='2015' and localitate.id!=$this->id and (6371*acos(cos(radians($this->lat))*cos(radians(lat))*cos(radians(lng)-radians($this->lng))+sin(radians($this->lat))*sin(radians(lat))))<10 ORDER BY distance ";
		$ls=$this->doSql($sql);
		return $ls;
	}	
	function getLocationsWithSameName(){	
		$sql="SELECT localitate.* FROM localitate where id!=$this->id and name='$this->name'";
		$ls=$this->doSql($sql);
		return $ls;
	}
	function getChildLocations(){
		
		$l=new Location();
		$ls=$l->getAll("id=$this->id or parent_id=".$this->id,"oras desc, p desc, name");
		return $ls;		
	}
	function getPrimariePopulation(){
		$sql="SELECT sum(p) as p FROM localitate where id=$this->id or parent_id=".$this->id;
		$ls=$this->doSql($sql);
		return $ls[0]->p;
	}
	function getPrimarName(){
		$sql="SELECT * FROM al_primari where alegeri_id='2015' and localitate_id=".$this->id;
		$ls=$this->doSql($sql);
		return $ls[0];
	}
	function getPrimarPartid(){
		$sql="SELECT a2.* FROM al_primari as a1 inner join al_partid as a2 on a1.alegeri_id=a2.alegeri_id and a1.partid=a2.partidcod where a1.alegeri_id='2015' and a1.localitate_id=".$this->id;
		$ls=$this->doSql($sql);
		return $ls[0];
	}	
	function getPrimarieConsilieri(){
		$sql="SELECT * FROM al_consilierilocali where alegeri_id='2015' and localitate_id=".$this->id." order by nr";
		$ls=$this->sql($sql);
		return $ls;
	}
	function getPrimarieConsilieriTotal(){
		$sql="SELECT count(*) as c FROM  al_consilierilocali where alegeri_id='2015' and localitate_id=".$this->id;
		$ls=$this->doSql($sql);
		return $ls[0]->c;
	}
	function getPrimarieConsilieriPerPartid(){
		$sql="SELECT partid,count(*) as c FROM al_consilierilocali where alegeri_id='2015' and localitate_id=".$this->id." group by partid order by 2 desc";
		$ls=$this->sql($sql);
		return $ls;
	}						
	function getDistanceToLocation($locationid){	
		$sql="SELECT * FROM localitate_distance where `from`=$this->id and `to`=$locationid";
		$ls=$this->doSql($sql);
		return $ls[0]->distance;
	}
	function getContacts(){
		$sql="SELECT xcompany.type,xsubdivizion.new_name as SubdivizionName, xsubsector.new_name as SectorName,phoneprefix,phonenumber FROM xsubcompany inner join xcompany on xsubcompany.companyid=xcompany.id left join xsubdivizion on xsubcompany.subdivizionid=xsubdivizion.id left join xsubsector on xsubcompany.subsectorid=xsubsector.id where localitateid=".$this->id." and xcompany.type in ('Primaria','Scoala','Gradinita','Medic','Politia','Casa De Cultura','Posta','Biblioteca','Banca','Biserica','Gimnaziu') order by xcompany.type LIMIT 0,100";
		$ls=$this->sql($sql);
		$out='';
		if (count($ls)){
		$table=new Table();
		$table->setDataSet($ls);
		$contactvalue=function($row){
			return $row->type.' ('.$row->SectorName.')';
		};
		$table->addField(new TableField(1, "Contact", "type", "width: 70%;",$contactvalue));
		$phonevalue=function($row){
			return '0-'.$row->phoneprefix.'-'.$row->phonenumber;
		};
		$table->addField(new TableField(2, "Telefon", "phoneprefix", "text-align: center;",$phonevalue));
	
		$out.=$table->show();
	
		}
		return $out;
	}	
	
	function getName(){						
		
		$fn="name_".$this->getLang()->name;
		if ($this->isMember($fn)){
			return $this->$fn;
		} else {
			return $this->name;
		}		
	}
	function getData(){
		//return System::getDate(date_parse($this->data));
		return substr($this->data,0,10);
	}
	function getPrefixes(){
		$o=DBManager::doSql("SELECT substring(prefix,1,3) as prefix FROM localitate l INNER JOIN prefix p ON l.id = p.localitate_id where l.id=".$this->id." group by substring(prefix,1,3) order by prefix");
		if (!is_null($o)){
			return $o;
		} else {	
			return null;
		}
	}
	function finLocationsInText($text){
		
	}		
	function getOraseList(){
		return $this->sql("select * from localitate where oras=1 order by name");
	}
	function getTopSusLocalitati(){
		return $this->sql("select l.id as l_id, l.oras as l_oras, l.name as l_name, l.elevation as l_elevation, r.id as r_id, r.municipiu as r_municipiu, r.name as r_name from localitate l inner join raion r on l.raion_id=r.id order by l.elevation desc limit 0,50");
	}
	function getTopJosLocalitati(){
		return $this->sql("select l.id as l_id, l.oras as l_oras, l.name as l_name, l.elevation as l_elevation, r.id as r_id, r.municipiu as r_municipiu, r.name as r_name from localitate l inner join raion r on l.raion_id=r.id where l.elevation>0 order by l.elevation limit 0,50");
	}
	function getTopUpPopLocalitati(){
		return $this->sql("select l.id as l_id, l.oras as l_oras, l.name as l_name, l.p as l_p, r.id as r_id, r.municipiu as r_municipiu, r.name as r_name from localitate l inner join raion r on l.raion_id=r.id where l.p>0 order by l.p desc limit 0,50");
	}
	

	
	public static function getListaOrase($currentPage){
		$sql="select l.* from localitate l where l.oras=1 order by l.name";	
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$table->setRowsPerPage(100);
		$table->setSql($sql);
		$table->setPagination(false);
				
		$namelink=function($row) use ($currentPage){
			$url=$currentPage->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate&id=".$row->id);
			return '<a href="'.$url.'">'.Location::getLocationPrefix($currentPage,$row->oras)." ".$row->name.'</a>';
		};
		$table->addField(new TableField(1, "Denumire", "name", "width:85%",$namelink));
		//$table->addField(new TableField(2, "Total locuitori", "p", "text-align: center;",""));
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	
	}
	
	
	public static function getTopSusJosLocalitati($currentPage, $susjos){
		$sql="select l.id as l_id, l.oras as l_oras, l.name as l_name, l.elevation as l_elevation, r.id as r_id, r.municipiu as r_municipiu, r.name as r_name from localitate l inner join raion r on l.raion_id=r.id where l.elevation>0 order by l.elevation";
		if ($susjos=='sus'){
			$sql.=" desc";
		}		

		$out='';
		$out.='<div class="groupboxtable">';

		$table=new Table();
		$navigationlink=function() use ($currentPage,$susjos){
			return $currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewtop".$susjos."localitati");
		};
		$table->setNavigationLink($navigationlink);
		
		
		if (!isset($currentPage->page)){
			$currentPage->page=0;
		}
		$table->setPage($currentPage->page);
		$table->setSql($sql);
				
		$namelink=function($row) use ($currentPage,$susjos){
			$urll=$currentPage->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate&id=".$row->l_id);
			$urlr=$currentPage->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewraion&id=".$row->r_id);
			return '<a href="'.$urll.'">'.Location::getLocationPrefix($currentPage,$row->l_oras)." ".$row->l_name.'</a> din <a href="'.$urlr.'">'.Raion::getRaionPrefix($currentPage, $row->r_municipiu)." ".$row->r_name."</a>";
		};
		$table->addField(new TableField(1, "Denumire", "name", "width:65%",$namelink));
		$table->addField(new TableField(2, "Altitudinea (m.)", "l_elevation", "text-align: center;width:20%",""));

		$out.=$table->show();

		$out.="</div>";

		return $out;
	}	
	
	public static function getTopUpDownPopLocalitati($currentPage, $updown){
		
		$sql="select l.id as l_id, l.oras as l_oras, l.name as l_name, l.p as l_p, r.id as r_id, r.municipiu as r_municipiu, r.name as r_name from localitate l inner join raion r on l.raion_id=r.id where l.p>0 order by l.p";
		if ($updown=='up'){
		$sql.=" desc";
		}
		
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$navigationlink=function() use ($currentPage,$updown){
			return $currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewtop".$updown."poplocalitati");
		};
		$table->setNavigationLink($navigationlink);
		
		
		if (!isset($currentPage->page)){
			$currentPage->page=0;
		}
		$table->setPage($currentPage->page);
		$table->setSql($sql);
		
		$namelink=function($row) use ($currentPage,$updown) {
			$urll=$currentPage->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate&id=".$row->l_id);
			$urlr=$currentPage->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewraion&id=".$row->r_id);
			return '<a href="'.$urll.'">'.Location::getLocationPrefix($currentPage,$row->l_oras)." ".$row->l_name.'</a> din <a href="'.$urlr.'">'.Raion::getRaionPrefix($currentPage, $row->r_municipiu)." ".$row->r_name."</a>";
		};
						
		$poplink=function($row){
			return number_format($row->l_p, 0, ',', ' ');
		};
		
		$table->addField(new TableField(1, "Denumire", "name", "width:65%",$namelink));
		$table->addField(new TableField(2, "Populatie", "l_p", "text-align: center;width:20%",$poplink));
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	}	
	
	public static function getTopLocalitatiNationality($currentPage, $nationality){
	
		$sql="select l.id as l_id, l.oras as l_oras, l.name as l_name, l.p as l_p, r.id as r_id, r.municipiu as r_municipiu, r.name as r_name, l.p as l_p, p.".$nationality." as l_".$nationality." from localitate l inner join raion r on l.raion_id=r.id inner join popnat p on l.id=p.id where ".$nationality.">0 order by ".$nationality." desc";
	
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$navigationlink=function() use ($currentPage,$nationality){
			return $currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewtoplocalitati".$nationality);
		};
		$table->setNavigationLink($navigationlink);
	
		if (!isset($currentPage->page)){
			$currentPage->page=0;
		}
		$table->setPage($currentPage->page);
		$table->setSql($sql);
	
		$namelink=function($row) use ($currentPage,$nationality){
			$urll=$currentPage->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate&id=".$row->l_id);
			$urlr=$currentPage->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewraion&id=".$row->r_id);
			return '<a href="'.$urll.'">'.self::getLocationPrefix($currentPage,$row->l_oras)." ".$row->l_name.'</a> din <a href="'.$urlr.'">'.Raion::getRaionPrefix($currentPage, $row->r_municipiu)." ".$row->r_name."</a>";		
		};
		
		$popNrLink=function($row) use ($currentPage,$nationality){
				$col="l_".$nationality;
				return number_format($row->$col, 0, ',', ' ');
			};
		$popProcLink=function($row) use ($currentPage,$nationality){
				$col="l_".$nationality;
				return number_format(($row->$col/$row->l_p)*100, 0, ',', ' ');
			};
		$popTotLink=function($row) use ($currentPage,$nationality){
				return number_format($row->l_p, 0, ',', ' ');
			};		
			
		$table->addField(new TableField(1, "Denumire localitate", "name", "width:52%",$namelink));
		$table->addField(new TableField(2, "Nr. ".ucfirst($nationality), "l_".$nationality, "text-align: center;width:12%",$popNrLink));
		$table->addField(new TableField(2, "% ".ucfirst($nationality), "l_".$nationality, "text-align: center;width:12%",$popProcLink));
		$table->addField(new TableField(2, "Total Populatie", "l_p", "text-align: center;width:12%",$popTotLink));

		$out.=$table->show();
		$out.="</div>";
		return $out;
	}	

}
?>
