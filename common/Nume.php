<?php
/*
 * Created on 10 Jul 2013
 *
 */
class Nume extends DBManager {
	public $id;
	public $name;
	public $suma;
	public $contor;	
	function getTableName(){
		return "family";
	}
	function getTop100Locations(){
		$sql="select raion.id as raion_id,municipiu,raion.name as raion_name,localitate.id as localitate_id,oras,localitate.name as localitate_name,p.contor from (SELECT localitateid, count(*) as contor FROM `person` where lastnameid='$this->id' group by localitateid) as p
inner join localitate on localitate.id=p.localitateid inner join raion on localitate.raion_id=raion.id order by p.contor desc limit 0,100";
		$ls=$this->doSql($sql);
		return $ls;
	}
	function getLocations(){
		$sql="select raion.id as raion_id,municipiu,raion.name as raion_name,localitate.id as localitate_id,oras,localitate.name as localitate_name,localitate.lat as localitate_lat,localitate.lng as localitate_lng,p.contor from (SELECT localitateid, count(*) as contor FROM `person` where lastnameid='$this->id' group by localitateid) as p
		inner join localitate on localitate.id=p.localitateid inner join raion on localitate.raion_id=raion.id order by p.contor desc";
		$ls=$this->doSql($sql);
		return $ls;
	}	
	function getTop100NamesGeograficlyLocated(){
		$sql="SELECT lastnameid as id, name, count FROM (SELECT lastnameid, COUNT( DISTINCT localitateid ) AS count FROM person GROUP BY lastnameid ) AS c INNER JOIN family ON c.lastnameid = family.id ORDER BY count DESC  limit 0,100";
		$ls=$this->doSql($sql);
		return $ls;
	}
	function getTopNamesByLocation($locationid){
		$sql="select id, name, counter from family inner join (SELECT lastnameid,count(*) as counter FROM person WHERE localitateid=".$locationid." group by lastnameid) as c on family.id=c.lastnameid ORDER BY counter DESC limit 0,50";
		$ls=$this->sql($sql);
		return $ls;
	}
	function getFamiliesNumber(){
		$sql="select count(*) as counter from person WHERE lastnameid=".$this->id;
		$ls=$this->doSql($sql);
		return $ls[0]->counter;
	}
	function getLacalitiesNumber(){
		$sql="select count(distinct localitateid) as counter from person WHERE lastnameid=".$this->id;
		$ls=$this->doSql($sql);
		return $ls[0]->counter;
	}
	public static function getTopFamiliiList($currentPage){
		$sql="select `id`, `name`, `suma`, `contor`, `deleted` from `family` where deleted=0 order by suma desc";
		return Nume::getTopNumeList($currentPage, $sql);
		
	}
	public static function getTopFamiliiAmplasateGeograficList($currentPage){
		$sql="SELECT lastnameid as id, name, count as suma FROM (SELECT lastnameid, COUNT( DISTINCT localitateid ) AS count FROM person GROUP BY lastnameid ) AS c INNER JOIN family ON c.lastnameid = family.id ORDER BY suma desc, name";
		return Nume::getTopNumeList($currentPage, $sql);
	
	}	
	public static function getTopNumeList($currentPage,$sql){
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$table->setPagination(false);
		$table->setCurrentPage($currentPage);
		$table->setRowsPerPage(100);
		$table->setSql($sql);
	
		$navigationlink=function() use ($currentPage){
			return $currentPage->getUrlWithSpecialCharsConverted("index.php");
		};
		$table->setNavigationLink($navigationlink);
	
		$namelink=function($row) use ($currentPage){
				
			$url=$currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$row->id);
			return '<a href="'.$url.'">'.$row->name.'</a>';
		};
		$table->addField(new TableField(1, "Nume de familie", "name", "text-align: left;width:60%",$namelink));
		$table->addField(new TableField(2, "Numarul total de familii", "suma", "text-align: center;width:30%",""));
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	
	}	
	
}

?>
