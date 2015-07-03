<?php
class Config {
	//default language, values:ro,ru,en
	public static $defaultlanguage="ro";

	public static $mainsite="http://casata.md";
	public static $commonsite="http://common.casata.md";
	public static $newssite="http://news.casata.md";
	public static $imobilsite="http://imobil.casata.md";
	public static $chiriesite="http://chirie.casata.md";
	public static $companiesite="http://companies.casata.md";
	public static $mapssite="http://maps.casata.md";
	public static $imagessite="http://photos.casata.md";
	public static $locationssite="http://localitati.casata.md";
	public static $accountssite="http://accounts.casata.md";
	public static $statssite="http://stats.casata.md";
	public static $toolssite="http://tools.casata.md";
	public static $videosite="http://video.casata.md";
	public static $distantesite="http://distante.casata.md";
	public static $telefoanesite="http://telefoane.casata.md";
	public static $primariisite="http://primarii.casata.md";
	public static $dictionarsite="http://dictionar.casata.md";
	public static $calendarsite="http://calendar.casata.md";	
	public static $numesite="http://nume.casata.md";
	public static $adssite="http://ads.casata.md";
	public static $feedssite="http://feeds.casata.md";
	public static $errorpage="http://casata.md/error.php";
	public static $cookiedomain=".casata.md";

	public static $mysql_host="localhost";
	public static $mysql_user="root";
	public static $mysql_password="";
	public static $mysql_db="";
	
	public static $loging=false;  //yes/true|no/false
	public static $phploging=0;  //-1 - all php errors; 0 - // Turn off all error reporting
	public static $live=true; //yes/true|no/false
	
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
	public static $file_size = "3145728";

	//Files Folder Path
	public static $filespath="data";
	
	//Recaptcha keys
	public static $publickey = '6LdotAYTAAAAAJJDrX13V5UShqo-mEQNWMD6zpZO'; 
	public static $privatekey = '6LdotAYTAAAAAJYTGqvD9VIHHyzCkEERzOU_5Cc1';

}
?>
