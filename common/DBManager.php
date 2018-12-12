<?php
class DBManager extends Object {
    //Common basic fields
    public $deleted;
    //public $createdby;
    //public $createddate;
    //public $lastupdatedby;
    //public $lastupdateddate;
    
    function DBManager(){
    	//foreach(func_get_args() as $k=>$n){
    	//	echo $k."-".$n."|";
    	//}
    }
	function sql($sql){
		Logger::setLogs($sql);
		$result=mysql_query($sql, DBConnection::getConnection());	
		if (!$result){
			DBManager::logsql($sql,'1');
			$n=new WebPage();
			$n->redirect(Config::$errorpage);
			exit;
			//die('sql query: ['.$sql.'] faild with error: '.mysql_error());
		//}else{
		//	DBManager::logsql($sql,'0');
		}
		return $result;
	}
	public static function doSql($sql){
		Logger::setLogs($sql);
		$result=mysql_query($sql, DBConnection::getConnection());
		if (!$result){
			DBManager::logsql($sql,'1');
			$n=new WebPage();
			$n->redirect(Config::$errorpage);
			exit;
			//die('sql query: ['.$sql.'] faild with error: '.mysql_error());
		//} else {
		//	DBManager::logsql($sql,'0');
		}
		if (!(is_bool($result) === true)) {
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
		} else {
			return null;
		}
	}
	public static function doJustSql($sql){
		Logger::setLogs($sql);
		$result=mysql_query($sql, DBConnection::getConnection());
		if (!$result){
			DBManager::logsql($sql,'1');
			$n=new WebPage();
			$n->redirect(Config::$errorpage);	
		}
	}	
	function save(){
		$f="";
		$v="";
		if (!isset($this->id)){
			//set values to system fields
			if (!isset($this->deleted)){$this->deleted=0;}
			//if (!isset($this->createdby)){$this->createdby=User::getCurrentUser()->id;}
			//if (!isset($this->createddate)){$this->createddate=System::getCurentDateTime();}
			//if (!isset($this->lastupdatedby)){$this->lastupdatedby=User::getCurrentUser()->id;}
			//if (!isset($this->lastupdateddate)){$this->lastupdateddate=System::getCurentDateTime();}
			//generate the sql script
			foreach ($this as $name => $value) {
	    		if ($this->isMember($name)){
		    		if ($f==""){
		    			$f.=" `".mysql_real_escape_string($name,DBConnection::getConnection())."`";
		    			$v.=" '".mysql_real_escape_string($value,DBConnection::getConnection())."'";
		    		} else {
						Logger::setLogs('fv='.$name.':'.$value);
		    			$f.=", `".mysql_real_escape_string($name,DBConnection::getConnection())."`";
		    			$v.=", '".mysql_real_escape_string($value,DBConnection::getConnection())."'";
		    			
		    		}
	    		}
			}
			$sql=" insert into `".$this->getTableName()."` (".$f.") values (".$v.")";
			//echo $sql;
			//execute the sql query
			if ($this->sql($sql)){
				$this->id=mysql_insert_id();
			}
		} else {
			$f="";
			//$this->lastupdatedby=User::getCurrentUser()->id;
			//$this->lastupdateddate=System::getCurentDateTime();
			foreach ($this as $name => $value) {
	    		//if ($this->isMember($name)){
		    	//	if ($f==""){
		    	//		$f.=" `".$name."`='".$value."'";
		    	//	} else {
		    	//		$f.=" ,`".$name."`='".$value."'";
		    	//	}
	    		//}
	    		if ($this->isMember($name)){
	    			if ($f==""){
	    				//$f.=" `".$name."`='".$value."'";
	    				$f.=" `".$name."`='".mysql_real_escape_string($value,DBConnection::getConnection())."'";
	    			} else {
	    				//$f.=" ,`".$name."`='".$value."'";
	    				$f.=" ,`".$name."`='".mysql_real_escape_string($value,DBConnection::getConnection())."'";
	    			}
	    		}	    			    		
			}
			$sql=" update `".$this->getTableName()."` set ".$f." where `id`='".$this->id."'";
			$this->sql($sql);
		}
	}
	function delete(){
			$sql=" update `".$this->getTableName()."` set deleted=1 where `id`='".$this->id."'";
			$this->sql($sql);
	}
	function count(){
			if ($this->isMember("contor")){
				$sql=" update `".$this->getTableName()."` set contor=".($this->contor+1)." where `id`='".$this->id."'";
				$this->sql($sql);
			}
	}	
	function count1(){
			if ($this->isMember("contor")){
				$this->contor=$this->contor+1;
				$this->save();
			}
	}	
	function delete1(){
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
		$sql.=" from `".$this->getTableName()."` where id='".$id."' and deleted=0";
	
		$result=$this->sql($sql);
		$num_rows = mysql_num_rows($result);
		$o=null;
		if ($num_rows!=0){
			while($row = mysql_fetch_object($result)){
				$o=new $this;
				foreach ($o as $name=>$value) {
					$o->$name=$row->$name;
				}		
			}
		}
		return $o;
	}
	function loadById($id){
		$sql="select";
		foreach ($this as $name => $value) {
    		if ($sql=="select"){
    			$sql.=" `".$name."`";
    		} else {
    			$sql.=", `".$name."`";
    		}
		}
		$sql.=" from `".$this->getTableName()."` where id='".$id."' and deleted=0";
	
		$result=$this->sql($sql);
		if (mysql_num_rows($result)!=0){
			while($row = mysql_fetch_object($result)){
				foreach ($this as $name=>$value) {
					$this->$name=$row->$name;
				}		
			}
			return true;
		} else {
			return false;	
		}
	}
	function getAll($sqlconditions="",$sqlorders="",$page="",$rowsperpage=""){
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
		//if (($page!="")&&($rowsperpage!="")){
		if (!(empty($page)&&empty($rowsperpage))){	
			$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;;
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
	function getTableName(){
		return strtolower($this->getClassName());
	}
	function __gettemp($name){		
		return $name."_".$this->getLang()->name;
	}
	function getFieldValueByName($name){		
		$rv="no field";
		$fv=$name."_".$this->getLang()->name;
		if (isset($this->$fv)){
			$rv=$this->$fv;
			if ($rv==""){
				$fv=$name."_".Config::$defaultlanguage;
				$rv=$this->$fv;
			}
		}

		return $rv;
	}
	public static function logsql($sql,$status){
		$sql=mysql_real_escape_string($sql);
		$sqllog="INSERT INTO  `logs` (`id` ,`sessionid` ,`sql` ,`status` ,`datetime`) VALUES (NULL ,  '".SessionManager::getSessionId()."',  '".$sql."',  '".$status."',  '".System::getCurentDateTime()."')";
		Logger::setLogs('sqllogger'.$sqllog);
		$result=mysql_query($sqllog, DBConnection::getConnection());
		if (!$result){
			$n=new WebPage();
			$n->redirect(Config::$errorpage);
		}		

	}
}
?>