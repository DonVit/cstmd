<?php
class PropertyViewTable extends Table {
	public function __construct($currentPage,$userId=0){
		parent::__construct();
		$this->setCurrentPage($currentPage);
		$view=function($row) use ($currentPage){
			return "<a href=\"".PropertyViewTable::getPropertyUrl($row)."\"><img src=\"".Config::$commonsite."/img/view.jpg\" border=0 align=\"middle\"></a>";		
		};
		$edit=function($row) use ($currentPage){
			return '<a href="'.PropertyViewTable::getSiteUrl($row).'/add.php?id='.$row->id.'"><img src="'.Config::$commonsite.'/img/edit.png" border=0 align="middle" alt="Modifica"></a>';				
		};
		$reload=function($row) use ($currentPage){
			return '<a href="'.PropertyViewTable::getSiteUrl($row).'/add.php?action=republish&id='.$row->id.'"><img src="'.Config::$commonsite.'/img/reload.png" border=0 align="middle" alt="Republica"></a>';
		};		
		$delete=function($row) use ($currentPage){
			return '<a href="'.PropertyViewTable::getSiteUrl($row).'/add.php?action=delete&id='.$row->id.'"><img src="'.Config::$commonsite.'/img/delete.png" border=0 align="middle" alt="Sterge"></a>';
		};		
		$album=function($row) use ($currentPage){
			if (is_numeric($row->image_id)){
				return "<a href=\"".PropertyViewTable::getPropertyUrl($row)."\"><img src=\"".Config::$commonsite."/img/photo.gif\" border=0 align=\"middle\"></a>";
			} else {
				return "";
			}
		};
		$map=function($row) use ($currentPage){
			if (($row->lat!=0)&&($row->lng!=0)){
				return "<a href=\"".PropertyViewTable::getPropertyUrl($row)."\"><img src=\"".Config::$commonsite."/img/map.png\" border=0 align=\"middle\"></a>";
			} else {
				return "";
			}
		};
		$datapub=function($row) use ($currentPage){
			return date("d/m/y", strtotime($row->data));
		};
		$tipanunt=function($row) use ($currentPage){
			return $row->scop_name;
		};
		$tipimobil=function($row) use ($currentPage){
			return $row->tipimobil_name." - ".$row->subtipimobil_name;
		};
		$address=function($row) use ($currentPage){
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
		};
		$telefon=function($row) use ($currentPage){
			$telefon="";
			if ($row->contact_phone!=""){
				$telefon.=$row->contact_phone.", ";
			}
			if ($row->contact_mobile!=""){
				$telefon.=$row->contact_mobile;
			}
			return $telefon;
		};
		$pret=function($row) use ($currentPage){
			$pret="";
			if ($row->pret!=0){
				$pret=number_format($row->pret,0,"."," ")." ".$row->valuta_name."/".$row->masura_name;
			}
			return $pret;
		};
		$aria=function($row) use ($currentPage){
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
		};
		$ariatotala=function($row) use ($currentPage){
			$ariatotala="";
			if ($row->aria_totala!=0){
				$ariatotala.=number_format($row->aria_totala,1,"."," ");
			}
			return $ariatotala;
		};
		
		$this->setShowNrOrd(false);
		$fieldCounter=0;
		$roleId=User::getCurrentUser()->role_id;
		$this->addField(new TableField($fieldCounter++, "Link", "id", "text-align: center;width1:10%",$view));
		
		if (($userId!=0)||($roleId==2)){
			$this->addField(new TableField($fieldCounter++, "Edt", "id", "text-align: center;width1:10%",$edit));
			$this->addField(new TableField($fieldCounter++, "Rep", "id", "text-align: center;width1:10%",$reload));
			$this->addField(new TableField($fieldCounter++, "Dels", "id", "text-align: center;width1:10%",$delete));
		}
		$this->addField(new TableField($fieldCounter++, "Foto", "id", "text-align: center;width1:10%",$album));
		$this->addField(new TableField($fieldCounter++, "Map", "id", "text-align: center;width1:10%",$map));
		$this->addField(new TableField($fieldCounter++, "Data Pub.", "id", "text-align: center;width1:10%",$datapub));
		$this->addField(new TableField($fieldCounter++, "Tip Anunt", "id", "text-align: left;width1:10%",$tipanunt));
		$this->addField(new TableField($fieldCounter++, "Tip Imobil", "id", "text-align: left;width1:10%",$tipimobil));
		$this->addField(new TableField($fieldCounter++, "Adresa", "id", "text-align: left;width1:10%",$address));
		$this->addField(new TableField($fieldCounter++, "Aria", "id", "text-align: right;width1:10%",$aria));
		$this->addField(new TableField($fieldCounter++, "Pret", "id", "text-align: right;width1:10%",$pret));
		
	}
	public static function getPropertyUrl($row){
		return PropertyViewTable::getSiteUrl($row)."/property.php?id=".$row->id;
	}
	public static function getSiteUrl($row){
		$url=Config::$imobilsite;
		if (($row->scop_id==2)||($row->scop_id==4)){
			$url=Config::$chiriesite;
		}
		return $url;
	}	
}
?>
