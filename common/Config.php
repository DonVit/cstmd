<?php
class Config {
	//default language, values:ro,ru,en
	public static $defaultlanguage="ro";

	public static $mainsite="http://casata.it:8080";
	public static $commonsite="http://common.casata.it:8080";
	public static $newssite="http://news.casata.it:8080";
	public static $imobilsite="http://imobil.casata.it:8080";
	public static $chiriesite="http://chirie.casata.it:8080";
	public static $companiesite="http://companies.casata.it:8080";
	public static $mapssite="http://maps.casata.it:8080";
	public static $imagessite="http://photos.casata.it:8080";
	public static $locationssite="http://localitati.casata.it:8080";
	public static $accountssite="http://accounts.casata.it:8080";
	public static $statssite="http://stats.casata.it:8080";
	public static $toolssite="http://tools.casata.it:8080";
	public static $videosite="http://video.casata.it:8080";
	public static $distantesite="http://distante.casata.it:8080";
	public static $telefoanesite="http://telefoane.casata.it:8080";
	public static $primariisite="http://primarii.casata.it:8080";
	public static $dictionarsite="http://dictionar.casata.it:8080";
	public static $calendarsite="http://calendar.casata.it:8080";	
	public static $numesite="http://nume.casata.it:8080";
	public static $adssite="http://ads.casata.it:8080";
	public static $feedssite="http://feeds.casata.it:8080";
	public static $fmsite="http://fm.casata.it:8080";
	public static $errorpage="http://casata.it:8080/error.php";
	public static $alegerisite="http://alegeri.casata.it:8080";
	public static $cookiedomain=".casata.it";

	public static $mysql_host="localhost";
	public static $mysql_user="root";
	public static $mysql_password="";
	public static $mysql_db="cst";
	
	public static $loging=true;  //yes/true|no/false
	public static $phploging=-1;  //-1 - all php errors; 0 - // Turn off all error reporting
	public static $live=false; //yes/true|no/false
	
	//Allowed images to upload
	public static $file_types = array(
		'image/pjpeg'     => 'jpg',
		'image/jpeg'     => 'jpg',
		'image/jpeg'     => 'jpeg',
		'image/gif'     => 'gif',
		'image/X-PNG'    => 'png',
		'image/PNG'         => 'png',
		'image/png'     => 'png',
		'image/x-png'     => 'png',
		'image/JPG'     => 'jpg',
		'image/GIF'     => 'gif',
		//'image/bmp'     => 'bmp',
		//'image/bmp'     => 'BMP',
	);

	//Allowed File Size in bytes
	//public static $file_size = "1048576";
	public static $file_size = "5145728";

	//Files Folder Path
	public static $filespath="data";
	
	//Recaptcha keys
	//public static $publickey = '6LdotAYTAAAAAJJDrX13V5UShqo-mEQNWMD6zpZO'; 
	//public static $privatekey = '6LdotAYTAAAAAJYTGqvD9VIHHyzCkEERzOU_5Cc1';
	public static $publickey = '6LfaSgkTAAAAACzsUEaIstWtJ_F8yE3Ul5t3b7_P'; 
	public static $privatekey = '6LfaSgkTAAAAAGTkTWUa0P_Zvq2bkRJwZqEzyD2X';
	public static $gmapskey = 'AIzaSyAPy4xpXsRZdvlEN2mAdXq6LJpkCZ6wniw';

	public static $adminmail = 'casata.md@outlook.com';

	public static function initFromEnv(){
		$baseurl=getenv('APP_BASE_URL');
		// if ($baseurl!==false && $baseurl!==''){
		// 	$baseurl=rtrim($baseurl,'/');
		// 	self::$mainsite=$baseurl.'/main';
		// 	self::$commonsite=$baseurl.'/common';
		// 	self::$newssite=$baseurl.'/news';
		// 	self::$imobilsite=$baseurl.'/imobil';
		// 	self::$chiriesite=$baseurl.'/chirie';
		// 	self::$companiesite=$baseurl.'/companies';
		// 	self::$mapssite=$baseurl.'/maps';
		// 	self::$imagessite=$baseurl.'/photos';
		// 	self::$locationssite=$baseurl.'/locations';
		// 	self::$accountssite=$baseurl.'/accounts';
		// 	self::$statssite=$baseurl.'/stats';
		// 	self::$toolssite=$baseurl.'/tools';
		// 	self::$videosite=$baseurl.'/video';
		// 	self::$distantesite=$baseurl.'/distante';
		// 	self::$telefoanesite=$baseurl.'/telefoane';
		// 	self::$primariisite=$baseurl.'/primarii';
		// 	self::$dictionarsite=$baseurl.'/dictionar';
		// 	self::$calendarsite=$baseurl.'/calendar';
		// 	self::$numesite=$baseurl.'/nume';
		// 	self::$adssite=$baseurl.'/ads';
		// 	self::$feedssite=$baseurl.'/feeds';
		// 	self::$fmsite=$baseurl.'/fm';
		// 	self::$errorpage=$baseurl.'/error.php';
		// 	self::$alegerisite=$baseurl.'/alegeri';
		// }
		if (getenv('DB_HOST')!==false){self::$mysql_host=getenv('DB_HOST');}
		if (getenv('DB_USER')!==false){self::$mysql_user=getenv('DB_USER');}
		if (getenv('DB_PASSWORD')!==false){self::$mysql_password=getenv('DB_PASSWORD');}
		if (getenv('DB_NAME')!==false){self::$mysql_db=getenv('DB_NAME');}
		if (getenv('COOKIE_DOMAIN')!==false){self::$cookiedomain=getenv('COOKIE_DOMAIN');}
	}
}

Config::initFromEnv();
?>
