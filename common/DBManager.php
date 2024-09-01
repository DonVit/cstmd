<?php
class DBManager extends MainObject {
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
		$result=mysqli_query(DBConnection::getConnection(), $sql);	
		// print_r($result);
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
		$result=mysqli_query(DBConnection::getConnection(), $sql);
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
		// $fields=mysqli_num_fields($result);
		$fieldsinfo = $result -> fetch_fields();
		$arr=array();
		while($row = mysqli_fetch_object($result)){
			$o=new MainObject();
			// for ($i=0; $i < $fields; $i++) {
    		// 	$n=mysqli_fetch_field($result, $i);
    		// 	$o->$n=$row->$n;
			// }
			foreach ($fieldsinfo as $val) {
				// printf("Name: %s\n", $val -> name);
				// printf("Table: %s\n", $val -> table);
				// printf("Max. Len: %d\n", $val -> max_length);
				$n = $val -> name;
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
		$result=mysqli_query(DBConnection::getConnection(), $sql);
		if (!$result){
			DBManager::logsql($sql,'1');
			$n=new WebPage();
			$n->redirect(Config::$errorpage);	
		}
		return $result;
	}	
	function save(){
		$f="";
		$v="";
		echo 'save:'.$this->id;
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
		    			$f.=" `".mysqli_real_escape_string(DBConnection::getConnection(), $name)."`";
		    			$v.=" '".mysqli_real_escape_string(DBConnection::getConnection(), $value)."'";
		    		} else {
						Logger::setLogs('fv='.$name.':'.$value);
		    			$f.=", `".mysqli_real_escape_string(DBConnection::getConnection(), $name)."`";
		    			$v.=", '".mysqli_real_escape_string(DBConnection::getConnection(), $value)."'";
		    			
		    		}
	    		}
			}
			$sql=" insert into `".$this->getTableName()."` (".$f.") values (".$v.")";
			// echo 'save sql'.$sql;
			// execute the sql query
			if ($this->sql($sql)){
				$this->id = mysqli_insert_id(DBConnection::getConnection());
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
	    				$f.=" `".$name."`='".mysqli_real_escape_string(DBConnection::getConnection(), $value)."'";
	    			} else {
	    				//$f.=" ,`".$name."`='".$value."'";
	    				$f.=" ,`".$name."`='".mysqli_real_escape_string(DBConnection::getConnection(), $value)."'";
	    			}
	    		}	    			    		
			}
			$sql=" update `".$this->getTableName()."` set ".$f." where `id`='".$this->id."'";
			echo 'update sql'.$sql;
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
		$num_rows = mysqli_num_rows($result);
		$o=null;
		if ($num_rows!=0){
			while($row = mysqli_fetch_object($result)){
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
		if (mysqli_num_rows($result)!=0){
			while($row = mysqli_fetch_object($result)){
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
		// echo $sql;	
		return $this->getResult($this->sql($sql));	
	}

	function getResult($result){
		$arr=array();
		while($row = mysqli_fetch_object($result)){
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
		$sql=mysqli_real_escape_string(DBConnection::getConnection(), $sql);
		$sqllog="INSERT INTO  `logs` (`id` ,`sessionid` ,`sql` ,`status` ,`datetime`) VALUES (NULL ,  '".SessionManager::getSessionId()."',  '".$sql."',  '".$status."',  '".System::getCurentDateTime()."')";
		Logger::setLogs('sqllogger'.$sqllog);
		$result=mysqli_query(DBConnection::getConnection(), $sqllog);
		if (!$result){
			$n=new WebPage();
			$n->redirect(Config::$errorpage);
		}		

	}
}
?>