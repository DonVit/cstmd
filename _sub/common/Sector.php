<?php
/*
 * Created on 27 Feb 2009
 *
 */

class Sector extends DBManager {
	public $id;
	public $localitate_id;
	public $name;
	public $note;
	public $order;
	public static function getTopFirstSectorByLocalitateId($localitateid){
		$o=DBManager::doSql("select * from sector where localitate_id=".$localitateid." order by `order` limit 0,1");
		if (!is_null($o)){
			return $o[0];
		} else {	
			return null;
		}		
	}
	public static function getSectorDropDown($localitateid,$sectorid,$style=""){
		$s=new Sector();
		$ss=$s->getAll("localitate_id=".$localitateid,"`order`,`name`");
		$out='<select id="sector_id" name="sector_id" style="'.$style.'" size="1" onchange="javascript:WizardOnSectorDropDownChange()">';
		if (!is_null($ss)){
			foreach($ss as $s){
				if ($s->id==$sectorid){
					$out.= "<option value=".$s->id." selected>".$s->name."</option>";
				} else {
					$out.= "<option value=".$s->id.">".$s->name."</option>";
				}
			}
		
			$out.= "<option value=\"0\" disabled=\"disabled\">---</option>";
			$out.= "<option value=\"-1\">Altul...</option>";
		}
		$out.="</select>";
		$out.="<input type=\"hidden\" id=\"sector_new\" name=\"sector_new\">";
		return $out;
	}	
}

?>
