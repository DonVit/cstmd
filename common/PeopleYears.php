<?php
class PeopleYears extends DBManager {
	public $id;
	public $raion_id;
	public $localitate_id;
	public $years_range;
	public $years_count;
	public $years_percent;

	function getTableName(){
		return "ap_people_years";
	}


	public static function getPopulationVeiw($currentPage, $raion_id, $location_id){
		$sql='select years_range, years_count, years_percent from ap_people_years where raion_id='.$raion_id.' and localitate_id='.$location_id.' order by years_range';
		
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$table->setPagination(false);
		$table->setRowsPerPage(100);
		$table->setSql($sql);
	
		$table->addField(new TableField(1, "Interval varste (ani)", "years_range", "text-align: left;width:50%",""));
		$table->addField(new TableField(2, "Nr. Locuitori", "years_count", "text-align: center;width:20%",""));
		$table->addField(new TableField(3, "% de Locuitori", "years_percent", "text-align: center;width:20%",""));
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	}
}

?>
