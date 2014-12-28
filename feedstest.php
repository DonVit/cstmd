<?php
/*
 * Created on 25 Feb 2009
 *
 */
//ini_set("memory_limit","200M");
//set_time_limit(10720);
require_once('loader.php');

class IndexWebPage extends WebPage {
	function __construct(){
		parent::__construct();
		$this->show();		
	}
	function show($html="IndexWebPageHTML"){

		$out="";
		$feedscount=0;
		
		$feed="http://trm.md/rss/ro/economic.xml";
				

			//$xml_content = file_get_contents($f->rssfeed,false,$context);
			$feeddownloadstatus=0;
			$feedparsestatus=0;
			$errormessage="";
			//$xml_content = file_get_contents($f->rssfeed);
			
			$ch = curl_init($feed);
			
			//curl_setopt($ch, CURLOPT_URL, $f->rssfeed);
			//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			//curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt ($ch, CURLINFO_HEADER_OUT, 1);
			
			

			//curl_setopt($ch, CURLOPT_URL,$feed);
			
			
			//var_dump(curl_exec ($ch));
			
			/*
			
			$verbose = fopen('php://temp', 'rw+');
			curl_setopt($ch, CURLOPT_STDERR, $verbose);
			
			//$out=$this->curl_exec_follow($ch);
			var_dump(curl_exec ($ch));
			var_dump(curl_getinfo($ch));
			
			curl_close ($ch);
			
			!rewind($verbose);
			$verboseLog = stream_get_contents($verbose);
			
			echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
			*/
			
			$curlDefault = array(
					CURLOPT_PORT => 80, //ignore explicit setting of port 80
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_FOLLOWLOCATION => FALSE,
					CURLOPT_ENCODING => '',
					CURLOPT_HTTPHEADER => array(
							'Proxy-Connection: Close',
							'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1017.2 Safari/535.19',
							'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
							'Accept-Encoding: gzip,deflate,sdch',
							'Accept-Language: en-US,en;q=0.8',
							'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3',
							'Cookie: __qca=blabla',
							'Connection: Close',
					),
					CURLOPT_VERBOSE => TRUE, // TRUE to output verbose information. Writes output to STDERR, or the file specified using CURLOPT_STDERR.
					CURLOPT_STDERR => $verbose = fopen('php://temp', 'rw+'),
			);
			
			$url = "http://trm.md/rss/ro/economic.xml";
			$handle = curl_init($url);
			curl_setopt_array($handle, $curlDefault);
			$html = curl_exec($handle);
			$urlEndpoint = curl_getinfo($handle, CURLINFO_EFFECTIVE_URL);
			echo "Verbose information:\n<pre>", !rewind($verbose), htmlspecialchars(stream_get_contents($verbose)), "</pre>\n";
			echo $html;
			curl_close($handle);		
		WebPage::show($out);
	}

	function curl_exec_follow(/*resource*/ $ch, /*int*/ &$maxredirect = null) {
		$mr = $maxredirect === null ? 5 : intval($maxredirect);
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
			curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
		} else {
			echo "safe mode on";
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
			if ($mr > 0) {
				$newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	
				$rch = curl_copy_handle($ch);
				curl_setopt($rch, CURLOPT_HEADER, true);
				curl_setopt($rch, CURLOPT_NOBODY, true);
				curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
				curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
				do {
					curl_setopt($rch, CURLOPT_URL, $newurl);
					$header = curl_exec($rch);
					if (curl_errno($rch)) {
						$code = 0;
					} else {
						$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
						if ($code == 301 || $code == 302) {
							preg_match('/Location:(.*?)\n/', $header, $matches);
							$newurl = trim(array_pop($matches));
						} else {
							$code = 0;
						}
					}
				} while ($code && --$mr);
				curl_close($rch);
				if (!$mr) {
					if ($maxredirect === null) {
						trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
					} else {
						$maxredirect = 0;
					}
					return false;
				}
				curl_setopt($ch, CURLOPT_URL, $newurl);
			}
		}
		return curl_exec($ch);
	}	
	
}
$n=new IndexWebPage();
?>
