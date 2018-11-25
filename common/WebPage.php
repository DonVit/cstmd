<?php
/*
 * Created on 24 Feb 2009
 *
 */
class WebPage extends Object {
	private $webpageid=0;
	private $contenttype="text/html";
	private $charset="utf-8";
	private $download=false;
	private $url="";
	private $title="web page";	
	private $description="description";
	private $keywords="keywords";
	//private $html="html";
	
	//public function __construct($contenttype="text/html",$charset="utf-8") {
		
	public function __construct() {
		//$this->contenttype=$contenttype;
		//$this->charset=$charset;
		$this->getHeaders();
		$this->setView();
		//$this->setWebPage();
 
 		if (isset($_GET["id"])){
			if (!is_numeric($_GET["id"])){
				$this->redirect(Config::$errorpage);
			}
		} 
		
		foreach ($_POST as $field => $value) {
			$this->$field=$this->getParamValue($value);
		}
		foreach ($_GET as $field => $value) {
			$this->$field=$this->getParamValue($value);
		}
		//set language
		if (isset($this->l)){
			$this->setLang($this->l);		  
		} else {
			$this->setLang('ro');
		}
		
	}
	function getHeaders(){
		//ini_set('session.cookie_domain',substr($_SERVER['SERVER_NAME'],strpos($_SERVER['SERVER_NAME'],"."),100));
		ini_set('session.cookie_domain',Config::$cookiedomain);
		header('Content-Type: '.$this->getContentType().'; charset='.$this->getCharset());
		if ($this->getDownload()){
			header('Content-Disposition: attachment; filename='.$this->getFileName());
		}
		//session_start();
		if (!session_id()) session_start();
	}
	function getContentType(){
		return $this->contenttype;
	}
	function setContentType($contenttype){
		$this->contenttype=$contenttype;
	}
	function getDownload(){
		return $this->download;
	}
	function setDownload($value){
		$this->download=$value;
	}	
	function getCharset(){
		return $this->charset;
	}
	function setCharset($charset){
		$this->charset=$charset;
	}
	function getTitle(){
		return $this->title;
	}
	function setTitle($title){
		$this->title=$title;
	}
	function getDescription(){
		return $this->description;
	}
	function setDescription($description){
		$this->description=$description;
	}
	function getKeywords(){
		return $this->keywords;
	}
	function setKeywords($keywords){
		$this->keywords=$keywords;
	}
//	function getBaseName(){
//		$requri=$_SERVER["REQUEST_URI"];
//		return basename(parse_url($requri, PHP_URL_PATH));
//	}
	function getFileName(){
		$requri=$_SERVER["REQUEST_URI"];
		return basename(parse_url($requri, PHP_URL_PATH));
	}
	function getServerName(){
		$requri=$_SERVER["SERVER_NAME"];
		return $requri;
	}	
	function getRequestURI(){
		$requri=$_SERVER["REQUEST_URI"];
		return $requri;
	}	
	function show($html="WebPageHtml"){
		echo $html;
	}
	function redirect($page){
		header("Location: $page");		
	}
	function logout(){
		// Unset all of the session variables.
		session_unset();
		// Finally, destroy the session.
		session_destroy();
	}
	function getParamValue($param)
	{
		if(isset($param)){
			return $param!=""?$param:NULL;
		}else{
		return NULL;
		}
	}
	function setView(){
		$v=new Views();
		$v->session=SessionManager::getSessionId();
		$v->ip=getenv("REMOTE_ADDR");
		$v->referer=getenv("HTTP_REFERER");
		$v->agent=getenv("HTTP_USER_AGENT");
		//$v->query=getenv("REQUEST_URI");
		$v->query='https://'.getenv("SERVER_NAME").getenv("REQUEST_URI");
		$v->datetime=System::getCurentDateTime();
		//$v->save();
	}
	function cleanText($intext) {
	    return utf8_encode(
	        htmlspecialchars(
	            stripslashes($intext)));
	}
	/*
	function setWebPage(){
		$sql="select * from webpage where name='".$this->getClassName()."'";
		$wp=DBManager::doSql($sql);
		if (count($wp)!=0){
			$this->webpageid=$wp[0]->id;
		}
	}
	*/		

	function getUrlInLanguage($l=""){	
		//http_build_query ()
		$url=$this->getFileName();
		if ($l==""){
			$url.="?l=".$this->getLang()->name;
		} else {
			$url.="?l=".$l;
		}
		foreach ($_GET as $field => $value) {
			if ($field!="l"){
				$url.="&".$field."=".$this->getParamValue($value);
			}
		}
		return htmlspecialchars($url);
	}		
	function create(){
		if (isset($this->action)){
			$actionmethod="action".$this->action;
			if (method_exists($this,$actionmethod)){				
				//call_user_method($actionmethod,$this); //depricated
				call_user_func(array($this, $actionmethod)	);
			} else {
				$this->actionDefault();
			}
		} else {
			$this->actionDefault();
		}
	}					
}
//$b=new WebPage();
//phpinfo();
?>
