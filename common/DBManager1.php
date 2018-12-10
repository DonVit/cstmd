<?php
class DBManager1 {
    //Common basic fields
    public $deleted;
    public $createdby;
    public $createddate;
    public $lastupdatedby;
    public $lastupdateddate;
    
    function DBManager(){
    	//foreach(func_get_args() as $k=>$n){
    	//	echo $k."-".$n."|";
    	//}
    }
	function sql($sql){
		Logger::setLogs($sql);
		$result=mysql_query($sql, DBConnection::getConnection());
		if (!$result){
			die('sql query: ['.$sql.'] faild with error: '.mysql_error());
		}
		return $result;
	}
	public static function doSql($sql){
		Logger::setLogs($sql);
		$result=mysql_query($sql, DBConnection::getConnection());
		if (!$result){
			die('sql query: ['.$sql.'] faild with error: '.mysql_error());
		}
		$fields=mysql_num_fields($result);
		$arr=array();
		while($row = mysql_fetch_object($result)){
			$o=new Object();
			for ($i=0; $i < $fields; $i++) {
    			$n=mysql_field_name($result, $i);
    			$o->$n=$row->$n;
			}
			$arr[]=$o;
		}
		if (count($arr)==0){
			return null;
		} else {
			return $arr;
		}
	}
	function save(){
		$f="";
		$v="";
		if (!isset($this->id)){
			//set values to system fields
			if (!isset($this->deleted)){$this->deleted=0;}
			if (!isset($this->createdby)){$this->createdby=User::getCurrentUser()->id;}
			if (!isset($this->createddate)){$this->createddate=System::getCurentDateTime();}
			if (!isset($this->lastupdatedby)){$this->lastupdatedby=User::getCurrentUser()->id;}
			if (!isset($this->lastupdateddate)){$this->lastupdateddate=System::getCurentDateTime();}
			//generate the sql script
			foreach ($this as $name => $value) {
	    		if ($f==""){
	    			$f.=" `".$name."`";
	    			$v.=" '".$value."'";
	    		} else {
	    			$f.=", `".$name."`";
	    			$v.=", '".$value."'";
	    		}
			}
			$sql=" insert into `".$this->getTableName()."` (".$f.") values (".$v.")";
			//execute the sql query
			if ($this->sql($sql)){
				$this->id=mysql_insert_id();
			}
		} else {
			$f="";
			$this->lastupdatedby=User::getCurrentUser()->id;
			$this->lastupdateddate=System::getCurentDateTime();
			foreach ($this as $name => $value) {
	    		if ($f==""){
	    			$f.=" `".$name."`='".$value."'";
	    		} else {
	    			$f.=" ,`".$name."`='".$value."'";
	    		}
			}
			$sql=" update `".$this->getTableName()."` set ".$f." where `id`='".$this->id."'";
			$this->sql($sql);
		}
	}
	function delete(){
		$this->deleted=1;
		$this->save();
	}
	function getById($id){
		$sql="select";
		foreach ($this as $name => $value) {
    		if ($sql=="select"){
    			$sql.=" `".$name."`";
    		} else {
    			$sql.=", `".$name."`";
    		}
		}
		$sql.=" from `".$this->getTableName()."` where id='".$id."'";
	
		$result=$this->sql($sql);

		$o=null;
		while($row = mysql_fetch_object($result)){
			$o=new $this;
			foreach ($o as $name=>$value) {
				$o->$name=$row->$name;
			}		
		}
		return $o;
	}
	function getAll($sqlconditions="",$sqlorders=""){
		$sql="select";
		foreach ($this as $name => $value) {
    		if ($sql=="select"){
    			$sql.=" `".$name."`";
    		} else {
    			$sql.=", `".$name."`";
    		}
		}
		$sql.=" from `".$this->getTableName()."`";
		$sql.=" where deleted=0";
		if ($sqlconditions!=""){
			$sql.=" and ".$sqlconditions;
		}
		if ($sqlorders!=""){
			$sql.=" order by ".$sqlorders;
		}
		return $this->getResult($this->sql($sql));	
	}

	function getResult($result){
		$arr=array();
		while($row = mysql_fetch_object($result)){
			$o=new $this;
			foreach ($o as $name=>$value) {
				$o->$name=$row->$name;
			}		
			$arr[]=$o;
		}
		if (count($arr)==0){
			return null;
		} else {
			return $arr;
		}
	}
	function getClassName(){
		return get_class($this);
	}
	function getTableName(){
		return $this->getClassName();
	}

}
//$c=new DBManager(1,2,3);
?>
