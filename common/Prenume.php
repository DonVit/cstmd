<?php
class Prenume extends DBManager {
	public $id;
	public $name;
	public $suma;
	public $contor;	
	function getTableName(){
		return "name";
	}
	function getTop100Locations(){
		$sql="select raion.id as raion_id,municipiu,raion.name as raion_name,localitate.id as localitate_id,oras,localitate.name as localitate_name,p.contor from (SELECT localitateid, count(*) as contor FROM `person` where lastnameid='$this->id' group by localitateid) as p
inner join localitate on localitate.id=p.localitateid inner join raion on localitate.raion_id=raion.id order by p.contor desc limit 0,100";
		$ls=$this->doSql($sql);
		return $ls;
	}
	
	function getTop100NamesGeograficlyLocated(){
		$sql="SELECT lastnameid as id, name, count FROM (SELECT lastnameid, COUNT( DISTINCT localitateid ) AS count FROM person GROUP BY lastnameid ) AS c INNER JOIN family ON c.lastnameid = family.id ORDER BY count DESC  limit 0,100";
		$ls=$this->doSql($sql);
		return $ls;
	}
	public static function getTopPrenumePopulareList($currentPage){
		$sql="select `id`, `name`, `suma`, `contor`, `deleted` from `name` where deleted=0 order by suma desc";
		return Prenume::getTopPrenumeList($currentPage, $sql);
	
	}	
	public static function getTopPrenumeList($currentPage,$sql){
		$out='';
	
		$table=new Table();
		$table->setPagination(false);
		$table->setCurrentPage($currentPage);
		$table->setRowsPerPage(100);
		$table->setSql($sql);
	
		$navigationlink=function() use ($currentPage){
			return $currentPage->getUrlWithSpecialCharsConverted("index.php");
		};
		$table->setNavigationLink($navigationlink);
	
		$prenumelink=function($row) use ($currentPage){
			$url=$currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewprenume&id=".$row->id);
			return '<a href="'.$url.'">'.$row->name.'</a>';
		};
		$totallink=function($row) use ($currentPage){
			return number_format($row->suma, 0, ',', ' ');
		};
		
		$table->addField(new TableField(1, "Prenume", "name", "text-align: left;width:60%",""));
		$table->addField(new TableField(2, "Numarul total", "suma", "text-align: center;width:30%",$totallink));
	
		$out.=$table->show();
	
		return $out;
	
	}
	
}

?>
