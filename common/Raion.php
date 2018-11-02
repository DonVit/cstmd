<?php
class Raion extends DBManager {
	public $id;
	public $municipiu;
	public $name;
	public $name_ro;
	//public $name_en;
	public $lat;
	public $lng;
	public $elevation;
	public $zoom;
	public $note;
	public $contor;
	public $order;	

	public function getName(){
		$fn="name_".$this->getLang()->name;
		Logger::setLogs($fn);
		if ($this->isMember($fn)){
			return $this->$fn;
		} else {
			return $this->name;
		}
	}
	public function getFullName(){
		$fn="";
		if (isset($this->id)){
			if ($this->municipiu==1){
				$fn="m. ".$this->getName();
			}else{
				$fn="r. ".$this->getName();
			}
		}
		return $fn;
	}	
	public function getFullNameDescription(){
		$fn="";
		if (isset($this->id)){
// 			if ($this->municipiu==1){
// 				$fn=$this->getConstants("RaionNameMunicipiu")." ".$this->getName();
// 			}else{
// 				$fn=$this->getConstants("RaionNameRaion")." ".$this->getName();
// 			}
			$fn=Raion::getRaionPrefix($this,$this->municipiu)." ".$this->getName();
		}
		return $fn;
	}
	public static function getRaionPrefix($parent,$municipiu){
		if ($municipiu==1){
			$fn=$parent->getConstants("RaionNameMunicipiu");
		}else{
			$fn=$parent->getConstants("RaionNameRaion");
		}
		return $fn;
	}
	public function getLocations(){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$this->id,"oras desc, name");
		return $ls;
	}
	public function getCentrulRaional(){
		$l=new Location();
		$ls=$l->getAll("raion_center=1 and raion_id=".$this->id,"oras desc, name");
		return $ls[0];
	}
	public function getPrimarii(){
		$l=new Location();
		$ls=$l->getAll("statut in (3,8) and raion_id=".$this->id,"oras desc, name");
		return $ls;
	}		
	public static function getTopFirstRaion(){
		$o=DBManager::doSql("select * from raion order by municipiu desc, `order` limit 0,1");
		if (!is_null($o)){
			return $o[0];
		} else {	
			return null;
		}		
	}
	public static function getRaionDropDown($raionid,$style=''){
		$r=new Raion();
		$rs=$r->getAll("","`municipiu` desc,`order`,`name`");
		$out='<select id="raion_id" name="raion_id" style='.$style.' size="1" onchange="javascript:WizardOnDropDownChange()">';
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
	public static function getRaionLocalitateDropDownAsync($raion,$localitate,$localitateid,$style=''){
		$l=new Location();
		$l->loadById($localitateid);
		$raionid=$l->raion_id;
		
		$r=new Raion();
		$rs=$r->getAll("","`municipiu` desc,`order`,`name`");
		$out='<select id="'.$raion.'" name="'.$raion.'" style="'.$style.'" size="1" onchange="javascript:FillLocationsDropDown(this,\''.$localitate.'\')">';
		if(!is_null($rs)){
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
		
		$ls=$l->getAll("raion_id=".$raionid,"`order`,`oras` desc,`name`");
		$out.='<select id="'.$localitate.'" name="'.$localitate.'" style="'.$style.'" size="1">';
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
	function getLatShort(){
		return substr($this->lat,0,7);
	}
	function getLngShort(){
		return substr($this->lng,0,7);
	}
	function getPopulation(){
		$o=DBManager::doSql("select sum(p) as p from localitate where raion_id=".$this->id);
		if (!is_null($o)){
			return $o[0]->p;
		} else {	
			return null;
		}
	}
	function getPrefixes(){
		$o=DBManager::doSql("SELECT substring(prefix,1,3) as prefix FROM localitate l INNER JOIN prefix p ON l.id = p.localitate_id where raion_id=".$this->id." group by substring(prefix,1,3) order by prefix");
		if (!is_null($o)){
			return $o;
		} else {	
			return null;
		}
	}
	function getRaionsList1(){
		return $this->sql("select *, (select count(*) from localitate where raion_id=raion.id) as contorloc from raion order by municipiu desc, `order`, name");
	}
	public static function getRaionsList($currentPage){
		$sql="select raion.* from raion order by raion.municipiu desc, raion.order, raion.name";	
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$table->setSql($sql);
		$table->setPagination(false);
		$namelink=function($row) use ($currentPage){
			$url=$currentPage->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewraion&id=".$row->id);
			return '<a href="'.$url.'">'.Raion::getRaionPrefix($currentPage,$row->municipiu)." ".$row->name.'</a>';
		};
		$table->addField(new TableField(1, "Denumire", "name", "text-align: left;width:85%",$namelink));
		//$table->addField(new TableField(2, "Localitati", "contorloc", "text-align: center;width:20%",""));
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	}
	public static function getRaionsListForPrimarii($currentPage){
		$sql="select raion.* from raion order by raion.municipiu desc, raion.order, raion.name";
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$table->setSql($sql);
		$table->setPagination(false);
		$namelink=function($row) use ($currentPage){
			$url=$currentPage->getUrlWithSpecialCharsConverted(Config::$primariisite."/index.php","action=viewraion&id=".$row->id);
			return '<a href="'.$url.'">'.Raion::getRaionPrefix($currentPage,$row->municipiu)." ".$row->name.'</a>';
		};
		$table->addField(new TableField(1, "Denumire", "name", "text-align: left;width:85%",$namelink));
		//$table->addField(new TableField(2, "Localitati", "contorloc", "text-align: center;width:20%",""));
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	}
	public static function getPrimariiListByRaion($currentPage, $raionId){
		$sql="select l.*, (select count(*) from localitate where parent_id=l.id) as nr_loc from localitate as l where l.statut in (3,8) and l.raion_id=".$raionId." order by l.oras desc, l.name";
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$table->setPagination(false);
		$table->setRowsPerPage(100);		
		$table->setSql($sql);
				
		$namelink=function($row) use ($currentPage){
			$name="Primaria ";
			$url=$currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewprimarie&id=".$row->id);
			if ($row->oras==1){
				$name.="Orasului ";
			} else {
				if ($row->nr_loc!=0){
					$name.="Comunei ";
				} else {
					$name.="Satului ";
				}
			}
			$name.=$row->name;
			return '<a href="'.$url.'">'.$name.'</a>';
		};
		$contorlink=function($row) use ($currentPage){
			$cnt=1;
			if ($row->raion_center==0){
				$cnt+=$row->nr_loc;
			}
			return $cnt;
		};		
		$table->addField(new TableField(1, "Denumire", "name", "text-align: left;width:70%",$namelink));
		$table->addField(new TableField(2, "Localitati in componenta", "nr_loc", "text-align: center;width:20%",$contorlink));
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	}	
	public static function getLocalitatiListByRaion($currentPage, $raionId){
		$sql="select l.id, l.name, l.oras, l.p from localitate as l where l.raion_id=".$raionId." order by l.oras desc, l.name";
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$table->setPagination(false);
		$table->setRowsPerPage(200);
		$table->setSql($sql);
	
		$namelink=function($row) use ($currentPage){
			$name="Satul ";
			$url=$currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewlocalitate&id=".$row->id);
			if ($row->oras==1){
				$name="Orasul ";
			}
			$name.=$row->name;
			return '<a href="'.$url.'">'.$name.'</a>';
		};
		$nrpoplink=function($row) use ($currentPage){
			$rez = number_format($row->p, 0, ',', ' ');
			if ($row->p==0) {
				$rez = "Nu sunt date";
			}
			return $rez;
		};
		$table->addField(new TableField(1, "Denumire localitate", "name", "text-align: left;width:70%",$namelink));
		$table->addField(new TableField(2, "Nr. Locuitori", "p", "text-align: center;width:20%",$nrpoplink));
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	}
	
}
?>
