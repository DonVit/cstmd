<?php
class Object {
 	public function getClassName(){
		return get_class($this);
	}
    public function toString() {
        $out="Class:".$this->getClassName()." [";
        foreach ($this as $f=>$v){
        	$out.=$f."=".$v."; ";
        }
        $out.="]";
        return $out;
    }
	public function isMember($member){
		$return=false;
		$class_vars = get_class_vars($this->getClassName());
		foreach ($class_vars as $name => $value) {
		    if ($name==$member){
		    	$return=true;
		    	break;
		    }
		}
		return $return;
	}
	function getBaseName(){
		$requri=$_SERVER["REQUEST_URI"];
		return basename(parse_url($requri, PHP_URL_PATH));
	}
	//apache fails when invoking this method through echo method
    public function __toString() {
        $out="Class:".$this->getClassName()." [";
        foreach ($this as $f=>$v){
        	$out.="<br>".$f."=".$v."; ";
        }
        $out.="]";
        return $out;
    }
	function setLang($lang){
		if (SessionManager::isObject("lang")){
			$wpl=SessionManager::getObject("lang");	
			if ($wpl->name!=$lang){
				$twpl=new Language();
				$twpls=$twpl->getAll();
				foreach($twpls as $twpl){
					if ($twpl->name==$lang){
						SessionManager::setObject("lang",$twpl);
						break; 
					}
				}
			}	
		}
		
	}
	function getLang(){
		if (!SessionManager::isObject("lang")){
			$wpl=new Language();
			$wpls=$wpl->getAll("`default`=1");	
			SessionManager::setObject("lang",$wpls[0]);
		}
		$wpl=SessionManager::getObject("lang");		
		return $wpl;
	}
	function setConstants($wpis){
		SessionManager::setObject("Constants",$wpis);
	}
	function getConstants($key){
		$value="";		
		if (!SessionManager::isObject("Constants")){
			$wpi=new Constant();
			//$wpis=$wpi->getAll("webpageid=".$this->webpageid);
			$wpis=$wpi->getAll();	
			$this->setConstants($wpis);
		}
		$wpis=SessionManager::getObject("Constants");
		foreach($wpis as $i){
			if ($i->key==$key){
				$v="value_".$this->getLang()->name;
				$value=$i->$v;
				if ($value==""){
					$v="value_".Config::$defaultlanguage;	
					$value=$i->$v;					
				}
				break;
			}
		}	
		return $value;		
	}
	function getUrl($url,$params=""){	
		if (!empty($params)){
			$url.="?l=".$this->getLang()->name."&".$params;
		} else {
			$url.="?l=".$this->getLang()->name;
		}
		return $url;
	}
	function getUrlWithSpecialCharsConverted($url,$params=""){	
		return htmlspecialchars($this->getUrl($url,$params));
	}	
	function getQueryParamFromUrl($p,$url){
    	$r=NULL;
    	if (!empty($url)) {
    	$queryParts = explode('&', parse_url($url, PHP_URL_QUERY)); 
    	$params = array(); 
    	//if (count($queryParts)!=0) {
	    	foreach ($queryParts as $param) { 
	        	$item = explode('=', $param); 
	        	$params[$item[0]] = $item[1];
	        	//log('ss','sss');
	    	} 
	    	foreach ($params as $param=>$value){
	    		if ($param=="v"){
	    			$r=$value;
	    		}
	    	}
    	//}
    	}
    	return $r; 		
	}				
}
?>
