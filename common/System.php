<?php
class System{
	public static function getCurentDateTime(){
		$date = getdate();
		return $date["year"]."-".$date["mon"]."-".$date["mday"]." ".$date["hours"].":".$date["minutes"].":".$date["seconds"];
	}
	public static function getCurentDate(){
		$date = getdate();
		return $date["year"]."-".$date["mon"]."-".$date["mday"];
	}
	public static function getCurentTime(){
		$date = getdate();
		return $date["hours"].":".$date["minutes"].":".$date["seconds"];
	}
	public static function getDate($date){
		return $date["year"]."-".$date["mon"]."-".$date["mday"];
	}
	public static function getTime($date){
		return $date["hours"].":".$date["minutes"].":".$date["seconds"];
	}	
	//Random numbers generator
	public static function _getValidationCode(){
		$code=rand(1000,9999);
		return $code;
	}
	//Returns random filename
	public static function getRandomFileName($basefile){
		return uniqid().substr($basefile, strrpos($basefile, '.'));
	}
	public static function getDomainFromURL($url){
		//get host from url
		$h=parse_url($url);
		$host=isset($h['host'])?$h['host']:'';
		// count dots
		$dot_num = substr_count($host, '.');
		// set initial search position
		$dot_pos = 0;
		// if subdomains exist
		if ($dot_num > 1){
			// find up to the penultimate dot
			for($i=1; $i < $dot_num-1; $i++){
				// reset pos
				$dot_pos = strpos($host, '.', $dot_pos) + 1;
			}
		}
		// get domain
		$domain = substr($host, $dot_pos);
		return $domain;
	}
	public static function getURLAmpReplace($url){
		return str_replace("&","&amp;",$url);
	}
	public static function getHTML($text){
		return nl2br(htmlspecialchars($text));
	}
	public static function getValidUrl($url){	
	   	return $url;
   }
	public static function getHtmlSpecialChars($text){
		return htmlspecialchars($text);
	}
	public static function getMaxValueRounded($maxvalue){
		$len=strlen($maxvalue);
		return $maxvalue+pow(10,$len-1);
	}
}
?>
