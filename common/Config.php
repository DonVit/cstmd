<?php
class Config {
	//default language, values:ro,ru,en
	public static $defaultlanguage="ro";

	public static $mainsite="http://casata.it";
	public static $commonsite="http://common.casata.it";
	public static $newssite="http://news.casata.it";
	public static $imobilsite="http://imobil.casata.it";
	public static $chiriesite="http://chirie.casata.it";
	public static $companiesite="http://companies.casata.it";
	public static $mapssite="http://maps.casata.it";
	public static $imagessite="http://photos.casata.it";
	public static $locationssite="http://localitati.casata.it";
	public static $accountssite="http://accounts.casata.it";
	public static $statssite="http://stats.casata.it";
	public static $toolssite="http://tools.casata.it";
	public static $videosite="http://video.casata.it";
	public static $distantesite="http://distante.casata.it";
	public static $telefoanesite="http://telefoane.casata.it";
	public static $primariisite="http://primarii.casata.it";
	public static $dictionarsite="http://dictionar.casata.it";
	public static $calendarsite="http://calendar.casata.it";	
	public static $numesite="http://nume.casata.it";
	public static $adssite="http://ads.casata.it";
	public static $feedssite="http://feeds.casata.it";
	public static $errorpage="http://casata.it/error.php";
	public static $cookiedomain=".casata.it";

/*
	public static $mainsite="http://casata.md";
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
	public static $primariisite="http://primarii.casata.md";	
	public static $numesite="http://ads.casata.md";	
	public static $errorpage="http://casata.md/error.php";
	public static $cookiedomain=".casata.md";
*/
	public static $mysql_host="localhost";
	public static $mysql_user="root";
	public static $mysql_password="";
	public static $mysql_db="db3";
	//public static $mysql_db="171209";
	
