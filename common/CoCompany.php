<?php
class CoCompany extends DBManager {
	public $id;
    public $idno_cod_fiscal;
	public $data_inregistrarii;
    public $nume_scurt;
    public $nume_lung;
    public $forma_juridica;
    public $raion_id;
    public $raion;
    public $localitate_id;
    public $localitate;
    public $adresa;
    public $lista_conducatorilor;
    public $lista_fondatorilor;
    public $activitate_nelicentiate;
    public $activitate_licentiate;
    public $statutul;
    public $created_at;
    public $contor;

	function getTableName(){
		return "co_co";
	}	

	public function getCompaniesByRaionAndNotLicencedActivity($raion_id,$activity_id,$orderbycolumns="data_inregistrarii desc",$page=0,$limit=50){
		$sql="SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c inner join co_cocaem_nelicentiate as cc on c.id=cc.company_id where c.raion_id=".$raion_id." and cc.caem_id=".$activity_id." order by ".$orderbycolumns." limit ".$page*$limit.",".$limit;
		$cs=DBManager::sql($sql);
		return $cs;
	}
	public function getNumarCompaniesByRaionAndNotLicencedActivity($raion_id,$activity_id){
		$sql="select count(*) as contor from (SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c inner join co_cocaem_nelicentiate as cc on c.id=cc.company_id where c.raion_id=".$raion_id." and cc.caem_id=".$activity_id.") as tb";
		$cs=DBManager::sql($sql);
		$cnt=0;
		while($row=mysql_fetch_assoc($cs)){
			$cnt=$row["contor"];
		}
		return $cnt;
	}
	public function getCompaniesByLocalitateAndNotLicencedActivity($localitate_id,$activity_id,$orderbycolumns="data_inregistrarii desc",$page=0,$limit=50){
		$sql="SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c inner join co_cocaem_nelicentiate as cc on c.id=cc.company_id where c.localitate_id=".$localitate_id." and cc.caem_id=".$activity_id." order by ".$orderbycolumns." limit ".$page*$limit.",".$limit;
		$cs=DBManager::sql($sql);
		return $cs;
	}
	public function getNumarCompaniesByLocalitateAndNotLicencedActivity($localitate_id,$activity_id){
		$sql="select count(*) as contor from (SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c inner join co_cocaem_nelicentiate as cc on c.id=cc.company_id where c.localitate_id=".$localitate_id." and cc.caem_id=".$activity_id.") as tb";
		$cs=DBManager::sql($sql);
		$cnt=0;
		while($row=mysql_fetch_assoc($cs)){
			$cnt=$row["contor"];
		}
		return $cnt;
	}
	
	public function getCompaniesByRaionAndLicencedActivity($raion_id,$activity_id,$orderbycolumns="data_inregistrarii desc",$page=0,$limit=50){
		$sql="SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c inner join co_cocaem_licentiate as cc on c.id=cc.company_id where c.raion_id=".$raion_id." and cc.caem_id=".$activity_id." order by ".$orderbycolumns." limit ".$page*$limit.",".$limit;
		$cs=DBManager::sql($sql);
		return $cs;
	}
	public function getNumarCompaniesByRaionAndLicencedActivity($raion_id,$activity_id){
		$sql="select count(*) as contor from (SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c inner join co_cocaem_licentiate as cc on c.id=cc.company_id where c.raion_id=".$raion_id." and cc.caem_id=".$activity_id.") as tb";
		$cs=DBManager::sql($sql);
		$cnt=0;
		while($row=mysql_fetch_assoc($cs)){
			$cnt=$row["contor"];
		}
		return $cnt;
	}
	public function getCompaniesByLocalitateAndLicencedActivity($localitate_id,$activity_id,$orderbycolumns="data_inregistrarii desc",$page=0,$limit=50){
		$sql="SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c inner join co_cocaem_licentiate as cc on c.id=cc.company_id where c.localitate_id=".$localitate_id." and cc.caem_id=".$activity_id." order by ".$orderbycolumns." limit ".$page*$limit.",".$limit;
		$cs=DBManager::sql($sql);
		return $cs;
	}
	public function getNumarCompaniesByLocalitateAndLicencedActivity($localitate_id,$activity_id){
		$sql="select count(*) as contor from (SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c inner join co_cocaem_licentiate as cc on c.id=cc.company_id where c.localitate_id=".$localitate_id." and cc.caem_id=".$activity_id.") as tb";
		$cs=DBManager::sql($sql);
		$cnt=0;
		while($row=mysql_fetch_assoc($cs)){
			$cnt=$row["contor"];
		}
		return $cnt;
	}	
	public function getCompaniesByRaion($raion_id,$orderbycolumns="data_inregistrarii desc",$page=0,$limit=50){
		$sql="SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul,created_at FROM co_co as c where c.raion_id=".$raion_id." order by ".$orderbycolumns." limit ".$page*$limit.",".$limit;
		$cs=DBManager::sql($sql);
		return $cs;
	}
	public function getNumarCompaniesByRaion($raion_id){
		$sql="select count(*) as contor from (SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c where c.raion_id=".$raion_id.") as tb";
		$cs=DBManager::sql($sql);
		$cnt=0;
		while($row=mysql_fetch_assoc($cs)){
			$cnt=$row["contor"];
		}
		return $cnt;
	}	
	public function getCompaniesByLocalitate($localitate_id,$orderbycolumns="data_inregistrarii desc",$page=0,$limit=50){
		$sql="SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c where c.localitate_id=".$localitate_id." order by ".$orderbycolumns." limit ".$page*$limit.",".$limit;
		$cs=DBManager::sql($sql);
		return $cs;
	}
	public function getNumarCompaniesByLocalitate($localitate_id){
		$sql="select count(*) as contor from (SELECT  c.id, c.data_inregistrarii, c.nume_scurt, statutul FROM co_co as c where c.localitate_id=".$localitate_id.") as tb";
		$cs=DBManager::sql($sql);
		$cnt=0;
		while($row=mysql_fetch_assoc($cs)){
			$cnt=$row["contor"];
		}
		return $cnt;
	}	
	public function getRaion(){
		$raion=new Raion();
		$raion->loadById($this->raion_id);
		return $raion;
	}
	public function getLocation(){
		$localitate=new Location();
		$localitate->loadById($this->localitate_id);
		return $localitate;
	}	
}

?>
