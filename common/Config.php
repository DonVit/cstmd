<?php
class Config {
	//default language, values:ro,ru,en
	public static $defaultlanguage="ro";

	public static $mainsite="http://casata.it:8081";
	public static $commonsite="http://common.casata.it:8081";
	public static $newssite="http://news.casata.it:8081";
	public static $imobilsite="http://imobil.casata.it:8081";
	public static $chiriesite="http://chirie.casata.it:8081";
	public static $companiesite="http://companies.casata.it:8081";
	public static $mapssite="http://maps.casata.it:8081";
	public static $imagessite="http://photos.casata.it:8081";
	public static $locationssite="http://localitati.casata.it:8081";
	public static $accountssite="http://accounts.casata.it:8081";
	public static $statssite="http://stats.casata.it:8081";
	public static $toolssite="http://tools.casata.it:8081";
	public static $videosite="http://video.casata.it:8081";
	public static $distantesite="http://distante.casata.it:8081";
	public static $telefoanesite="http://telefoane.casata.it:8081";
	public static $primariisite="http://primarii.casata.it:8081";
	public static $dictionarsite="http://dictionar.casata.it:8081";
	public static $calendarsite="http://calendar.casata.it:8081";	
	public static $numesite="http://nume.casata.it:8081";
	public static $adssite="http://ads.casata.it:8081";
	public static $feedssite="http://feeds.casata.it:8081";
	public static $fmsite="http://fm.casata.it:8081";
	public static $errorpage="http://casata.it:8081/error.php";
	public static $alegerisite="http://alegeri.casata.it:8081";
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
	public static $gmapskey = 'AIzaSyDTxVOWG58AJai2C0ds5y2ytKmFOM3nepA';

	public static $adminmail = 'casata.md@outlook.com';
}
?>