	public static $loging=true;  //yes/true|no/false
	public static $live=false; //yes/true|no/false
	//gmap key
/*	
	public static function getMapKey($servername){
		$mapkey="";
		if(Config::$live){
			if ($servername==substr(Config::$newssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBRpJw6vIm_21frXtF_N_tN0xQ4WXhSzAQ43XVni-utHWEiHLU53cZj9Nw";
			}
			if ($servername==substr(Config::$imobilsite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBRo8oE9hNTKaXqV63h2GBWw_1YdthQHq7uRYByQ5XUy3j8Y-bC0binNGw";
			}
			if ($servername==substr(Config::$chiriesite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBSxVeGCcFDfp5dn20l6XqP5fyxrSBQ-YP4mjbmEoreY29zxtyXVp2mxRA";
			}
			if ($servername==substr(Config::$companiesite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTvDlilkz86QDRdRcxdqibQwjweMBSoCBdIvnCxwo7E-IdXBnl4pqngbA";
			}
			if ($servername==substr(Config::$imagessite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBQWeE3eXkoIgpCkQmtsp9k2yBBooRRURy8mBJ_Unk5honLYx4UuSiU9yQ";
			}
			if ($servername==substr(Config::$mapssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBSECkQjtaOzL2R89iLCBntgacUyFRTWfJU-Avjp25y-bQQeQbtZnivV-A";
			}
			if ($servername==substr(Config::$locationssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBR7B758-gyHoZfR1-uD63Ulkg_xSxSio6GtwWys4aPBDqM7PRuLKNNETw";
			}
			if ($servername==substr(Config::$toolssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTgERXdCY1YX3xlsz7bnnw4n4je0RS5JMtYDrtC5rtbyWVVvZsfay31mQ";
			}
		} else {
			if ($servername==substr(Config::$newssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBQET-Xljzk0gU_Z0Ks1L74FdbmgfxQJslVcCqn3RBwE-7S8gfpc-gotkQ";
			}
			if ($servername==substr(Config::$imobilsite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBSNpTmPJVWc9UV64lkHCNP0cYR98RTXWGqOlKtByEl9wl0GjbGFJaLqkw";
			}
			if ($servername==substr(Config::$chiriesite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBRsEmU1zNeCglvq4dF3YrV_khWvNRTpPfJTbfix8wxmNYKi6hITOP9R1w";
			}
			if ($servername==substr(Config::$companiesite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBQDzolQZgngqBoyG9RnG-30SSZxhBSar31a9qU7VfA0b6lg4PVznxhsJg";
			}
			if ($servername==substr(Config::$imagessite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTBr3KKp5KENHF1SID58z7FBjzhiRQfQbSL6Tet3yYAX379MpkB3M2Diw";
			}
			if ($servername==substr(Config::$mapssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTPXGvulpZda80YkfJImFStTwg96xTnjmVseGYocZhtBlM_TPB0_ypJpg";
			}
			if ($servername==substr(Config::$locationssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTJ1NiPF16lrGGlGL3gFA-QdDKeeRQwc8JTqlzGN59vmEU7YQQ20d7QsQ";
			}
			if ($servername==substr(Config::$toolssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTacafNjC5UPi7pAz9LnY4LhWjdExS5L1E-AqMv7f68j2nRG80jk2lfqA";
			}
		}
		return $mapkey;
	}
	public static function getGAPIKey($servername){
		$mapkey="";
		if(Config::$live){
			if ($servername==substr(Config::$newssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBRpJw6vIm_21frXtF_N_tN0xQ4WXhSzAQ43XVni-utHWEiHLU53cZj9Nw";
			}
			if ($servername==substr(Config::$imobilsite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBRo8oE9hNTKaXqV63h2GBWw_1YdthQHq7uRYByQ5XUy3j8Y-bC0binNGw";
			}
			if ($servername==substr(Config::$chiriesite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBSxVeGCcFDfp5dn20l6XqP5fyxrSBQ-YP4mjbmEoreY29zxtyXVp2mxRA";
			}
			if ($servername==substr(Config::$companiesite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTvDlilkz86QDRdRcxdqibQwjweMBSoCBdIvnCxwo7E-IdXBnl4pqngbA";
			}
			if ($servername==substr(Config::$imagessite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBQWeE3eXkoIgpCkQmtsp9k2yBBooRRURy8mBJ_Unk5honLYx4UuSiU9yQ";
			}
			if ($servername==substr(Config::$mapssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBSECkQjtaOzL2R89iLCBntgacUyFRTWfJU-Avjp25y-bQQeQbtZnivV-A";
			}
			if ($servername==substr(Config::$locationssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBR7B758-gyHoZfR1-uD63Ulkg_xSxSio6GtwWys4aPBDqM7PRuLKNNETw";
			}
			if ($servername==substr(Config::$toolssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTgERXdCY1YX3xlsz7bnnw4n4je0RS5JMtYDrtC5rtbyWVVvZsfay31mQ";
			}
		} else {
			if ($servername==substr(Config::$newssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBQET-Xljzk0gU_Z0Ks1L74FdbmgfxQJslVcCqn3RBwE-7S8gfpc-gotkQ";
			}
			if ($servername==substr(Config::$imobilsite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBSNpTmPJVWc9UV64lkHCNP0cYR98RTXWGqOlKtByEl9wl0GjbGFJaLqkw";
			}
			if ($servername==substr(Config::$chiriesite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBRsEmU1zNeCglvq4dF3YrV_khWvNRTpPfJTbfix8wxmNYKi6hITOP9R1w";
			}
			if ($servername==substr(Config::$companiesite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBQDzolQZgngqBoyG9RnG-30SSZxhBSar31a9qU7VfA0b6lg4PVznxhsJg";
			}
			if ($servername==substr(Config::$imagessite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTBr3KKp5KENHF1SID58z7FBjzhiRQfQbSL6Tet3yYAX379MpkB3M2Diw";
			}
			if ($servername==substr(Config::$mapssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTPXGvulpZda80YkfJImFStTwg96xTnjmVseGYocZhtBlM_TPB0_ypJpg";
			}
			if ($servername==substr(Config::$locationssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTJ1NiPF16lrGGlGL3gFA-QdDKeeRQwc8JTqlzGN59vmEU7YQQ20d7QsQ";
			}
			if ($servername==substr(Config::$toolssite,7)){
				$mapkey="ABQIAAAAb_gfrYZFk_yGsf7Lt3YnMBTacafNjC5UPi7pAz9LnY4LhWjdExS5L1E-AqMv7f68j2nRG80jk2lfqA";
			}
		}
		return $mapkey;
	}
*/		
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
	
	
}
?>