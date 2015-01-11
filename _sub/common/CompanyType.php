<?php
/*
 * Created on 27 Feb 2009
 *
 */


class CompanyType extends DBManager {
	public $id;
	public $name;
	public $description;
	public $order;
	public $valid;
	public $created_by;
	public $created_date;
	public $modified_by;
	public $modified_date;	
	
	function getTableName(){
		return "company_type";
	}
	public static function getCompanyTypeDropDown($companytypeid){
		$c=new CompanyType();
		$cs=$c->getAll("","`name`");
		$out="<select id=\"company_type_id\" name=\"company_type_id\" class=\"select\" size=\"1\">";
		if (!is_null($cs)){
			foreach($cs as $c){
				if ($c->id==$companytypeid){
					$out.= "<option value=".$c->id." selected>".$c->name."</option>";
				} else {
					$out.= "<option value=".$c->id.">".$c->name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}		
}

?>
