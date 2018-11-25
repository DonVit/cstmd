<?php
class Config {
	//default language, values:ro,ru,en
	public static $defaultlanguage="ro";

	public static $mainsite="https://casata.it";
	public static $commonsite="https://common.casata.it";
	public static $newssite="https://news.casata.it";
	public static $imobilsite="https://imobil.casata.it";
	public static $chiriesite="https://chirie.casata.it";
	public static $companiesite="https://companies.casata.it";
	public static $mapssite="https://maps.casata.it";
	public static $imagessite="https://photos.casata.it";
	public static $locationssite="https://localitati.casata.it";
	public static $accountssite="https://accounts.casata.it";
	public static $statssite="https://stats.casata.it";
	public static $toolssite="https://tools.casata.it";
	public static $videosite="https://video.casata.it";
	public static $distantesite="https://distante.casata.it";
	public static $telefoanesite="https://telefoane.casata.it";
	public static $primariisite="https://primarii.casata.it";
	public static $dictionarsite="https://dictionar.casata.it";
	public static $calendarsite="https://calendar.casata.it";	
	public static $numesite="https://nume.casata.it";
	public static $adssite="https://ads.casata.it";
	public static $feedssite="https://feeds.casata.it";
	public static $fmsite="https://fm.casata.it";
	public static $errorpage="https://casata.it/error.php";
	public static $alegerisite="https://alegeri.casata.it";
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
}
?>
