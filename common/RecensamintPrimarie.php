<?php
class RecensamintPrimarie extends DBManager {
	public $id;
	public $an;
	public $raion_id;
	public $primaria_id;
	public $total;
	public $barbati;
	public $femei;
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
		return "recensamint_primarie";
	}
	public static function getWhereClause($an, $raionId, $primariaId){
    		$conditions = array();
    		$wheresql="";
    		if ($an!=0){
    		    array_push($conditions, "an=".$an);
    		}
    		if ($raionId!=0){
    		    array_push($conditions, "raion_id=".$raionId);
    		}
    		if ($primariaId!=0){
    		    array_push($conditions, "primaria_id=".$primariaId);
    		}
            $whereClouse="";
            foreach ($conditions as $condition) {
                if ($whereClouse==""){
                   $whereClouse.=$condition;
                } else {
                    $whereClouse.=" and ".$condition;
                }
            }
            if ($whereClouse!=""){
                $whereClouse="where ".$whereClouse;
            }
            return $whereClouse;
	}
	public static function getPopulationBy($an=0, $raionId=0, $primariaId=0){
    		$sql="select an, sum(total) as total, sum(moldoveni) as moldoveni, sum(ucraineni) as ucraineni, sum(rusi) as rusi, sum(gagauzi) as gagauzi, sum(bulgari) as bulgari, sum(romani) as romani, sum(evrei) as evrei, sum(polonezi) as polonezi,sum(tigani) as tigani,sum(altele) as altele, deleted from `recensamint_primarie`";
    		$sql.=RecensamintPrimarie::getWhereClause($an, $raionId, $primariaId);
            $sql.=" group by an ";

    		$o=RecensamintPrimarie::doSql($sql);
    		return $o;
    	}

	public static function getPopulationVeiw($currentPage, $filter){
		$sql='select t.* from (';
		$sql.='select "Moldoveni/Romani" as Nationalitate, "008000" as color, sum(total) as Total, sum(moldoveni+romani) as totalNationalitate from `popnat` '.$filter.' union ';
		$sql.='select "Ucraineni" as Nationalitate, "224499" as color, sum(total) as Total, sum(ucraineni) as totalNationalitate from `popnat` '.$filter.' union ';
		$sql.='select "Rusi" as Nationalitate, "FF0000" as color, sum(total) as Total, sum(rusi) as totalNationalitate from `popnat` '.$filter.' union ';
		$sql.='select "Gagauzi" as Nationalitate, "FF9900" as color, sum(total) as Total, sum(gagauzi) as totalNationalitate from `popnat` '.$filter.' union ';
		$sql.='select "Bulgari" as Nationalitate, "AA0033" as color, sum(total) as Total, sum(bulgari) as totalNationalitate from `popnat` '.$filter.' union ';
		$sql.='select "Evrei" as Nationalitate, "7777CC" as color, sum(total) as Total, sum(evrei) as totalNationalitate from `popnat` '.$filter.' union ';
		$sql.='select "Polonezi" as Nationalitate, "80C65A" as color, sum(total) as Total, sum(polonezi) as totalNationalitate from `popnat` '.$filter.' union ';
		$sql.='select "Romi/Tigani" as Nationalitate, "AB8F3C" as color, sum(total) as Total, sum(tigani) as totalNationalitate from `popnat` '.$filter.' union ';
		$sql.='select "Altele" as Nationalitate, "AD4949" as color, sum(total) as Total, sum(altele) as totalNationalitate from `popnat` '.$filter;
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
	public static function getPopulationInTimeVeiwByPrimarie($currentPage, $primarie_id){
		$sql='select rp.primaria_id, rp.an, rp.total, r.sursa from recensamint_primarie as rp inner join recensamint as r on rp.an=r.an where rp.primaria_id='.$primarie_id.' order by rp.an desc';
	
		$out='';
	
		$table=new Table();
		$table->setPagination(false);
		$table->setShowNrOrd(false);
		$table->setRowsPerPage(100);
		$table->setSql($sql);
	
		$total=function($row) use ($currentPage){
			return number_format($row->total, 0, ',', ' ');
		};
		
		$sursaLink=function($row) use ($currentPage){
			$rtn=$row->sursa;
			if ($row->an=='1904'){
				$rtn.=' <a href="'.$currentPage->getUrlWithSpecialCharsConverted(Config::$dictionarsite."/index.php","action=viewdictionarbylocalitate&id=".$row->primaria_id).'">Vezi aici</a>';
			}
			return $rtn;
		};
	
		$table->addField(new TableField(1, "Anul", "an", "text-align: center;width:10%",""));
		$table->addField(new TableField(2, "Nr. Locuitori", "total", "text-align: center;width:15%",$total));
		$table->addField(new TableField(3, "Sursa de Date", "sursa", "text-align: left;width:75%",$sursaLink));
	
		$out.=$table->show();
	
		return $out;
	}
}

?>
