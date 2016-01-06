<?php
class CoCaemNL extends DBManager {
	public $id;
	public $denumire;
	public $cod_caem;
	public $versiune_caem;

	function getTableName(){
		return "co_caem_nelicentiate";
	}	
	public function getActivitatiByRaion($raion_id,$orderbycolumns="3 desc",$page=0,$limit=50){
		$sql="SELECT cn.id, cn.denumire,count(*) as contor FROM co_caem_nelicentiate as cn inner join co_cocaem_nelicentiate as cc on cn.id=cc.caem_id inner join co_co as c on cc.company_id=c.id where c.raion_id=".$raion_id." group by cn.id, cn.denumire order by ".$orderbycolumns." limit ".$page*$limit.",".$limit;
		$cs=DBManager::sql($sql);
		return $cs;
	}
	public function getNumarActivitatiByRaion($raion_id){
		$sql="select count(*) as contor from (SELECT cn.id, cn.denumire,count(*) as contor FROM co_caem_nelicentiate as cn inner join co_cocaem_nelicentiate as cc on cn.id=cc.caem_id inner join co_co as c on cc.company_id=c.id where c.raion_id=".$raion_id." group by cn.id, cn.denumire) as tb";
		$cs=DBManager::sql($sql);
		$cnt=0;
		while($row=mysql_fetch_assoc($cs)){
			$cnt=$row["contor"];
		}
		return $cnt;
	}
	public function getActivitatiByLocalitate($localitate_id,$orderbycolumns="3 desc",$page=0,$limit=50){
		$sql="SELECT cn.id, cn.denumire,count(*) as contor FROM co_caem_nelicentiate as cn inner join co_cocaem_nelicentiate as cc on cn.id=cc.caem_id inner join co_co as c on cc.company_id=c.id where c.localitate_id=".$localitate_id." group by cn.id, cn.denumire order by ".$orderbycolumns." limit ".$page*$limit.",".$limit;
		$cs=DBManager::sql($sql);
		return $cs;
	}
	public function getNumarActivitatiByLocalitate($localitate_id){
		$sql="select count(*) as contor from (SELECT cn.id, cn.denumire,count(*) as contor FROM co_caem_nelicentiate as cn inner join co_cocaem_nelicentiate as cc on cn.id=cc.caem_id inner join co_co as c on cc.company_id=c.id where c.localitate_id=".$localitate_id." group by cn.id, cn.denumire) as tb";
		$cs=DBManager::sql($sql);
		$cnt=0;
		while($row=mysql_fetch_assoc($cs)){
			$cnt=$row["contor"];
		}
		return $cnt;
		
	}	
	public function getActivitatiByCompanie($comapany_id,$limit=50){
		$sql="SELECT cn.id, cn.denumire FROM co_caem_nelicentiate as cn inner join co_cocaem_nelicentiate as cc on cn.id=cc.caem_id inner join co_co as c on cc.company_id=c.id where c.id=".$comapany_id." order by 2 desc limit 0,".$limit;
		$cs=DBManager::doSql($sql);
		return $cs;
	}	
}

?>
