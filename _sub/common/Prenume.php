<?php
/*
 * Created on 10 Jul 2013
 *
 */
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
}

?>
