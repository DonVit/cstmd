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
}

?>
