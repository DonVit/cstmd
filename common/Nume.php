<?php
class Nume extends DBManager {
	public $id;
	public $name;
	public $suma;
	public $contor;	
	function getTableName(){
		return "family";
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
	
		$namelink=function($row) use ($currentPage){
				
			$url=$currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$row->id);
			return '<a href="'.$url.'">'.$row->name.'</a>';
		};
		$totallink=function($row) use ($currentPage){
			return number_format($row->suma, 0, ',', ' ');
		};		
		$table->addField(new TableField(1, "Nume de familie", "name", "text-align: left;width:60%",$namelink));
		$table->addField(new TableField(2, "Numarul total de familii", "suma", "text-align: center;width:30%",$totallink));
	
		$out.=$table->show();
		
		return $out;
	
		
	}
	public static function getTopFamiliiAmplasateGeograficList($currentPage){
		$sql="SELECT lastnameid as id, name, count as suma FROM (SELECT lastnameid, COUNT( DISTINCT localitateid ) AS count FROM person GROUP BY lastnameid ) AS c INNER JOIN family ON c.lastnameid = family.id ORDER BY suma desc, name";
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
	
		$namelink=function($row) use ($currentPage){
				
			$url=$currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$row->id);
			return '<a href="'.$url.'">'.$row->name.'</a>';
		};
		$totallink=function($row) use ($currentPage){
			return number_format($row->suma, 0, ',', ' ');
		};		
		$table->addField(new TableField(1, "Nume de familie", "name", "text-align: left;width:60%",$namelink));
		$table->addField(new TableField(2, "Numarul de localitati", "suma", "text-align: center;width:30%",$totallink));
	
		$out.=$table->show();
		
		return $out;
	
		
	}	
	public static function getTop100LocationsByFamily($currentPage,$familyId){
		$sql="select raion.id as raion_id,municipiu,raion.name as raion_name,localitate.id as localitate_id,oras,localitate.name as localitate_name,p.contor from (SELECT localitateid, count(*) as contor FROM `person` where lastnameid='$familyId' group by localitateid) as p
		inner join localitate on localitate.id=p.localitateid inner join raion on localitate.raion_id=raion.id order by p.contor desc";
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
	
		$namelink=function($row) use ($currentPage){

			$urll=$currentPage->getUrlWithSpecialCharsConverted(config::$locationssite."/index.php","action=viewlocalitate&id=".$row->localitate_id);
			$urlr=$currentPage->getUrlWithSpecialCharsConverted(config::$locationssite."/index.php","action=viewraion&id=".$row->raion_id);
			$locname='';
			if ($row->oras==1){
				$locname.='<a href="'.$urll.'">or. '.$row->localitate_name.'</a>';
			} else {
				$locname.='<a href="'.$urll.'">sat. '.$row->localitate_name.'</a>';
					
				if ($row->municipiu==1){
					$locname.=' din <a href="'.$urlr.'">m. '.$row->raion_name.'</a>';
				} else {
					$locname.=' din <a href="'.$urlr.'">r. '.$row->raion_name.'</a>';
				}
			}
			return $locname;
			
		};
		$totallink=function($row) use ($currentPage){
			return number_format($row->contor, 0, ',', ' ');
		};		
		$table->addField(new TableField(1, "Nume de Localitate", "localitate_name", "text-align: left;width:60%",$namelink));
		$table->addField(new TableField(2, "Numarul de familii", "contor", "text-align: center;width:30%",$totallink));
	
		$out.=$table->show();
		
		return $out;
	}	
}

?>
