<?php
class Country extends DBManager {
	public $id;
	public $ISO;
	public $Country;		

	function getTableName(){
		return "country";
	}	
	public static function getCountryDropDown($countryid){
		$c=new Country();
		$cs=$c->getAll("","`Country`");
		$out="<select id=\"country_id\" name=\"country_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnDropDownChange()\">";
		if (!is_null($cs)){
			foreach($cs as $cc){
				$name=$cc->Country;
				if ($cc->id==$countryid){
					$out.= "<option value=".$cc->id." selected>".$name."</option>";
				} else {
					$out.= "<option value=".$cc->id.">".$name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}	
}

?>
