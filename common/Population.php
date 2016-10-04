<?php
/*
 * Created on 27 Feb 2009
 *
 */


class Population extends DBManager {
	public $id;
	public $total;
	public $moldoveni;
	public $ucraineni;
	public $rusi;
	public $gagauzi;
	public $bulgari;
	public $romani;
	public $evrei;
	public $polonezi;
	public $tigani;
	public $altele;

	function getTableName(){
		return "popnat";
	}
	function getPopulationByRaion($raionid){
		$sql="SELECT ".$raionid." as id, sum(total) as total,sum(moldoveni) as moldoveni,sum(ucraineni) as ucraineni,sum(rusi) as rusi,sum(gagauzi) as gagauzi,sum(bulgari) as bulgari ,sum(romani) as romani,sum(evrei) as evrei,sum(polonezi) as polonezi,sum(tigani) as tigani,sum(altele) as altele, 0 as deleted FROM `popnat` inner join localitate on popnat.id=localitate.id where raion_id=".$raionid;
		$o=Population::doSql($sql);
		return $o;
	}

	public static function getPopulationVeiwByRaion($currentPage, $raionId){

		$filter="inner join localitate on popnat.id=localitate.id where raion_id=$raionId";
		
		return Population::getPopulationVeiw($currentPage, $filter);
	}
	
	public static function getPopulationVeiwByLocalitate($currentPage, $localitateId){
	
		$filter="where popnat.id=$localitateId";
	
		return Population::getPopulationVeiw($currentPage, $filter);
	}	

	public static function getPopulationVeiw($currentPage, $filter){
		$sql='select t.* from (';
		$sql.='SELECT "Moldoveni/Romani" as Nationalitate, "008000" as color, sum(total) as Total, sum(moldoveni+romani) as totalNationalitate FROM `popnat` '.$filter.' union ';
		$sql.='SELECT "Ucraineni" as Nationalitate, "224499" as color, sum(total) as Total, sum(ucraineni) as totalNationalitate FROM `popnat` '.$filter.' union ';
		$sql.='SELECT "Rusi" as Nationalitate, "FF0000" as color, sum(total) as Total, sum(rusi) as totalNationalitate FROM `popnat` '.$filter.' union ';
		$sql.='SELECT "Gagauzi" as Nationalitate, "FF9900" as color, sum(total) as Total, sum(gagauzi) as totalNationalitate FROM `popnat` '.$filter.' union ';
		$sql.='SELECT "Bulgari" as Nationalitate, "AA0033" as color, sum(total) as Total, sum(bulgari) as totalNationalitate FROM `popnat` '.$filter.' union ';
		$sql.='SELECT "Evrei" as Nationalitate, "7777CC" as color, sum(total) as Total, sum(evrei) as totalNationalitate FROM `popnat` '.$filter.' union ';
		$sql.='SELECT "Polonezi" as Nationalitate, "80C65A" as color, sum(total) as Total, sum(polonezi) as totalNationalitate FROM `popnat` '.$filter.' union ';
		$sql.='SELECT "Romi/Tigani" as Nationalitate, "AB8F3C" as color, sum(total) as Total, sum(tigani) as totalNationalitate FROM `popnat` '.$filter.' union ';
		$sql.='SELECT "Altele" as Nationalitate, "AD4949" as color, sum(total) as Total, sum(altele) as totalNationalitate FROM `popnat` '.$filter;
		$sql.=') as t';
		
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$table->setPagination(false);
		$table->setRowsPerPage(100);
		$table->setSql($sql);
	
		$totalNationalitatelink=function($row) use ($currentPage){
			return number_format($row->totalNationalitate, 0, ',', ' ');
		};
		$procentTotalNationalitatelink=function($row) use ($currentPage){
			$rtn=0;
			if ($row->Total!=0){
				$rtn=round($row->totalNationalitate*100/$row->Total,2);
			}
			return $rtn;
		};
	
		$table->addField(new TableField(1, "Nationalitate", "Nationalitate", "text-align: left;width:50%",""));
		$table->addField(new TableField(2, "Nr. Locuitori", "totalNationalitate", "text-align: center;width:20%",$totalNationalitatelink));
		$table->addField(new TableField(3, "% de Locuitori", "totalNationalitate", "text-align: center;width:20%",$procentTotalNationalitatelink));
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	}
}

?>
